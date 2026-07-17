<?php

namespace App\Jobs;

use App\Models\AiAuditLog;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
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

    public function handle(PdfService $pdf): void
    {
        $batch = RentContractImportBatch::find($this->batchId);
        if (! $batch) {
            return;
        }

        // التجزئة موحّدة في الخدمة (يعيد استخدامها المسار اللحظي)، ثم نُجدول معالجة كل عنصر.
        foreach (app(\App\Services\Ai\RentImportService::class)->prepareItems($batch) as $itemId) {
            ProcessRentContractItemJob::dispatch($itemId);
        }
    }
}
