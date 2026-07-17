<?php

namespace App\Services\Ai;

use App\Models\AiAuditLog;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * تنسيق استيراد عقود الإيجار: إنشاء الدفعة، واعتماد/رفض العناصر.
 * الاعتماد ينشئ عقداً في shop_rent ويولّد دفعاته في shop_rentpay.
 */
class RentImportService
{
    public function __construct(protected PaymentScheduleService $schedule) {}

    public function createBatch(string $diskPath, string $originalName, ?int $userId = null): RentContractImportBatch
    {
        $abs = Storage::disk(config('ai.disk'))->path($diskPath);
        $hash = is_file($abs) ? hash_file('sha256', $abs) : hash('sha256', $diskPath);

        $batch = RentContractImportBatch::create([
            'original_filename' => $originalName,
            'file_path'         => $diskPath,
            'file_hash'         => $hash,
            'status'            => RentContractImportBatch::STATUS_PENDING,
            'engine'            => config('ai.engine'),
            'create_user'       => $userId,
        ]);

        AiAuditLog::record('rent_batch', $batch->id, 'created', ['file' => $originalName], $userId);

        // لا جدولة خلفية: المعالجة لحظية يقودها المتصفّح عبر endpoint المعالجة (step).
        return $batch;
    }

    /**
     * تجهيز عناصر دفعة العقود لحظياً: تحويل لصور، فحص الحد الأقصى، وإنشاء عنصر «قيد الانتظار»
     * لكل صفحة — دون جدولة خلفية. تُعيد معرّفات العناصر لمعالجتها تباعاً. تُستدعى مرّة لكل دفعة.
     *
     * @return int[]
     */
    public function prepareItems(RentContractImportBatch $batch): array
    {
        if ($batch->items()->exists()) {
            return [];
        }

        $batch->update(['status' => RentContractImportBatch::STATUS_PROCESSING, 'error_reason' => null]);

        try {
            $pdf = app(PdfService::class);
            $disk = config('ai.disk');
            $absPdf = Storage::disk($disk)->path($batch->file_path);
            $workDir = Storage::disk($disk)->path('rent/work/' . $batch->id);

            $max = (int) config('ai.max_pages_per_batch', 200);
            if ($max > 0) {
                try {
                    $pages = $pdf->pageCount($absPdf);
                } catch (\Throwable $e) {
                    $pages = 0;
                }
                if ($pages > $max) {
                    throw new \RuntimeException("الملف يتجاوز الحد الأقصى ({$max} صفحة/عقد). يرجى تقسيمه إلى ملفات أصغر.");
                }
            }

            $images = $pdf->rasterizeAll($absPdf, $workDir);
            if (empty($images)) {
                throw new \RuntimeException('لم تُنتج أي صور من الملف.');
            }
            if ($max > 0 && count($images) > $max) {
                throw new \RuntimeException("الملف يتجاوز الحد الأقصى ({$max} صفحة/عقد). يرجى تقسيمه إلى ملفات أصغر.");
            }

            $batch->update(['total_items' => count($images)]);
            AiAuditLog::record('rent_batch', $batch->id, 'pages', ['pages' => count($images)], $batch->create_user);

            $ids = [];
            foreach ($images as $i => $path) {
                $item = RentContractImportItem::create([
                    'batch_id'         => $batch->id,
                    'page_from'        => $i + 1,
                    'page_to'          => $i + 1,
                    'source_file_path' => $path,
                    'page_hash'        => is_file($path) ? hash_file('sha256', $path) : $path,
                    'status'           => RentContractImportItem::STATUS_PENDING,
                ]);
                $ids[] = $item->id;
            }

            return $ids;
        } catch (\Throwable $e) {
            $batch->update(['status' => RentContractImportBatch::STATUS_FAILED, 'error_reason' => $e->getMessage()]);
            AiAuditLog::record('rent_batch', $batch->id, 'failed', ['error' => $e->getMessage()], $batch->create_user);
            throw $e;
        }
    }

    public function findExistingByHash(string $absPath): ?RentContractImportBatch
    {
        if (! is_file($absPath)) {
            return null;
        }
        return RentContractImportBatch::where('file_hash', hash_file('sha256', $absPath))->latest()->first();
    }

    /**
     * اعتماد عقد مستخرَج: إنشاء سجل shop_rent + توليد دفعات shop_rentpay.
     *
     * @param array $overrides يجب أن يحتوي shop_id، مع تعديلات الحقول اختيارياً.
     */
    public function approveItem(RentContractImportItem $item, array $overrides = [], ?int $userId = null): int
    {
        // حارس ضد الاعتماد المزدوج → لا يُنشأ عقدان ولا جدولا دفعات
        $item->refresh();
        if ($item->status === RentContractImportItem::STATUS_APPROVED || $item->shop_rent_id) {
            return (int) $item->shop_rent_id;
        }

        $origExtracted = (array) ($item->extracted_json['data'] ?? $item->extracted_json ?? []);
        $data = array_merge($origExtracted, $overrides);
        $shopId = $overrides['shop_id'] ?? $item->shop_id;

        // التعلّم المستمر: سجّل تصحيحات المستخدم
        app(CorrectionService::class)->record('rent', $origExtracted, $data, $data['contract_no'] ?? null, $userId);

        $result = DB::transaction(function () use ($item, $data, $shopId, $overrides, $userId) {
            // 1) إنشاء العقد (تعبئة الأعمدة القديمة ليعرضها النظام الحالي + الجديدة)
            $shopRentId = DB::table('shop_rent')->insertGetId([
                'shop_id'           => $shopId,
                'rent_no'           => $data['contract_no'] ?? null,
                'rent_name'         => $data['landlord'] ?? null,
                'rent_sdt'          => $this->date($data['start_date'] ?? null),
                'rent_edt'          => $this->date($data['end_date'] ?? null),
                'rent_note'         => $data['note'] ?? null,
                'contract_no'       => $data['contract_no'] ?? null,
                'start_date'        => $this->date($data['start_date'] ?? null),
                'end_date'          => $this->date($data['end_date'] ?? null),
                'landlord'          => $data['landlord'] ?? null,
                'tenant'            => $data['tenant'] ?? null,
                'property_info'     => $data['property_info'] ?? null,
                'rent_value'        => $this->num($data['rent_value'] ?? null),
                'payments_count'    => isset($data['payments_count']) ? (int) $data['payments_count'] : null,
                'renewal_terms'     => $data['renewal_terms'] ?? null,
                'termination_terms' => $data['termination_terms'] ?? null,
                'import_item_id'    => $item->id,
                // ملف العقد الأصلي (PDF) ليظهر «تحميل العقد» في صفحة المحل
                'ai_contract_file'  => $item->batch->file_path ?? null,
                'create_user'       => $userId,
                'created_at'        => Carbon::now(),
            ]);

            // 2) توليد الدفعات
            $rows = $this->schedule->build($data);
            $paymentsCreated = 0;
            foreach ($rows as $r) {
                DB::table('shop_rentpay')->insert([
                    'shop_id'       => $shopId,
                    'rentpay_dt'    => $r['rentpay_dt'],
                    'rentpay_price' => $r['rentpay_price'],
                    'rentpay_note'  => 'مولّدة آلياً من العقد المستورد',
                    'create_user'   => $userId,
                    'created_at'    => Carbon::now(),
                ]);
                $paymentsCreated++;
            }

            $item->update([
                'status'       => RentContractImportItem::STATUS_APPROVED,
                'shop_rent_id' => $shopRentId,
                'shop_id'      => $shopId,
                'reviewed_by'  => $userId,
                'reviewed_at'  => Carbon::now(),
            ]);

            AiAuditLog::record('rent_item', $item->id, 'approved', [
                'shop_rent_id'     => $shopRentId,
                'shop_id'          => $shopId,
                'payments_created' => $paymentsCreated,
                'overrides'        => $overrides,
            ], $userId);

            return $shopRentId;
        });

        \App\Support\AiDashboardStats::forget(); // تحديث فوري لمؤشّرات اللوحة

        return $result;
    }

    public function rejectItem(RentContractImportItem $item, ?string $reason = null, ?int $userId = null): void
    {
        $item->update([
            'status'       => RentContractImportItem::STATUS_REJECTED,
            'error_reason' => $reason,
            'reviewed_by'  => $userId,
            'reviewed_at'  => Carbon::now(),
        ]);
        AiAuditLog::record('rent_item', $item->id, 'rejected', ['reason' => $reason], $userId);
        \App\Support\AiDashboardStats::forget();
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
