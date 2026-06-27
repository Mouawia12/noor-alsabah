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
            ->select('rp.rentpay_id', 'rp.shop_id', 'rp.rentpay_dt', 'rp.rentpay_price', 's.shop_name')
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
            ->select('rp.rentpay_id', 'rp.shop_id', 'rp.rentpay_dt', 'rp.rentpay_price', 's.shop_name')
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
