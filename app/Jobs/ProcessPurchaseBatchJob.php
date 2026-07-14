<?php

namespace App\Jobs;

use App\Models\AiAuditLog;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Services\Ai\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

/**
 * معالجة دفعة فواتير: تحويل الصفحات لصور، تجزئتها إلى فواتير بكشف حدود ذكي،
 * وإنشاء عناصر استيراد وجدولة معالجة كل عنصر.
 */
class ProcessPurchaseBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800; // 30 دقيقة للملفات الكبيرة
    public int $tries = 1;      // المعالجة idempotent على مستوى العنصر؛ نتجنب إعادة التجزئة

    public function __construct(public int $batchId) {}

    public function handle(PdfService $pdf): void
    {
        $batch = PurchaseImportBatch::find($this->batchId);
        if (! $batch) {
            return;
        }

        $batch->update(['status' => PurchaseImportBatch::STATUS_PROCESSING]);

        try {
            $disk = config('ai.disk');
            $absPdf = Storage::disk($disk)->path($batch->file_path);
            $workDir = Storage::disk($disk)->path('purchase/work/' . $batch->id);

            // حدّ أقصى لعدد الصفحات/الفواتير — حماية من ملف ضخم يُرهق الخادم.
            // نتحقّق قبل التحويل عند الإمكان (PDF)، ثم بعده كضمان إضافي.
            $max = (int) config('ai.max_pages_per_batch', 200);
            if ($max > 0) {
                try {
                    $pages = $pdf->pageCount($absPdf);
                } catch (\Throwable $e) {
                    $pages = 0; // صورة مفردة أو تعذّر العدّ — نتحقّق بعد التحويل
                }
                if ($pages > $max) {
                    throw new \RuntimeException("الملف يتجاوز الحد الأقصى ({$max} صفحة/فاتورة). يرجى تقسيمه إلى ملفات أصغر.");
                }
            }

            $images = $pdf->rasterizeAll($absPdf, $workDir);
            if (empty($images)) {
                throw new \RuntimeException('لم تُنتج أي صور من الملف.');
            }
            if ($max > 0 && count($images) > $max) {
                throw new \RuntimeException("الملف يتجاوز الحد الأقصى ({$max} صفحة/فاتورة). يرجى تقسيمه إلى ملفات أصغر.");
            }

            // نضبط العدد الكلي قبل جدولة العناصر: يمنع سباقاً قد يجعل عامل الطابور
            // يعالج عنصراً ويحدّث العدّادات بينما total_items = 0 (فلا تُغلق الدفعة).
            $batch->update(['total_items' => count($images)]);
            AiAuditLog::record('purchase_batch', $batch->id, 'pages', ['pages' => count($images)], $batch->create_user);

            // عنصر لكل صفحة (الدمج الذكي يتم بعد الاستخراج) — قوي وقابل للتوسّع
            foreach ($images as $i => $path) {
                $item = PurchaseImportItem::create([
                    'batch_id'         => $batch->id,
                    'page_from'        => $i + 1,
                    'page_to'          => $i + 1,
                    'source_file_path' => $path,
                    'page_hash'        => is_file($path) ? hash_file('sha256', $path) : $path,
                    'status'           => PurchaseImportItem::STATUS_PENDING,
                ]);
                // مهمة واحدة للعنصر المُنشأ للتوّ. (كان ->each() يُمرَّر إلى Eloquent Builder
                // فيمرّ على كل عناصر الجدول ويُرسل مهمة لكلٍّ منها × عدد الصفحات = تكرار هائل.)
                ProcessPurchaseItemJob::dispatch($item->id);
            }
        } catch (\Throwable $e) {
            $batch->update([
                'status'       => PurchaseImportBatch::STATUS_FAILED,
                'error_reason' => $e->getMessage(),
            ]);
            AiAuditLog::record('purchase_batch', $batch->id, 'failed', ['error' => $e->getMessage()], $batch->create_user);
            throw $e;
        }
    }
}
