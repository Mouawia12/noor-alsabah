<?php

use App\Models\RentPaymentMatchItem;
use App\Models\ShopReceipt;
use App\Models\ShopRentpay;
use App\Models\User;
use App\Services\Ai\PaymentMatchingService;
use Illuminate\Support\Facades\DB;
use Tests\Support\RentTestSchema;

/**
 * مطابقة الدفعات بالذكاء الاصطناعي: التدرّج (رقم عقد/توليفة/يتيم/ناقص/زائد/مكرر) + اعتماد المستخدم.
 */
beforeEach(function () {
    RentTestSchema::install();
    DB::table('shop')->insert(['shop_id' => 1, 'shop_name' => 'محل']);
    DB::table('shop_rent')->insert([
        'shop_rent_id' => 10, 'shop_id' => 1, 'contract_no' => 'C-555',
        'tenant' => 'شركة الأمل التجارية', 'tenant_id_no' => '1010101010', 'unit_no' => 'A-12',
    ]);
    // بند سداد مستحق بقيمة 1000 في 2026-07-01
    DB::table('shop_rentpay')->insert([
        'rentpay_id' => 100, 'shop_id' => 1, 'shop_rent_id' => 10, 'seq_no' => 1,
        'rentpay_dt' => '2026-07-01', 'rentpay_price' => 1000, 'paid_amount' => 0, 'status' => 'unpaid',
    ]);
});

function matchPayment(array $p): array
{
    return app(PaymentMatchingService::class)->match($p);
}

it('matches by contract number with full confidence and a matching installment', function () {
    $m = matchPayment(['contract_no' => 'C-555', 'amount' => 1000, 'due_date' => '2026-07-01']);

    expect($m['status'])->toBe(RentPaymentMatchItem::MATCH_MATCHED);
    expect($m['matched'])->toBeTrue();
    expect($m['confidence'])->toBe(1.0);
    expect((int) $m['shop_rent_id'])->toBe(10);
    expect((int) $m['rentpay_id'])->toBe(100);
});

it('flags an underpaid amount', function () {
    $m = matchPayment(['contract_no' => 'C-555', 'amount' => 600, 'due_date' => '2026-07-01']);
    expect($m['status'])->toBe(RentPaymentMatchItem::MATCH_UNDERPAID);
    expect($m['reason'])->toContain('أقل من المستحق');
});

it('flags an overpaid amount', function () {
    $m = matchPayment(['contract_no' => 'C-555', 'amount' => 1500, 'due_date' => '2026-07-01']);
    expect($m['status'])->toBe(RentPaymentMatchItem::MATCH_OVERPAID);
});

it('detects a duplicate against an already-paid installment', function () {
    DB::table('shop_rentpay')->where('rentpay_id', 100)->update(['status' => 'paid', 'paid_amount' => 1000, 'is_paid' => true]);

    $m = matchPayment(['contract_no' => 'C-555', 'amount' => 1000, 'due_date' => '2026-07-01']);
    expect($m['status'])->toBe(RentPaymentMatchItem::MATCH_DUPLICATE);
});

it('returns orphan when no contract matches', function () {
    $m = matchPayment(['contract_no' => 'NOPE', 'tenant_name' => 'مجهول تماماً', 'amount' => 1000]);
    expect($m['status'])->toBe(RentPaymentMatchItem::MATCH_ORPHAN);
    expect($m['shop_rent_id'])->toBeNull();
});

it('matches by tenant national id when contract number is absent', function () {
    $m = matchPayment(['tenant_id_no' => '1010101010', 'amount' => 1000, 'due_date' => '2026-07-01']);
    expect((int) $m['shop_rent_id'])->toBe(10);
    expect($m['reason'])->toContain('هوية المستأجر');
});

it('fuzzy-matches by tenant name', function () {
    // اسم قريب جداً (اختلاف بسيط) → يجب أن يطابق العقد
    $m = matchPayment(['tenant_name' => 'شركة الأمل التجارية', 'amount' => 1000, 'due_date' => '2026-07-01']);
    expect((int) $m['shop_rent_id'])->toBe(10);
});

it('store endpoint saves a match item with the suggestion', function () {
    $user = User::factory()->create();

    $res = $this->actingAs($user)->postJson(route('dashboard.rent.payment_match.store'), [
        'contract_no' => 'C-555', 'amount' => 1000, 'due_date' => '2026-07-01',
    ]);

    $res->assertOk()->assertJson(['ok' => true]);
    $item = RentPaymentMatchItem::first();
    expect($item->match_status)->toBe(RentPaymentMatchItem::MATCH_MATCHED);
    expect((int) $item->matched_rentpay_id)->toBe(100);
});

it('approve endpoint records the payment and links it', function () {
    $user = User::factory()->create();
    $item = RentPaymentMatchItem::create([
        'contract_no' => 'C-555', 'amount' => 1000, 'due_date' => '2026-07-01',
        'matched_shop_id' => 1, 'matched_shop_rent_id' => 10, 'matched_rentpay_id' => 100,
        'confidence' => 1.0, 'match_status' => RentPaymentMatchItem::MATCH_MATCHED,
        'status' => RentPaymentMatchItem::STATUS_NEEDS_REVIEW, 'create_user' => $user->id,
    ]);

    $res = $this->actingAs($user)->postJson(route('dashboard.rent.payment_match.approve', $item->id));

    $res->assertOk()->assertJson(['ok' => true]);
    expect($item->fresh()->status)->toBe(RentPaymentMatchItem::STATUS_APPROVED);
    expect(ShopRentpay::find(100)->status)->toBe(ShopRentpay::STATUS_PAID);
    expect(ShopReceipt::where('rentpay_id', 100)->count())->toBe(1);
});

it('forbids approving a match item owned by another user', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create(['emp_job' => 0]);
    $item = RentPaymentMatchItem::create([
        'contract_no' => 'C-555', 'amount' => 1000,
        'matched_shop_rent_id' => 10, 'matched_rentpay_id' => 100,
        'match_status' => RentPaymentMatchItem::MATCH_MATCHED,
        'status' => RentPaymentMatchItem::STATUS_NEEDS_REVIEW, 'create_user' => $owner->id,
    ]);

    $this->actingAs($other)->postJson(route('dashboard.rent.payment_match.approve', $item->id))->assertForbidden();
});
