<?php

namespace App\Services\Ai;

use Carbon\Carbon;

/**
 * توليد جدول دفعات الإيجار من بيانات العقد المستخرجة.
 * أولوية: تواريخ الاستحقاق المذكورة في العقد، وإلا تُحتسب شهرياً من تاريخ البداية.
 */
class PaymentScheduleService
{
    /**
     * @return array<int, array{rentpay_dt: string, rentpay_price: float}>
     */
    public function build(array $data): array
    {
        $rentValue = $this->num($data['rent_value'] ?? null);
        $count     = (int) ($data['payments_count'] ?? 0);
        $perPay    = $this->num($data['payment_amount'] ?? null);

        // مبلغ الدفعة: المذكور صراحة، أو قيمة الإيجار ÷ عدد الدفعات، أو قيمة الإيجار
        if ($perPay === null) {
            $perPay = ($rentValue !== null && $count > 0) ? round($rentValue / $count, 2) : $rentValue;
        }

        $dueDates = array_values(array_filter((array) ($data['due_dates'] ?? []), 'strlen'));

        // 1) تواريخ استحقاق مذكورة في العقد
        if (! empty($dueDates)) {
            $rows = [];
            foreach ($dueDates as $d) {
                $dt = $this->date($d);
                if ($dt) {
                    $rows[] = ['rentpay_dt' => $dt, 'rentpay_price' => (float) ($perPay ?? 0)];
                }
            }
            return $rows;
        }

        // 2) احتساب شهري من تاريخ البداية حسب عدد الدفعات
        $start = $this->date($data['start_date'] ?? null);
        if ($start && $count > 0) {
            $rows = [];
            $base = Carbon::parse($start);
            for ($i = 0; $i < $count; $i++) {
                $rows[] = [
                    'rentpay_dt'    => $base->copy()->addMonths($i)->format('Y-m-d'),
                    'rentpay_price' => (float) ($perPay ?? 0),
                ];
            }
            return $rows;
        }

        // 3) لا معلومات كافية — تُترك للإدخال اليدوي
        return [];
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
