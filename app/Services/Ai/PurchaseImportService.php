<?php

namespace App\Services\Ai;

use App\Models\AiAuditLog;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // لا جدولة خلفية: المعالجة تتم لحظياً يقودها المتصفّح عبر endpoint المعالجة (step).
        return $batch;
    }

    /**
     * تجهيز عناصر الدفعة لحظياً: تحويل الملف لصور، فحص الحد الأقصى، وإنشاء عنصر «قيد الانتظار»
     * لكل صفحة — دون جدولة أي مهمة خلفية. تُعيد معرّفات العناصر المُنشأة لمعالجتها تباعاً.
     * تُستدعى مرّة واحدة لكل دفعة (الاستدعاء المتكرر بلا صور جديدة يُعيد قائمة فارغة).
     *
     * @return int[] معرّفات العناصر المُنشأة
     */
    public function prepareItems(PurchaseImportBatch $batch): array
    {
        // لا تُعِد التجزئة إن سبق إنشاء عناصر لهذه الدفعة (حماية من نداء step مكرّر).
        if ($batch->items()->exists()) {
            return [];
        }

        $batch->update(['status' => PurchaseImportBatch::STATUS_PROCESSING, 'error_reason' => null]);

        try {
            $pdf = app(PdfService::class);
            $disk = config('ai.disk');
            $absPdf = Storage::disk($disk)->path($batch->file_path);
            $workDir = Storage::disk($disk)->path('purchase/work/' . $batch->id);

            // حدّ أقصى لعدد الصفحات/الفواتير — حماية من ملف ضخم يُرهق الخادم.
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

            $batch->update(['total_items' => count($images)]);
            AiAuditLog::record('purchase_batch', $batch->id, 'pages', ['pages' => count($images)], $batch->create_user);

            $ids = [];
            foreach ($images as $i => $path) {
                $item = PurchaseImportItem::create([
                    'batch_id'         => $batch->id,
                    'page_from'        => $i + 1,
                    'page_to'          => $i + 1,
                    'source_file_path' => $path,
                    'page_hash'        => is_file($path) ? hash_file('sha256', $path) : $path,
                    'status'           => PurchaseImportItem::STATUS_PENDING,
                ]);
                $ids[] = $item->id;
            }

            return $ids;
        } catch (\Throwable $e) {
            $batch->update([
                'status'       => PurchaseImportBatch::STATUS_FAILED,
                'error_reason' => $e->getMessage(),
            ]);
            AiAuditLog::record('purchase_batch', $batch->id, 'failed', ['error' => $e->getMessage()], $batch->create_user);
            throw $e;
        }
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

        // تحديد المورد: معرّف صريح، أو إنشاء جديد عند الطلب.
        // (يسبق فحص التكرار لأن الفحص يعتمد على المورّد لا على رقم الفاتورة وحده.)
        $supplierId = $overrides['supplier_id'] ?? null;
        if (! $supplierId && ! empty($overrides['new_supplier_name'])) {
            $supplierId = app(SupplierMatchingService::class)
                ->create($overrides['new_supplier_name'], $data['tax_number'] ?? null, $userId)
                ->supplier_id;
        }
        $supplierId = ($supplierId !== null && $supplierId !== '') ? (int) $supplierId : null;

        // اسم المورد المعروض في شاشة مصاريف شراء المحلات (عمود purchase_respon)
        // نأخذ الاسم كما استُخرج/عُدِّل ليظهر بعد الترحيل (كان يبقى فارغاً سابقاً)
        $supplierName = $overrides['new_supplier_name'] ?? ($data['supplier_name'] ?? null);

        // الفرع/المحل الذي تُرحَّل إليه الفاتورة (اختيار المستخدم من قائمة المحلات)
        $shopId = isset($overrides['shop_id']) && $overrides['shop_id'] !== '' ? (int) $overrides['shop_id'] : null;

        // منع تكرار الفاتورة عند الحفظ — فحص مركّب دقيق:
        // تُعتبر الفاتورة مكرّرة فقط عند تطابق (رقم الفاتورة + المورّد)، ويُضاف الفرع كقيد
        // إضافي إذا كان محدَّداً. إن غاب رقم الفاتورة أو المورّد → لا يُجرى الفحص إطلاقاً
        // (لا يُعامَل نقص الحقول تطابقاً، ولا يُعتمد رقم الفاتورة وحده كما كان يحدث سابقاً).
        $invoiceNo = trim((string) ($data['invoice_no'] ?? ''));
        if ($invoiceNo !== '' && $supplierId !== null) {
            $dupQuery = DB::table('purchase')
                ->where('purchase_no', $invoiceNo)
                ->where('supplier_id', $supplierId);
            if ($shopId !== null) {
                $dupQuery->where('shop_id', $shopId);
            }
            $existing = $dupQuery->first(['purchase_id']);
            if ($existing) {
                $branchPart = $shopId !== null ? ' بنفس الفرع' : '';
                throw new DuplicateInvoiceException(
                    "فاتورة مكررة: الفاتورة رقم «{$invoiceNo}» لنفس المورّد{$branchPart} مسجَّلة مسبقاً "
                    . "(سجل المشتريات رقم {$existing->purchase_id}) — لا يمكن حفظها مرتين."
                );
            }
        }

        // التعلّم المستمر (best-effort): تسجيل تصحيحات المستخدم يجب ألا يُفشل الترحيل إن تعذّر (مثل عطل الكاش).
        try {
            app(CorrectionService::class)->record('purchase', $origExtracted, $data, $data['tax_number'] ?? null, $userId);
        } catch (\Throwable $e) {
            Log::warning('فشل تسجيل تصحيح التعلّم المستمر عند الترحيل: ' . $e->getMessage());
        }

        // الحفظ ذرّياً: إدراج سجل المشتريات + تحديث حالة العنصر + سجل التدقيق ضمن معاملة واحدة.
        $purchaseId = DB::transaction(function () use ($item, $data, $supplierId, $supplierName, $shopId, $userId, $overrides) {
            $purchaseId = DB::table('purchase')->insertGetId([
                'purchase_no'       => $data['invoice_no'] ?? null,
                'purchase_dt'       => $this->date($data['invoice_date'] ?? null),
                'tax_number'        => $data['tax_number'] ?? null,
                'currency'          => $data['currency'] ?? null,
                'amount_before_tax' => $this->num($data['amount_before_tax'] ?? null),
                'tax_amount'        => $this->num($data['tax_amount'] ?? null),
                'purchase_price'    => $this->num($data['total'] ?? null),
                'supplier_id'       => $supplierId,
                'purchase_respon'   => $supplierName,
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
        });

        // تحديث مؤشّرات اللوحة (best-effort): فشل الكاش لا يُبطل ترحيلاً نجح فعلاً.
        try {
            \App\Support\AiDashboardStats::forget();
        } catch (\Throwable $e) {
            Log::warning('فشل تحديث مؤشّرات لوحة الذكاء الاصطناعي بعد الترحيل: ' . $e->getMessage());
        }

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

        try {
            \App\Support\AiDashboardStats::forget();
        } catch (\Throwable $e) {
            Log::warning('فشل تحديث مؤشّرات لوحة الذكاء الاصطناعي بعد الرفض: ' . $e->getMessage());
        }
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
