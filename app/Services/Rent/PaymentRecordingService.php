<?php

namespace App\Services\Rent;

use App\Models\ShopFinancialLog;
use App\Models\ShopReceipt;
use App\Models\ShopRentpay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * تسجيل سداد دفعة إيجار كعملية ذرّية واحدة:
 *  1) تحديث المبلغ المدفوع وحالة الدفعة (مسدَّد/جزئي/غير مسدَّد) + مزامنة is_paid/paid_at القديمة.
 *  2) إنشاء سند قبض إلكتروني مرقّم ومرتبط بالمحل + العقد + الدفعة.
 *  3) قيد في السجل المالي للمحل.
 * لا يمسّ المخطط القديم إلا بالإضافة، ويُبقي is_paid متوافقاً مع الاستعلامات الحالية.
 */
class PaymentRecordingService
{
    /**
     * @param  int         $rentpayId  معرّف الدفعة (bند جدول السداد).
     * @param  float|null  $amount     المبلغ المقبوض (افتراضياً: كامل المتبقّي).
     * @param  array       $opts       method, note, paid_at (Y-m-d أو Carbon).
     * @return ShopReceipt سند القبض المُنشأ.
     */
    public function record(int $rentpayId, ?float $amount = null, array $opts = [], ?int $userId = null): ShopReceipt
    {
        return DB::transaction(function () use ($rentpayId, $amount, $opts, $userId) {
            /** @var ShopRentpay $pay */
            $pay = ShopRentpay::whereKey($rentpayId)->lockForUpdate()->firstOrFail();

            $due       = (float) $pay->rentpay_price;
            $paidSoFar = (float) $pay->paid_amount;
            // افتراضياً نقبض كامل المتبقّي؛ ولا نقبل مبلغاً سالباً
            $remaining = max(0.0, round($due - $paidSoFar, 2));
            $amount    = $amount === null ? $remaining : round(max(0.0, $amount), 2);

            $paidAt = isset($opts['paid_at']) && $opts['paid_at']
                ? Carbon::parse($opts['paid_at'])
                : Carbon::now();

            $newPaid   = round($paidSoFar + $amount, 2);
            $newStatus = ShopRentpay::deriveStatus($due, $newPaid);

            // 1) تحديث الدفعة (مع مزامنة الأعمدة القديمة is_paid/paid_at للحفاظ على التقارير الحالية)
            $pay->paid_amount = $newPaid;
            $pay->status      = $newStatus;
            $pay->is_paid     = $newStatus === ShopRentpay::STATUS_PAID;
            $pay->paid_at     = $newStatus === ShopRentpay::STATUS_PAID ? $paidAt : $pay->paid_at;
            $pay->update_user = $userId;
            $pay->save();

            // 2) سند القبض — رقم متسلسل مشتقّ من المفتاح الأساسي (فريد)
            $receipt = ShopReceipt::create([
                'shop_id'      => $pay->shop_id,
                'shop_rent_id' => $pay->shop_rent_id,
                'rentpay_id'   => $pay->rentpay_id,
                'amount'       => $amount,
                'method'       => $opts['method'] ?? null,
                'paid_at'      => $paidAt,
                'note'         => $opts['note'] ?? null,
                'create_user'  => $userId,
            ]);
            $receipt->receipt_no = 'RC-' . $paidAt->format('Y') . '-' . str_pad((string) $receipt->receipt_id, 6, '0', STR_PAD_LEFT);
            $receipt->save();

            // 3) قيد في السجل المالي للمحل (قبض = credit)
            ShopFinancialLog::create([
                'shop_id'      => $pay->shop_id,
                'shop_rent_id' => $pay->shop_rent_id,
                'rentpay_id'   => $pay->rentpay_id,
                'receipt_id'   => $receipt->receipt_id,
                'direction'    => ShopFinancialLog::DIR_CREDIT,
                'event'        => ShopFinancialLog::EVENT_PAYMENT,
                'amount'       => $amount,
                'description'  => 'سداد دفعة إيجار رقم ' . ($pay->seq_no ?? $pay->rentpay_id)
                    . ' — سند ' . $receipt->receipt_no,
                'create_user'  => $userId,
                'created_at'   => $paidAt,
            ]);

            return $receipt;
        });
    }

    /**
     * ملخّص السداد لعقد واحد: الإجمالي/المسدَّد/المتبقّي/المتأخّر (بالميلادي).
     *
     * @return array{total: float, paid: float, remaining: float, overdue: float, overdue_count: int}
     */
    public function contractSummary(int $shopRentId, ?Carbon $asOf = null): array
    {
        $asOf = $asOf ?? Carbon::today();
        $payments = ShopRentpay::where('shop_rent_id', $shopRentId)->get();

        $total = $paid = $overdue = 0.0;
        $overdueCount = 0;
        foreach ($payments as $p) {
            $total += (float) $p->rentpay_price;
            $paid  += (float) $p->paid_amount;
            if ($p->isOverdue($asOf)) {
                $overdue += $p->remaining;
                $overdueCount++;
            }
        }

        return [
            'total'         => round($total, 2),
            'paid'          => round($paid, 2),
            'remaining'     => round(max(0.0, $total - $paid), 2),
            'overdue'       => round($overdue, 2),
            'overdue_count' => $overdueCount,
        ];
    }
}
