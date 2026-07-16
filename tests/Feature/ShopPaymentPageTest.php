<?php

use App\Models\ShopRentpay;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\Support\RentTestSchema;

/**
 * صفحة سداد المحل وتسجيل الدفعة عبر HTTP.
 */
beforeEach(function () {
    RentTestSchema::install();
    DB::table('shop')->insert(['shop_id' => 1, 'shop_name' => 'محل الاختبار']);
    DB::table('shop_rent')->insert([
        'shop_rent_id' => 10, 'shop_id' => 1, 'contract_no' => 'C-1', 'contract_type' => 'new',
        'tenant' => 'مستأجر', 'unit_no' => 'U1', 'payment_cycle' => 'monthly',
    ]);
    DB::table('shop_rentpay')->insert([
        'rentpay_id' => 100, 'shop_id' => 1, 'shop_rent_id' => 10, 'seq_no' => 1,
        'rentpay_dt' => '2026-07-01', 'rentpay_price' => 1000, 'paid_amount' => 0, 'status' => 'unpaid',
    ]);
});

it('renders the shop payment page with the contract and its payment', function () {
    // مدير (emp_job=1): يتخطّى استعلامات جداول الصلاحيات الموروثة في الشريط الجانبي عند عرض الصفحة كاملة
    $user = User::factory()->create(['emp_job' => 1]);

    $res = $this->actingAs($user)->get(route('dashboard.shop.payments', 1));

    $res->assertOk();
    $res->assertSee('C-1');
    $res->assertSee('متابعة سداد العقود');
});

it('records a payment through the HTTP endpoint and issues a receipt', function () {
    $user = User::factory()->create();

    $res = $this->actingAs($user)->postJson(route('dashboard.rent.pay.record', 100), ['method' => 'نقدي']);

    $res->assertOk()->assertJson(['ok' => true, 'status' => 'paid']);
    expect($res->json('receipt_no'))->toStartWith('RC-');
    expect(ShopRentpay::find(100)->status)->toBe(ShopRentpay::STATUS_PAID);
});

it('refuses to record on an already fully-paid installment', function () {
    $user = User::factory()->create();
    DB::table('shop_rentpay')->where('rentpay_id', 100)->update(['status' => 'paid', 'paid_amount' => 1000, 'is_paid' => true]);

    $this->actingAs($user)->postJson(route('dashboard.rent.pay.record', 100), [])
        ->assertStatus(422)->assertJson(['ok' => false]);
});

it('renders the financial report with summary tiles', function () {
    $user = User::factory()->create(['emp_job' => 1]);

    $res = $this->actingAs($user)->get(route('dashboard.shop.financial_report', 1));

    $res->assertOk();
    $res->assertSee('التقرير المالي');
    $res->assertSee('إجمالي المستحق');
});

it('redirects guests to login', function () {
    $this->get(route('dashboard.shop.payments', 1))->assertRedirect('/login');
});
