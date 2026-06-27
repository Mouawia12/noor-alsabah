<?php

namespace App\Jobs;

use App\Models\AiAuditLog;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Services\Ai\ExtractionManager;
use App\Services\Ai\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

/**
 * معالجة دفعة عقود إيجار: تحويل لصور، تجزئة بكشف حدود ذكي، وجدولة معالجة كل عقد.
 */
class ProcessRentContractBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800;
    public int $tries = 1;

    public function __construct(public int $batchId) {}

    public function handle(PdfService $pdf, ExtractionManager $manager): void
    {
        $batch = RentContractImportBatch::find($this->batchId);
        if (! $batch) {
            return;
        }

        $batch->update(['status' => RentContractImportBatch::STATUS_PROCESSING]);

        try {
            $disk = config('ai.disk');
            $absPdf = Storage::disk($disk)->path($batch->file_path);
            $workDir = Storage::disk($disk)->path('rent/work/' . $batch->id);

            $images = $pdf->rasterizeAll($absPdf, $workDir);
            if (empty($images)) {
                throw new \RuntimeException('لم تُنتج أي صور من الملف.');
            }

            $groups = $this->segment($images, $manager);

            foreach ($groups as $group) {
                $paths = array_map(fn ($i) => $images[$i], $group);
                RentContractImportItem::create([
                    'batch_id'         => $batch->id,
                    'page_from'        => $group[0] + 1,
                    'page_to'          => end($group) + 1,
                    'source_file_path' => implode(',', $paths),
                    'page_hash'        => $this->hashPages($paths),
                    'status'           => RentContractImportItem::STATUS_PENDING,
                ])->each(fn ($item) => ProcessRentContractItemJob::dispatch($item->id));
            }

            $batch->update(['total_items' => count($groups)]);
            AiAuditLog::record('rent_batch', $batch->id, 'segmented', ['items' => count($groups)], $batch->create_user);
        } catch (\Throwable $e) {
            $batch->update(['status' => RentContractImportBatch::STATUS_FAILED, 'error_reason' => $e->getMessage()]);
            AiAuditLog::record('rent_batch', $batch->id, 'failed', ['error' => $e->getMessage()], $batch->create_user);
            throw $e;
        }
    }

    /** @param string[] $images @return int[][] */
    protected function segment(array $images, ExtractionManager $manager): array
    {
        $n = count($images);
        if ($n === 1) {
            return [[0]];
        }
        try {
            $engine = $manager->engine();
            $groups = [[0]];
            for ($i = 1; $i < $n; $i++) {
                $res = $engine->classifyBoundary($images[$i]);
                if (! empty($res['is_new_document'])) {
                    $groups[] = [$i];
                } else {
                    $groups[count($groups) - 1][] = $i;
                }
            }
            return $groups;
        } catch (\Throwable $e) {
            return array_map(fn ($i) => [$i], range(0, $n - 1));
        }
    }

    /** @param string[] $paths */
    protected function hashPages(array $paths): string
    {
        $h = '';
        foreach ($paths as $p) {
            $h .= is_file($p) ? hash_file('sha256', $p) : $p;
        }
        return hash('sha256', $h);
    }
}
