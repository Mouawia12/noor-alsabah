<?php

use App\Models\RentPaymentMatchItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\Support\RentTestSchema;

/**
 * تصدير التقرير المالي للمحل + قائمة مطابقة الدفعات (PDF/Excel).
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
        'rentpay_dt' => '2026-07-01', 'rentpay_price' => 1000, 'paid_amount' => 400, 'status' => 'partial',
    ]);
});

it('exports the shop financial report as Excel', function () {
    $user = User::factory()->create(['emp_job' => 1]);

    $res = $this->actingAs($user)->get(route('dashboard.shop.financial_report.export', ['shop' => 1, 'format' => 'xlsx']));

    $res->assertOk();
    expect($res->headers->get('content-disposition'))->toContain('.xlsx');
});

it('exports the shop financial report as PDF (joined Arabic via TCPDF)', function () {
    $user = User::factory()->create(['emp_job' => 1]);

    $res = $this->actingAs($user)->get(route('dashboard.shop.financial_report.export', ['shop' => 1, 'format' => 'pdf']));

    $res->assertOk();
    expect($res->headers->get('content-type'))->toContain('pdf');
    // ملف PDF حقيقي (يبدأ بترويسة %PDF)
    expect(substr($res->getContent(), 0, 4))->toBe('%PDF');
});

it('exports the payment matching list as Excel', function () {
    $user = User::factory()->create(['emp_job' => 1]);
    RentPaymentMatchItem::create([
        'contract_no' => 'C-1', 'tenant_name' => 'مستأجر', 'amount' => 1000, 'due_date' => '2026-07-01',
        'match_status' => 'orphan', 'status' => 'needs_review', 'confidence' => 0.4, 'match_reason' => 'لا عقد مطابق',
    ]);

    $res = $this->actingAs($user)->get(route('dashboard.rent.payment_match.export', ['format' => 'xlsx']));

    $res->assertOk();
    expect($res->headers->get('content-disposition'))->toContain('.xlsx');
});

it('requires authentication to export the financial report', function () {
    $this->get(route('dashboard.shop.financial_report.export', ['shop' => 1, 'format' => 'xlsx']))
        ->assertRedirect('/login');
});
