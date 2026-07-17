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

        // التجزئة (تحويل لصور + إنشاء عناصر) موحّدة في الخدمة ليعيد استخدامها المسار اللحظي أيضاً.
        // ثم نُجدول معالجة كل عنصر (يُستخدم في الاختبارات/التوافق؛ الرفع الفعلي صار لحظياً).
        foreach (app(\App\Services\Ai\PurchaseImportService::class)->prepareItems($batch) as $itemId) {
            ProcessPurchaseItemJob::dispatch($itemId);
        }
    }
}
