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

        $dueDates = array_values(array_filter(
            array_map(fn ($d) => $this->date($d), (array) ($data['due_dates'] ?? [])),
            fn ($d) => $d !== null
        ));

        // 1) تواريخ استحقاق مذكورة في العقد
        if (! empty($dueDates)) {
            $amounts = $this->amounts($perPay, $rentValue, count($dueDates));
            $rows = [];
            foreach ($dueDates as $i => $dt) {
                $rows[] = ['rentpay_dt' => $dt, 'rentpay_price' => $amounts[$i]];
            }
            return $rows;
        }

        // 2) احتساب شهري من تاريخ البداية حسب عدد الدفعات
        $start = $this->date($data['start_date'] ?? null);
        if ($start && $count > 0) {
            $amounts = $this->amounts($perPay, $rentValue, $count);
            $base = Carbon::parse($start);
            $rows = [];
            for ($i = 0; $i < $count; $i++) {
                $rows[] = [
                    // addMonthsNoOverflow: بداية 31 يناير لا تتجاوز إلى مارس
                    'rentpay_dt'    => $base->copy()->addMonthsNoOverflow($i)->format('Y-m-d'),
                    'rentpay_price' => $amounts[$i],
                ];
            }
            return $rows;
        }

        // 3) لا معلومات كافية — تُترك للإدخال اليدوي
        return [];
    }

    /**
     * توزيع المبالغ على الدفعات مع تسوية باقي التقريب على آخر دفعة
     * (مجموع الدفعات يساوي قيمة العقد بالضبط).
     *
     * @return float[]
     */
    protected function amounts(?float $perPay, ?float $rentValue, int $n): array
    {
        if ($n <= 0) {
            return [];
        }

        // مبلغ صريح لكل دفعة → كل الدفعات متساوية
        if ($perPay !== null) {
            return array_fill(0, $n, round($perPay, 2));
        }

        // لا إجمالي معروف → أصفار (تُملأ يدوياً)
        if ($rentValue === null) {
            return array_fill(0, $n, 0.0);
        }

        $each = round($rentValue / $n, 2);
        $amounts = array_fill(0, $n, $each);
        // آخر دفعة تمتص فرق التقريب حتى يطابق المجموع قيمة العقد
        $amounts[$n - 1] = round($rentValue - $each * ($n - 1), 2);

        return $amounts;
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
