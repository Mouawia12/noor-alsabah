<?php

use App\Services\Ai\PaymentScheduleService;

beforeEach(function () {
    $this->svc = new PaymentScheduleService();
});

it('uses explicit due dates and explicit payment amount', function () {
    $rows = $this->svc->build([
        'due_dates'      => ['2026-01-01', '2026-02-01', '2026-03-01'],
        'payment_amount' => 500,
    ]);

    expect($rows)->toHaveCount(3);
    expect($rows[0])->toBe(['rentpay_dt' => '2026-01-01', 'rentpay_price' => 500.0]);
    expect(array_column($rows, 'rentpay_price'))->toBe([500.0, 500.0, 500.0]);
});

it('generates a monthly schedule from start date and count', function () {
    $rows = $this->svc->build([
        'start_date'     => '2026-01-15',
        'payments_count' => 3,
        'payment_amount' => 1000,
    ]);

    expect(array_column($rows, 'rentpay_dt'))
        ->toBe(['2026-01-15', '2026-02-15', '2026-03-15']);
});

it('does not overflow month-end (Jan 31 must not jump to March)', function () {
    $rows = $this->svc->build([
        'start_date'     => '2026-01-31',
        'payments_count' => 3,
        'payment_amount' => 1000,
    ]);

    // addMonthsNoOverflow: فبراير = آخر يوم، لا 3 مارس
    expect(array_column($rows, 'rentpay_dt'))
        ->toBe(['2026-01-31', '2026-02-28', '2026-03-31']);
});

it('distributes rounding remainder so the sum equals the contract value', function () {
    $rows = $this->svc->build([
        'start_date'     => '2026-01-01',
        'payments_count' => 12,
        'rent_value'     => 1000, // 1000/12 = 83.33...
    ]);

    expect($rows)->toHaveCount(12);
    $total = array_sum(array_column($rows, 'rentpay_price'));
    expect(round($total, 2))->toBe(1000.0);
    // أول 11 دفعة متساوية والأخيرة تمتص الفرق
    expect($rows[0]['rentpay_price'])->toBe(83.33);
    expect($rows[11]['rentpay_price'])->toBe(round(1000 - 83.33 * 11, 2));
});

it('drops invalid due dates instead of inserting garbage', function () {
    $rows = $this->svc->build([
        'due_dates'      => ['2026-01-01', 'لا يوجد', '2026-03-01'],
        'payment_amount' => 100,
    ]);

    expect($rows)->toHaveCount(2);
    expect(array_column($rows, 'rentpay_dt'))->toBe(['2026-01-01', '2026-03-01']);
});

it('returns empty when there is not enough information', function () {
    expect($this->svc->build([]))->toBe([]);
    expect($this->svc->build(['payments_count' => 3]))->toBe([]); // لا تاريخ بداية
});
