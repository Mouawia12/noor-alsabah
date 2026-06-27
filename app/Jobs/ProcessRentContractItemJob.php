<?php

namespace App\Jobs;

use App\Models\AiAuditLog;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Services\Ai\ExtractionManager;
use App\Services\Ai\Schemas\ContractSchema;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

/**
 * معالجة عقد إيجار واحد: استخراج → كشف تكرار (برقم العقد) → بحاجة مراجعة.
 */
class ProcessRentContractItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries = 3;
    public array $backoff = [10, 30, 60];

    public function __construct(public int $itemId) {}

    public function handle(ExtractionManager $manager): void
    {
        $item = RentContractImportItem::find($this->itemId);
        if (! $item) {
            return;
        }

        $item->update(['status' => RentContractImportItem::STATUS_PROCESSING]);

        try {
            $images = array_filter(explode(',', (string) $item->source_file_path));
            $engine = $manager->engine();

            $result = $engine->extract($images, ContractSchema::schema(), ContractSchema::prompt());
            $threshold = (float) config('ai.confidence_threshold', 0.8);

            if (config('ai.escalate_on_low_conf') && $result->confidence !== null && $result->confidence < $threshold) {
                $heavy = $engine->extract($images, ContractSchema::schema(), ContractSchema::prompt(), ['heavy' => true]);
                if (($heavy->confidence ?? 0) > ($result->confidence ?? 0)) {
                    $result = $heavy;
                }
            }

            $data = $result->data;
            $dup = $this->checkDuplicate($data);

            $item->update([
                'extracted_json'   => ['data' => $data, 'duplicate' => $dup],
                'confidence'       => $result->confidence,
                'field_confidence' => $result->fieldConfidence,
                'is_duplicate'     => $dup['is_duplicate'],
                'status'           => RentContractImportItem::STATUS_NEEDS_REVIEW,
            ]);

            AiAuditLog::record('rent_item', $item->id, 'extracted', [
                'confidence' => $result->confidence,
                'model'      => $result->model,
                'duplicate'  => $dup['is_duplicate'],
            ], $item->batch->create_user ?? null);

            $this->bumpBatch($item->batch_id, true);
        } catch (\Throwable $e) {
            $item->update(['status' => RentContractImportItem::STATUS_FAILED, 'error_reason' => $e->getMessage()]);
            AiAuditLog::record('rent_item', $item->id, 'failed', ['error' => $e->getMessage()], $item->batch->create_user ?? null);
            $this->bumpBatch($item->batch_id, false);
            throw $e;
        }
    }

    /** كشف تكرار العقد برقم العقد في shop_rent. */
    protected function checkDuplicate(array $data): array
    {
        $no = trim((string) ($data['contract_no'] ?? ''));
        if ($no !== '') {
            $row = DB::table('shop_rent')
                ->where('contract_no', $no)->orWhere('rent_no', $no)
                ->first(['shop_rent_id']);
            if ($row) {
                return ['is_duplicate' => true, 'shop_rent_id' => (int) $row->shop_rent_id];
            }
        }
        return ['is_duplicate' => false, 'shop_rent_id' => null];
    }

    protected function bumpBatch(int $batchId, bool $processed): void
    {
        $batch = RentContractImportBatch::find($batchId);
        if (! $batch) {
            return;
        }
        $processed ? $batch->increment('processed_items') : $batch->increment('failed_items');
        $batch->refresh();
        if (($batch->processed_items + $batch->failed_items) >= $batch->total_items && $batch->total_items > 0) {
            $batch->update(['status' => RentContractImportBatch::STATUS_COMPLETED]);
        }
    }
}
