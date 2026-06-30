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
use Illuminate\Support\Facades\Log;

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

            // 5) قرار القبول/الرفض بأسباب واضحة للمستخدم
            $reasons = $this->rejectionReasons($data, $result->confidence);
            $accepted = empty($reasons);

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
                'status'                   => $accepted ? PurchaseImportItem::STATUS_NEEDS_REVIEW : PurchaseImportItem::STATUS_FAILED,
                // الفواتير المرفوضة: سبب الرفض. المقبولة: ملاحظات تحقق إن وُجدت.
                'error_reason'             => $accepted
                    ? ($validation['issues'] ? implode(' | ', $validation['issues']) : null)
                    : implode(' | ', $reasons),
            ]);

            AiAuditLog::record('purchase_item', $item->id, $accepted ? 'extracted' : 'rejected', [
                'confidence' => $result->confidence,
                'model'      => $result->model,
                'reasons'    => $reasons,
            ], $item->batch->create_user ?? null);

            $this->bumpBatch($item->batch_id);
        } catch (\Throwable $e) {
            // رسالة ودّية للمستخدم بدل الخطأ التقني الخام
            $item->update([
                'status'       => PurchaseImportItem::STATUS_FAILED,
                'error_reason' => 'تعذّرت قراءة الفاتورة (قد تكون الصورة غير واضحة أو الملف تالفاً)',
            ]);
            AiAuditLog::record('purchase_item', $item->id, 'failed', ['error' => $e->getMessage()], $item->batch->create_user ?? null);
            $this->bumpBatch($item->batch_id);
            throw $e;
        }
    }

    /**
     * يعيد حساب عدّادات الدفعة من قاعدة البيانات (مقاوم لإعادة المحاولة)،
     * ويُغلق الدفعة عند انتهاء كل العناصر (لا pending/processing).
     */
    protected function bumpBatch(int $batchId): void
    {
        $batch = PurchaseImportBatch::find($batchId);
        if (! $batch) {
            return;
        }

        $c = PurchaseImportItem::where('batch_id', $batchId)
            ->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $failed  = (int) ($c['failed'] ?? 0);
        $pending = (int) ($c['pending'] ?? 0) + (int) ($c['processing'] ?? 0);
        $processed = max(0, (int) $batch->total_items - $failed - $pending);

        $wasCompleted = $batch->status === PurchaseImportBatch::STATUS_COMPLETED;
        $batch->update(['processed_items' => $processed, 'failed_items' => $failed]);

        if ($pending === 0 && $batch->total_items > 0 && ! $wasCompleted) {
            $batch->update(['status' => PurchaseImportBatch::STATUS_COMPLETED]);
            AiAuditLog::record('purchase_batch', $batch->id, 'completed', [
                'accepted' => $processed,
                'rejected' => $failed,
            ], $batch->create_user);
            $this->notifyUploader($batch);
        }
    }

    /**
     * أسباب رفض الفاتورة (قائمة فارغة = مقبولة). تُعرض للمستخدم في شاشة المرفوضة.
     */
    protected function rejectionReasons(array $data, ?float $confidence): array
    {
        $hasInvoiceNo = ! empty($data['invoice_no']);
        $hasTotal = is_numeric($data['total'] ?? null);

        if (! $hasInvoiceNo && ! $hasTotal) {
            return ['الملف لا يحتوي فاتورة واضحة (تعذّر قراءة رقم الفاتورة والإجمالي) — قد تكون الصورة غير واضحة'];
        }

        $reasons = [];
        if (! $hasInvoiceNo) {
            $reasons[] = 'رقم الفاتورة غير مقروء أو غير موجود';
        }
        if (! $hasTotal) {
            $reasons[] = 'إجمالي الفاتورة غير مقروء';
        }
        if ($confidence !== null && $confidence < 0.35) {
            $reasons[] = 'الصورة غير واضحة (ثقة الاستخراج منخفضة جداً)';
        }
        return $reasons;
    }

    /** إشعار من رفع الدفعة باكتمالها (إن كان له بريد). */
    protected function notifyUploader(PurchaseImportBatch $batch): void
    {
        $user = $batch->create_user ? \App\Models\User::find($batch->create_user) : null;
        if ($user && $user->email) {
            // إشعار الإكمال غير حرج: فشله (مثلاً SMTP محظور) يجب ألا يُفشل معالجة العنصر
            try {
                $user->notify(new \App\Notifications\BatchCompletedNotification(
                    'purchase', $batch->original_filename, $batch->total_items,
                    $batch->processed_items, $batch->failed_items,
                    route('dashboard.purchase.ai.review', ['batch_id' => $batch->id])
                ));
            } catch (\Throwable $e) {
                Log::warning('BatchCompletedNotification (purchase) failed: ' . $e->getMessage());
            }
        }
    }
}
