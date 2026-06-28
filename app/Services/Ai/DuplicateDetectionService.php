<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\DB;

/**
 * كشف الفواتير المكررة قبل الاعتماد.
 * يعتمد على رقم الفاتورة (الأقوى) ثم على توليفة (مورد+تاريخ+إجمالي).
 */
class DuplicateDetectionService
{
    /**
     * @return array{is_duplicate: bool, purchase_id: int|null, reason: string|null}
     */
    public function check(array $data): array
    {
        $invoiceNo = trim((string) ($data['invoice_no'] ?? ''));

        // 1) تطابق رقم الفاتورة (purchase.purchase_no فريد منطقياً)
        if ($invoiceNo !== '') {
            $row = DB::table('purchase')->where('purchase_no', $invoiceNo)->first(['purchase_id']);
            if ($row) {
                return ['is_duplicate' => true, 'purchase_id' => (int) $row->purchase_id, 'reason' => 'رقم فاتورة مطابق موجود مسبقاً'];
            }
        }

        // 2) توليفة: نفس الرقم الضريبي + التاريخ + الإجمالي
        $tax   = trim((string) ($data['tax_number'] ?? ''));
        $date  = $this->normDate($data['invoice_date'] ?? null);
        $total = is_numeric($data['total'] ?? null) ? (float) $data['total'] : null;

        if ($tax !== '' && $date !== null && $total !== null) {
            $row = DB::table('purchase')
                ->where('tax_number', $tax)
                ->whereDate('purchase_dt', $date)
                // سماحية صغيرة بدل المساواة التامة (تمثيل الأرقام العشرية غير دقيق)
                ->whereBetween('purchase_price', [$total - 0.01, $total + 0.01])
                ->first(['purchase_id']);
            if ($row) {
                return ['is_duplicate' => true, 'purchase_id' => (int) $row->purchase_id, 'reason' => 'توليفة (رقم ضريبي+تاريخ+إجمالي) مطابقة'];
            }
        }

        return ['is_duplicate' => false, 'purchase_id' => null, 'reason' => null];
    }

    protected function normDate($v): ?string
    {
        return DateNormalizer::toYmd($v);
    }
}
