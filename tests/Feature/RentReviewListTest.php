<?php

use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

/**
 * يغطّي منطق جلب/ترتيب/ترقيم وإعادة جلب جدول «العقود بانتظار المراجعة».
 * (لا يختبر approveItem() لأنها تكتب في جداول shop_rent/shop_rentpay المستوردة من SQL
 *  وغير الموجودة في قاعدة اختبار SQLite — نحاكي الاعتماد بتغيير status مباشرةً.)
 */

// جدول shop مستورد من SQL ولا migration له → ننشئه يدوياً لاختبار review() الذي يستعلمه.
beforeEach(function () {
    if (! Schema::hasTable('shop')) {
        Schema::create('shop', function ($t) {
            $t->bigIncrements('shop_id');
            $t->string('shop_name')->nullable();
        });
    }
});

function rentBatch(int $ownerId): RentContractImportBatch
{
    return RentContractImportBatch::create([
        'original_filename' => 'contract.pdf',
        'file_path'         => 'rent/batches/contract.pdf',
        'file_hash'         => str_repeat('b', 64),
        'status'            => 'completed',
        'engine'            => 'openai',
        'create_user'       => $ownerId,
    ]);
}

function rentItem(int $batchId, string $status = 'needs_review', array $overrides = []): RentContractImportItem
{
    return RentContractImportItem::create(array_merge([
        'batch_id'       => $batchId,
        'page_from'      => 1,
        'page_to'        => 1,
        'status'         => $status,
        'confidence'     => 0.95,
        'extracted_json' => ['data' => ['contract_no' => 'C-'.uniqid(), 'landlord' => 'مالك', 'rent_value' => 1000]],
    ], $overrides));
}

it('shows only needs_review items with a matching count', function () {
    $user = User::factory()->create();
    $batch = rentBatch($user->id);
    rentItem($batch->id, 'needs_review');
    rentItem($batch->id, 'needs_review');
    rentItem($batch->id, 'approved');
    rentItem($batch->id, 'rejected');
    rentItem($batch->id, 'failed');

    // عبر AJAX (تجنّب عرض الـ layout الذي يستعلم جداول القائمة permission المستوردة من SQL).
    $res = $this->actingAs($user)->get(route('dashboard.rent.ai.review'), ['X-Requested-With' => 'XMLHttpRequest']);

    $res->assertOk();
    expect($res->json('count'))->toBe(2);
});

it('orders deterministically by id desc on equal created_at', function () {
    $user = User::factory()->create();
    $batch = rentBatch($user->id);
    $ts = now()->subDay();
    $a = rentItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]);
    $b = rentItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]);
    $c = rentItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]);

    $ordered = RentContractImportItem::query()->needsReviewOrdered()->pluck('id')->all();

    expect($ordered)->toBe([$c->id, $b->id, $a->id]);
});

it('paginates 20 per page without overlap between pages', function () {
    $user = User::factory()->create();
    $batch = rentBatch($user->id);
    collect(range(1, 25))->each(fn () => rentItem($batch->id, 'needs_review'));

    $page1 = $this->actingAs($user)->get(route('dashboard.rent.ai.review', ['page' => 1]), ['X-Requested-With' => 'XMLHttpRequest']);
    $page2 = $this->actingAs($user)->get(route('dashboard.rent.ai.review', ['page' => 2]), ['X-Requested-With' => 'XMLHttpRequest']);

    expect($page1->json('count'))->toBe(25);
    expect(substr_count($page1->json('html'), '<tr data-item="'))->toBe(20);
    expect(substr_count($page2->json('html'), '<tr data-item="'))->toBe(5);

    // لا تكرار: معرّفات صفوف الصفحة 1 لا تظهر في الصفحة 2.
    preg_match_all('/<tr data-item="(\d+)"/', $page1->json('html'), $m1);
    preg_match_all('/<tr data-item="(\d+)"/', $page2->json('html'), $m2);
    expect(array_intersect($m1[1], $m2[1]))->toBe([]);
});

it('returns json partial for ajax requests', function () {
    $user = User::factory()->create();
    $batch = rentBatch($user->id);
    $item = rentItem($batch->id, 'needs_review');

    $res = $this->actingAs($user)->get(route('dashboard.rent.ai.review'), ['X-Requested-With' => 'XMLHttpRequest']);

    $res->assertOk();
    expect($res->json('count'))->toBe(1);
    expect($res->json('html'))->toContain('data-item="'.$item->id.'"');
});

it('reveals the next item after one is approved (the reported bug)', function () {
    $user = User::factory()->create();
    $batch = rentBatch($user->id);
    $ts = now()->subDay();
    // 21 عنصراً بنفس created_at → الترتيب id desc؛ الأقدم (أصغر id) يقع في الصفحة 2.
    $items = collect(range(1, 21))->map(fn () => rentItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]));
    $oldest = $items->first(); // أصغر id → غير ظاهر في الصفحة 1 ابتداءً

    $before = $this->actingAs($user)->get(route('dashboard.rent.ai.review', ['page' => 1]), ['X-Requested-With' => 'XMLHttpRequest']);
    expect($before->json('count'))->toBe(21);
    expect($before->json('html'))->not->toContain('<tr data-item="'.$oldest->id.'"');

    // محاكاة اعتماد عنصر ظاهر.
    $visible = $items->last();
    $visible->update(['status' => 'approved']);

    $after = $this->actingAs($user)->get(route('dashboard.rent.ai.review', ['page' => 1]), ['X-Requested-With' => 'XMLHttpRequest']);
    expect($after->json('count'))->toBe(20);
    expect($after->json('html'))->toContain('<tr data-item="'.$oldest->id.'"'); // السطر الجديد ظهر بلا «خروج ودخول»
});

it('signals an emptied last page so the client can step back', function () {
    $user = User::factory()->create();
    $batch = rentBatch($user->id);
    collect(range(1, 21))->each(fn () => rentItem($batch->id, 'needs_review'));

    // اعتمد العنصر الوحيد في الصفحة 2 (أصغر id).
    RentContractImportItem::orderBy('id')->first()->update(['status' => 'approved']);

    $page2 = $this->actingAs($user)->get(route('dashboard.rent.ai.review', ['page' => 2]), ['X-Requested-With' => 'XMLHttpRequest']);

    // count=20 ≤ (page-1)*20 = 20 → الصفحة 2 فارغة، يتراجع العميل للصفحة 1.
    expect($page2->json('count'))->toBe(20);
    expect($page2->json('count') <= (2 - 1) * 20)->toBeTrue();
});

it('redirects guests to login', function () {
    $this->get(route('dashboard.rent.ai.review'))->assertRedirect('/login');
});
