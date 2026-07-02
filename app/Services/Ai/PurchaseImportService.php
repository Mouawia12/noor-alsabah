<?php

namespace App\Services\Ai;

use App\Jobs\ProcessPurchaseBatchJob;
use App\Models\AiAuditLog;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * تنسيق استيراد فواتير المشتريات: إنشاء الدفعة، واعتماد/رفض العناصر.
 * الحفظ النهائي في جدول purchase لا يتم إلا عند اعتماد المستخدم.
 */
class PurchaseImportService
{
    /**
     * إنشاء دفعة استيراد من ملف مرفوع (مخزَّن مسبقاً على قرص ai_private)، وجدولة المعالجة.
     */
    public function createBatch(string $diskPath, string $originalName, ?int $userId = null): PurchaseImportBatch
    {
        $abs = Storage::disk(config('ai.disk'))->path($diskPath);
        $hash = is_file($abs) ? hash_file('sha256', $abs) : hash('sha256', $diskPath);

        $batch = PurchaseImportBatch::create([
            'original_filename' => $originalName,
            'file_path'         => $diskPath,
            'file_hash'         => $hash,
            'status'            => PurchaseImportBatch::STATUS_PENDING,
            'engine'            => config('ai.engine'),
            'create_user'       => $userId,
        ]);

        AiAuditLog::record('purchase_batch', $batch->id, 'created', ['file' => $originalName], $userId);

        ProcessPurchaseBatchJob::dispatch($batch->id);

        return $batch;
    }

    /** هل سبق رفع نفس الملف (بصمة مطابقة)؟ */
    public function findExistingByHash(string $absPath): ?PurchaseImportBatch
    {
        if (! is_file($absPath)) {
            return null;
        }
        return PurchaseImportBatch::where('file_hash', hash_file('sha256', $absPath))->latest()->first();
    }

    /**
     * اعتماد عنصر مستخرَج وإنشاء سجل مشتريات فعلي.
     *
     * @param array $overrides تعديلات المستخدم على الحقول + supplier_id/new_supplier_name اختيارياً.
     */
    public function approveItem(PurchaseImportItem $item, array $overrides = [], ?int $userId = null): int
    {
        // حارس ضد الاعتماد المزدوج (نقر مزدوج/إعادة إرسال) → لا تُنشأ مشترياتان
        $item->refresh();
        if ($item->status === PurchaseImportItem::STATUS_APPROVED || $item->purchase_id) {
            return (int) $item->purchase_id;
        }

        $origExtracted = (array) ($item->extracted_json['data'] ?? $item->extracted_json ?? []);
        $data = array_merge($origExtracted, $overrides);

        // منع تكرار الفاتورة عند الحفظ: رقم فاتورة موجود مسبقاً في المشتريات → رفض صريح
        $invoiceNo = trim((string) ($data['invoice_no'] ?? ''));
        if ($invoiceNo !== '' && DB::table('purchase')->where('purchase_no', $invoiceNo)->exists()) {
            throw new DuplicateInvoiceException(
                "فاتورة مكررة: رقم الفاتورة «{$invoiceNo}» مسجَّل مسبقاً — لا يمكن حفظها مرتين."
            );
        }

        // التعلّم المستمر: سجّل أي تصحيحات قام بها المستخدم
        app(CorrectionService::class)->record('purchase', $origExtracted, $data, $data['tax_number'] ?? null, $userId);

        // تحديد المورد: معرّف صريح، أو إنشاء جديد عند الطلب
        $supplierId = $overrides['supplier_id'] ?? null;
        if (! $supplierId && ! empty($overrides['new_supplier_name'])) {
            $supplierId = app(SupplierMatchingService::class)
                ->create($overrides['new_supplier_name'], $data['tax_number'] ?? null, $userId)
                ->supplier_id;
        }

        // الفرع/المحل الذي تُرحَّل إليه الفاتورة (اختيار المستخدم من قائمة المحلات)
        $shopId = isset($overrides['shop_id']) && $overrides['shop_id'] !== '' ? (int) $overrides['shop_id'] : null;

        $purchaseId = DB::table('purchase')->insertGetId([
            'purchase_no'       => $data['invoice_no'] ?? null,
            'purchase_dt'       => $this->date($data['invoice_date'] ?? null),
            'tax_number'        => $data['tax_number'] ?? null,
            'currency'          => $data['currency'] ?? null,
            'amount_before_tax' => $this->num($data['amount_before_tax'] ?? null),
            'tax_amount'        => $this->num($data['tax_amount'] ?? null),
            'purchase_price'    => $this->num($data['total'] ?? null),
            'supplier_id'       => $supplierId,
            'shop_id'           => $shopId,
            'purchasefile'      => $item->batch->file_path ?? null,
            'note'              => $data['note'] ?? null,
            'import_item_id'    => $item->id,
            'created_at'        => Carbon::now(),
            'create_user'       => $userId,
        ]);

        $item->update([
            'status'      => PurchaseImportItem::STATUS_APPROVED,
            'purchase_id' => $purchaseId,
            'reviewed_by' => $userId,
            'reviewed_at' => Carbon::now(),
        ]);

        AiAuditLog::record('purchase_item', $item->id, 'approved', [
            'purchase_id' => $purchaseId,
            'overrides'   => $overrides,
        ], $userId);

        return $purchaseId;
    }

    public function rejectItem(PurchaseImportItem $item, ?string $reason = null, ?int $userId = null): void
    {
        $item->update([
            'status'       => PurchaseImportItem::STATUS_REJECTED,
            'error_reason' => $reason,
            'reviewed_by'  => $userId,
            'reviewed_at'  => Carbon::now(),
        ]);

        AiAuditLog::record('purchase_item', $item->id, 'rejected', ['reason' => $reason], $userId);
    }

    protected function num($v): ?float
    {
        return is_numeric($v) ? (float) $v : null;
    }

    protected function date($v): ?string
    {
        return DateNormalizer::toYmd($v);
    }
}
