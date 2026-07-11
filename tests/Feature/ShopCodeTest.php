<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * إدارة أكواد المحلات: فرادة الكود، تسجيل المنشئ/التاريخ، التعديل، التفعيل، والبحث.
 * جدول shop من Oracle (لا هجرة له) → ننشئ نسخة مبسّطة في SQLite.
 */
beforeEach(function () {
    Schema::dropIfExists('shop');
    Schema::create('shop', function ($t) {
        $t->id('shop_id');
        $t->string('shop_name')->nullable();
        $t->string('shop_code')->nullable();
        $t->tinyInteger('shop_code_active')->default(1);
        $t->unsignedBigInteger('shop_code_by')->nullable();
        $t->timestamp('shop_code_at')->nullable();
    });

    DB::table('shop')->insert([
        ['shop_id' => 1, 'shop_name' => 'مطعم الريان'],
        ['shop_id' => 2, 'shop_name' => 'سوبر ماركت النخيل'],
    ]);
});

it('assigns a unique code and records the creator + timestamp', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('dashboard.shop_codes.save'), ['shop_id' => 1, 'shop_code' => 'SH001'])
        ->assertOk()->assertJson(['ok' => true]);

    $row = DB::table('shop')->where('shop_id', 1)->first();
    expect($row->shop_code)->toBe('SH001');
    expect((int) $row->shop_code_by)->toBe($user->id);
    expect($row->shop_code_at)->not->toBeNull();
    expect((int) $row->shop_code_active)->toBe(1);
});

it('rejects a code already used by another shop', function () {
    DB::table('shop')->where('shop_id', 1)->update(['shop_code' => 'SH001']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('dashboard.shop_codes.save'), ['shop_id' => 2, 'shop_code' => 'SH001'])
        ->assertStatus(422); // فشل التحقق (كود مكرر)

    expect(DB::table('shop')->where('shop_id', 2)->value('shop_code'))->toBeNull();
});

it('allows keeping the same code on the same shop (edit)', function () {
    $user = User::factory()->create();
    DB::table('shop')->where('shop_id', 1)->update(['shop_code' => 'SH001', 'shop_code_by' => $user->id]);

    // إعادة حفظ نفس الكود لنفس المحل يجب ألا تُرفض
    $this->actingAs($user)
        ->postJson(route('dashboard.shop_codes.save'), ['shop_id' => 1, 'shop_code' => 'SH001'])
        ->assertOk();

    expect(DB::table('shop')->where('shop_id', 1)->value('shop_code'))->toBe('SH001');
});

it('toggles the code active/inactive', function () {
    $user = User::factory()->create();
    DB::table('shop')->where('shop_id', 1)->update(['shop_code' => 'SH001', 'shop_code_active' => 1]);

    $this->actingAs($user)
        ->postJson(route('dashboard.shop_codes.toggle'), ['shop_id' => 1])
        ->assertOk()->assertJson(['active' => 0]);
    expect((int) DB::table('shop')->where('shop_id', 1)->value('shop_code_active'))->toBe(0);

    $this->actingAs($user)
        ->postJson(route('dashboard.shop_codes.toggle'), ['shop_id' => 1])
        ->assertJson(['active' => 1]);
});

it('requires authentication', function () {
    $this->postJson(route('dashboard.shop_codes.save'), ['shop_id' => 1, 'shop_code' => 'X'])
        ->assertUnauthorized();
});
