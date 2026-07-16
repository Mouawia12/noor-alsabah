<?php

use App\Models\ShopRentpay;
use Carbon\Carbon;

/**
 * منطق حالة الدفعة: اشتقاق مسدَّد/جزئي/غير مسدَّد من المبلغ، و«المتأخّر» المشتقّ من التاريخ الميلادي.
 */

it('derives settlement status from amounts', function () {
    expect(ShopRentpay::deriveStatus(1000, 0))->toBe(ShopRentpay::STATUS_UNPAID);
    expect(ShopRentpay::deriveStatus(1000, 400))->toBe(ShopRentpay::STATUS_PARTIAL);
    expect(ShopRentpay::deriveStatus(1000, 1000))->toBe(ShopRentpay::STATUS_PAID);
    // سماحية تقريب عشري
    expect(ShopRentpay::deriveStatus(1000, 999.995))->toBe(ShopRentpay::STATUS_PAID);
});

it('computes remaining as due minus paid (never negative)', function () {
    $p = new ShopRentpay(['rentpay_price' => 1000, 'paid_amount' => 300]);
    expect($p->remaining)->toBe(700.0);

    $over = new ShopRentpay(['rentpay_price' => 1000, 'paid_amount' => 1200]);
    expect($over->remaining)->toBe(0.0);
});

it('marks an unpaid past-due installment as overdue (Gregorian)', function () {
    $asOf = Carbon::parse('2026-07-15');

    $overdue = new ShopRentpay(['status' => 'unpaid', 'rentpay_dt' => '2026-06-01']);
    expect($overdue->isOverdue($asOf))->toBeTrue();
    expect($overdue->displayStatus($asOf))->toBe(ShopRentpay::STATUS_OVERDUE);
    expect($overdue->displayStatusLabel($asOf))->toBe('متأخِّر');
});

it('does NOT mark a paid or future installment as overdue', function () {
    $asOf = Carbon::parse('2026-07-15');

    $paid = new ShopRentpay(['status' => 'paid', 'rentpay_dt' => '2026-06-01']);
    expect($paid->isOverdue($asOf))->toBeFalse();
    expect($paid->displayStatus($asOf))->toBe(ShopRentpay::STATUS_PAID);

    $future = new ShopRentpay(['status' => 'unpaid', 'rentpay_dt' => '2026-08-01']);
    expect($future->isOverdue($asOf))->toBeFalse();
    expect($future->displayStatus($asOf))->toBe(ShopRentpay::STATUS_UNPAID);
});
