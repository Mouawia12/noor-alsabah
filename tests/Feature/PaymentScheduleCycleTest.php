<?php

use App\Services\Ai\PaymentScheduleService;

/**
 * توليد جدول السداد حسب دورة الدفع (شهري/ربعي/نصفي/سنوي) مع الحفاظ على السلوك الشهري الافتراضي.
 */
function schedule(array $data): array
{
    return app(PaymentScheduleService::class)->build($data);
}

it('generates monthly installments by default', function () {
    $rows = schedule(['start_date' => '2026-01-01', 'payments_count' => 4, 'payment_amount' => 1000]);

    expect(array_column($rows, 'rentpay_dt'))->toBe(['2026-01-01', '2026-02-01', '2026-03-01', '2026-04-01']);
});

it('generates quarterly installments (3-month step)', function () {
    $rows = schedule(['start_date' => '2026-01-01', 'payments_count' => 4, 'payment_amount' => 1000, 'payment_cycle' => 'quarterly']);

    expect(array_column($rows, 'rentpay_dt'))->toBe(['2026-01-01', '2026-04-01', '2026-07-01', '2026-10-01']);
});

it('generates semi-annual installments (6-month step)', function () {
    $rows = schedule(['start_date' => '2026-01-01', 'payments_count' => 2, 'payment_amount' => 5000, 'payment_cycle' => 'semi']);

    expect(array_column($rows, 'rentpay_dt'))->toBe(['2026-01-01', '2026-07-01']);
});

it('generates annual installments (12-month step)', function () {
    $rows = schedule(['start_date' => '2026-01-01', 'payments_count' => 2, 'payment_amount' => 12000, 'payment_cycle' => 'annual']);

    expect(array_column($rows, 'rentpay_dt'))->toBe(['2026-01-01', '2027-01-01']);
});

it('splits the total across installments with rounding on the last one', function () {
    $rows = schedule(['start_date' => '2026-01-01', 'payments_count' => 3, 'rent_value' => 1000, 'payment_cycle' => 'monthly']);

    $amounts = array_column($rows, 'rentpay_price');
    expect(array_sum($amounts))->toBe(1000.0);       // المجموع يطابق قيمة العقد بالضبط
    expect(count($amounts))->toBe(3);
});
