<?php

namespace App\Jobs;

use App\Models\AiAuditLog;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Services\Ai\DuplicateDetectionService;
use App\Services\Ai\ExtractionManager;
use App\Services\Ai\InvoiceValidationService;
use App\Services\Ai\Schemas\InvoiceSchema;
use App\Services\Ai\SupplierMatchingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * معالجة عنصر (فاتورة) واحد: استخراج → تحقق → كشف تكرار → مطابقة مورد.
 * ينتهي العنصر دائماً عند "بحاجة مراجعة" (الإنسان في الحلقة) أو "فشل".
 */
class ProcessPurchaseItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries = 3;
    public array $backoff = [10, 30, 60];

    public function __construct(public int $itemId) {}

    public function handle(
        ExtractionManager $manager,
        InvoiceValidationService $validator,
        DuplicateDetectionService $dedup,
        SupplierMatchingService $suppliers
    ): void {
        $item = PurchaseImportItem::find($this->itemId);
        if (! $item) {
            return;
        }

        $item->update(['status' => PurchaseImportItem::STATUS_PROCESSING]);

        try {
            $images = array_filter(explode(',', (string) $item->source_file_path));
            $engine = $manager->engine();

            // التعلّم المستمر: أضف تلميحات من تصحيحات سابقة إلى المطالبة
            $prompt = InvoiceSchema::prompt() . app(\App\Services\Ai\CorrectionService::class)->hints('purchase');

            // 1) استخراج (مع تصعيد للنموذج الأقوى عند انخفاض الثقة)
            $result = $engine->extract($images, InvoiceSchema::schema(), $prompt);
            $threshold = (float) config('ai.confidence_threshold', 0.8);

            if (config('ai.escalate_on_low_conf') && $result->confidence !== null && $result->confidence < $threshold) {
                $heavy = $engine->extract($images, InvoiceSchema::schema(), $prompt, ['heavy' => true]);
                if (($heavy->confidence ?? 0) > ($result->confidence ?? 0)) {
                    $result = $heavy;
                    $result->calls++;
                }
            }

            $data = $result->data;

            // 2) تحقق منطقي
            $validation = $validator->validate($data);

            // 3) كشف تكرار
            $dup = $dedup->check($data);

            // 4) مطابقة مورد (اقتراح فقط)
            $supplier = $suppliers->match($data['supplier_name'] ?? null, $data['tax_number'] ?? null);

            // تخزين النتائج + الميتا للمراجعة
            $item->update([
                'extracted_json' => [
                    'data'       => $data,
                    'validation' => $validation,
                    'supplier'   => $supplier,
                    'duplicate'  => $dup,
                ],
                'confidence'               => $result->confidence,
                'field_confidence'         => $result->fieldConfidence,
                'is_duplicate'             => $dup['is_duplicate'],
                'duplicate_of_purchase_id' => $dup['purchase_id'],
                'status'                   => PurchaseImportItem::STATUS_NEEDS_REVIEW,
                'error_reason'             => $validation['issues'] ? implode(' | ', $validation['issues']) : null,
            ]);

            AiAuditLog::record('purchase_item', $item->id, 'extracted', [
                'confidence' => $result->confidence,
                'model'      => $result->model,
                'duplicate'  => $dup['is_duplicate'],
                'issues'     => $validation['issues'],
            ], $item->batch->create_user ?? null);

            $this->bumpBatch($item->batch_id, processed: true);
        } catch (\Throwable $e) {
            $item->update([
                'status'       => PurchaseImportItem::STATUS_FAILED,
                'error_reason' => $e->getMessage(),
            ]);
            AiAuditLog::record('purchase_item', $item->id, 'failed', ['error' => $e->getMessage()], $item->batch->create_user ?? null);
            $this->bumpBatch($item->batch_id, processed: false);
            throw $e;
        }
    }

    /** تحديث عدّادات الدفعة وإغلاقها عند اكتمال كل العناصر. */
    protected function bumpBatch(int $batchId, bool $processed): void
    {
        $batch = PurchaseImportBatch::find($batchId);
        if (! $batch) {
            return;
        }

        $processed ? $batch->increment('processed_items') : $batch->increment('failed_items');

        $batch->refresh();
        if (($batch->processed_items + $batch->failed_items) >= $batch->total_items && $batch->total_items > 0) {
            // دمج صفحات التكملة مع فواتيرها (لا تظهر صفحة فارغة كفاتورة)
            $items = PurchaseImportItem::where('batch_id', $batch->id)
                ->where('status', PurchaseImportItem::STATUS_NEEDS_REVIEW)
                ->orderBy('page_from')->get();
            $docs = app(\App\Services\Ai\PageMergeService::class)->merge(
                $items, ['invoice_no'], ['total', 'amount_before_tax'], PurchaseImportItem::STATUS_MERGED
            );

            $batch->update(['status' => PurchaseImportBatch::STATUS_COMPLETED]);
            AiAuditLog::record('purchase_batch', $batch->id, 'merged', ['documents' => $docs], $batch->create_user);
            $this->notifyUploader($batch);
        }
    }

    /** إشعار من رفع الدفعة باكتمالها (إن كان له بريد). */
    protected function notifyUploader(PurchaseImportBatch $batch): void
    {
        $user = $batch->create_user ? \App\Models\User::find($batch->create_user) : null;
        if ($user && $user->email) {
            $user->notify(new \App\Notifications\BatchCompletedNotification(
                'purchase', $batch->original_filename, $batch->total_items,
                $batch->processed_items, $batch->failed_items,
                route('dashboard.purchase.ai.review', ['batch_id' => $batch->id])
            ));
        }
    }
}
