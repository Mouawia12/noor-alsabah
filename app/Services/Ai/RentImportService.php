<?php

namespace App\Services\Ai;

use App\Jobs\ProcessRentContractBatchJob;
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
        ProcessRentContractBatchJob::dispatch($batch->id);

        return $batch;
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
        $origExtracted = (array) ($item->extracted_json['data'] ?? $item->extracted_json ?? []);
        $data = array_merge($origExtracted, $overrides);
        $shopId = $overrides['shop_id'] ?? $item->shop_id;

        // التعلّم المستمر: سجّل تصحيحات المستخدم
        app(CorrectionService::class)->record('rent', $origExtracted, $data, $data['contract_no'] ?? null, $userId);

        return DB::transaction(function () use ($item, $data, $shopId, $overrides, $userId) {
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
    }

    protected function num($v): ?float
    {
        return is_numeric($v) ? (float) $v : null;
    }

    protected function date($v): ?string
    {
        if (empty($v)) {
            return null;
        }
        $ts = strtotime((string) $v);
        return $ts ? date('Y-m-d', $ts) : null;
    }
}
