<?php

namespace App\Services\Ai;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * تنبيهات الإيجارات: دفعات مستحقة قريباً، دفعات متأخرة، وعقود قاربت الانتهاء.
 */
class RentAlertService
{
    /** أعمدة مُثراة لكل دفعة: بيانات العقد + رقم الدفعة (3 من 12) + أيام التأخير. */
    protected function paymentSelect(): array
    {
        return [
            DB::raw('rp.rentpay_id'), DB::raw('rp.shop_id'), DB::raw('rp.rentpay_dt'),
            DB::raw('rp.rentpay_price'), DB::raw('s.shop_name'),
            // رقم الدفعة وإجماليها (ترتيب حسب التاريخ ضمن نفس المحل)
            DB::raw('(SELECT COUNT(*) FROM shop_rentpay r2 WHERE r2.shop_id = rp.shop_id AND r2.rentpay_dt <= rp.rentpay_dt) as pay_no'),
            DB::raw('(SELECT COUNT(*) FROM shop_rentpay r3 WHERE r3.shop_id = rp.shop_id) as pay_total'),
            // بيانات العقد (أحدث عقد للمحل)
            DB::raw('(SELECT contract_no FROM shop_rent sr WHERE sr.shop_id = rp.shop_id ORDER BY sr.shop_rent_id DESC LIMIT 1) as contract_no'),
            DB::raw('(SELECT landlord FROM shop_rent sr WHERE sr.shop_id = rp.shop_id ORDER BY sr.shop_rent_id DESC LIMIT 1) as landlord'),
            DB::raw('(SELECT tenant FROM shop_rent sr WHERE sr.shop_id = rp.shop_id ORDER BY sr.shop_rent_id DESC LIMIT 1) as tenant'),
            DB::raw('(SELECT property_info FROM shop_rent sr WHERE sr.shop_id = rp.shop_id ORDER BY sr.shop_rent_id DESC LIMIT 1) as property_info'),
            DB::raw('DATEDIFF(CURDATE(), rp.rentpay_dt) as days_overdue'),
        ];
    }

    /** دفعات مستحقة خلال X يوماً (غير مسدَّدة). */
    public function upcomingPayments(int $days = 10): Collection
    {
        $today = Carbon::today()->toDateString();
        $until = Carbon::today()->addDays($days)->toDateString();

        return DB::table('shop_rentpay as rp')
            ->leftJoin('shop as s', 's.shop_id', '=', 'rp.shop_id')
            ->where('rp.is_paid', 0)
            ->whereBetween('rp.rentpay_dt', [$today, $until])
            ->orderBy('rp.rentpay_dt')
            ->select($this->paymentSelect())
            ->get();
    }

    /** دفعات متأخرة (غير مسدَّدة، تاريخها مضى) — محدودة بـ180 يوماً لتقليل الضجيج. */
    public function overduePayments(): Collection
    {
        $today = Carbon::today()->toDateString();
        $since = Carbon::today()->subDays(180)->toDateString();

        return DB::table('shop_rentpay as rp')
            ->leftJoin('shop as s', 's.shop_id', '=', 'rp.shop_id')
            ->where('rp.is_paid', 0)
            ->where('rp.rentpay_dt', '<', $today)
            ->where('rp.rentpay_dt', '>=', $since)
            ->orderBy('rp.rentpay_dt')
            ->select($this->paymentSelect())
            ->get();
    }

    /** عقود تنتهي خلال X يوماً. */
    public function expiringContracts(int $days = 30): Collection
    {
        $today = Carbon::today()->toDateString();
        $until = Carbon::today()->addDays($days)->toDateString();

        return DB::table('shop_rent as r')
            ->leftJoin('shop as s', 's.shop_id', '=', 'r.shop_id')
            ->whereNotNull('r.end_date')
            ->whereBetween('r.end_date', [$today, $until])
            ->orderBy('r.end_date')
            ->select('r.shop_rent_id', 'r.shop_id', 'r.contract_no', 'r.end_date', 's.shop_name')
            ->get();
    }

    /** وصف نصّي مفصّل لدفعة (للإشعار والبريد) يتضمّن كل ما طلبه العميل. */
    public static function describePayment($p): string
    {
        $name = $p->shop_name ?: ('محل #' . ($p->shop_id ?? ''));
        $date = $p->rentpay_dt ? Carbon::parse($p->rentpay_dt)->format('Y-m-d') : '—';
        $days = (int) ($p->days_overdue ?? 0);
        $status = $days > 0 ? "متأخرة منذ {$days} يوم" : 'مستحقة قريباً';
        $payNo = (isset($p->pay_no, $p->pay_total) && $p->pay_total)
            ? "الدفعة {$p->pay_no} من {$p->pay_total}" : 'دفعة';

        $parts = [];
        if (! empty($p->contract_no)) $parts[] = "عقد {$p->contract_no}";
        $parts[] = "العقار: {$name}";
        if (! empty($p->landlord)) $parts[] = "المؤجر: {$p->landlord}";
        if (! empty($p->tenant)) $parts[] = "المستأجر: {$p->tenant}";
        $parts[] = $payNo;
        $parts[] = "المبلغ: {$p->rentpay_price}";
        $parts[] = "الاستحقاق: {$date}";
        $parts[] = $status;

        return implode(' | ', $parts);
    }

    /** ملخّص الأعداد. */
    public function summary(int $dueDays = 10, int $expiryDays = 30): array
    {
        return [
            'upcoming'  => $this->upcomingPayments($dueDays),
            'overdue'   => $this->overduePayments(),
            'expiring'  => $this->expiringContracts($expiryDays),
        ];
    }

    /** تعليم دفعة كمسدَّدة. */
    public function markPaid(int $rentpayId, ?int $userId = null): void
    {
        DB::table('shop_rentpay')->where('rentpay_id', $rentpayId)->update([
            'is_paid'    => 1,
            'paid_at'    => now(),
            'update_user' => $userId,
            'updated_at' => now(),
        ]);
    }
}
