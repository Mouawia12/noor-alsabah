<?php

use App\Models\ShopFinancialLog;
use App\Models\ShopReceipt;
use App\Models\ShopRentpay;
use App\Services\Rent\PaymentRecordingService;
use Illuminate\Support\Facades\DB;
use Tests\Support\RentTestSchema;

/**
 * تسجيل سداد الدفعة: تحديث الحالة/المبلغ، إصدار سند مرقّم، وقيد في السجل المالي — ذرّياً.
 */
beforeEach(function () {
    RentTestSchema::install();
    DB::table('shop')->insert(['shop_id' => 1, 'shop_name' => 'محل الاختبار']);
    DB::table('shop_rent')->insert([
        'shop_rent_id' => 10, 'shop_id' => 1, 'contract_no' => 'C-100', 'tenant' => 'مستأجر',
        'rent_value' => 12000, 'payments_count' => 12,
    ]);
});

function seedPayment(array $overrides = []): int
{
    return DB::table('shop_rentpay')->insertGetId(array_merge([
        'shop_id' => 1, 'shop_rent_id' => 10, 'seq_no' => 1,
        'rentpay_dt' => '2026-07-01', 'rentpay_price' => 1000, 'paid_amount' => 0, 'status' => 'unpaid',
    ], $overrides));
}

it('records a full payment: status paid, receipt issued, financial log created', function () {
    $id = seedPayment();

    $receipt = app(PaymentRecordingService::class)->record($id, null, ['method' => 'نقدي'], 7);

    $pay = ShopRentpay::find($id);
    expect($pay->status)->toBe(ShopRentpay::STATUS_PAID);
    expect($pay->paid_amount)->toBe(1000.0);
    expect((bool) $pay->is_paid)->toBeTrue();        // مزامنة العمود القديم
    expect($pay->paid_at)->not->toBeNull();

    // سند القبض
    expect($receipt)->toBeInstanceOf(ShopReceipt::class);
    expect($receipt->receipt_no)->toStartWith('RC-');
    expect($receipt->amount)->toBe(1000.0);
    expect((int) $receipt->shop_id)->toBe(1);
    expect((int) $receipt->shop_rent_id)->toBe(10);
    expect((int) $receipt->rentpay_id)->toBe($id);

    // قيد السجل المالي (قبض)
    $log = ShopFinancialLog::where('rentpay_id', $id)->first();
    expect($log)->not->toBeNull();
    expect($log->direction)->toBe(ShopFinancialLog::DIR_CREDIT);
    expect($log->amount)->toBe(1000.0);
});

it('records a partial payment then completes it', function () {
    $id = seedPayment();
    $svc = app(PaymentRecordingService::class);

    $svc->record($id, 300, [], 7);
    $pay = ShopRentpay::find($id);
    expect($pay->status)->toBe(ShopRentpay::STATUS_PARTIAL);
    expect($pay->paid_amount)->toBe(300.0);
    expect($pay->remaining)->toBe(700.0);
    expect((bool) $pay->is_paid)->toBeFalse();

    $svc->record($id, 700, [], 7);
    $pay->refresh();
    expect($pay->status)->toBe(ShopRentpay::STATUS_PAID);
    expect($pay->paid_amount)->toBe(1000.0);
    expect($pay->remaining)->toBe(0.0);

    // سندان لكل عملية قبض
    expect(ShopReceipt::where('rentpay_id', $id)->count())->toBe(2);
    expect(ShopFinancialLog::where('rentpay_id', $id)->count())->toBe(2);
});

it('issues sequential unique receipt numbers', function () {
    $a = seedPayment(['seq_no' => 1]);
    $b = seedPayment(['seq_no' => 2]);
    $svc = app(PaymentRecordingService::class);

    $r1 = $svc->record($a, null, [], 7);
    $r2 = $svc->record($b, null, [], 7);

    expect($r1->receipt_no)->not->toBe($r2->receipt_no);
    expect(ShopReceipt::whereIn('receipt_no', [$r1->receipt_no, $r2->receipt_no])->count())->toBe(2);
});

it('computes a correct contract-level summary including overdue', function () {
    seedPayment(['seq_no' => 1, 'rentpay_dt' => '2026-06-01', 'rentpay_price' => 1000, 'status' => 'unpaid']); // متأخّرة
    $paidId = seedPayment(['seq_no' => 2, 'rentpay_dt' => '2026-07-01', 'rentpay_price' => 1000]);
    seedPayment(['seq_no' => 3, 'rentpay_dt' => '2030-01-01', 'rentpay_price' => 1000, 'status' => 'unpaid']); // مستقبلية

    app(PaymentRecordingService::class)->record($paidId, null, [], 7); // سداد الثانية

    $summary = app(PaymentRecordingService::class)->contractSummary(10, Carbon\Carbon::parse('2026-07-15'));

    expect($summary['total'])->toBe(3000.0);
    expect($summary['paid'])->toBe(1000.0);
    expect($summary['remaining'])->toBe(2000.0);
    expect($summary['overdue'])->toBe(1000.0);       // فقط الدفعة المتأخرة غير المسدّدة
    expect($summary['overdue_count'])->toBe(1);
});
