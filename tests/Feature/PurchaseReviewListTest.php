<?php

use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\User;

/**
 * يغطّي منطق جلب/ترتيب/ترقيم وإعادة جلب جدول «الفواتير بانتظار المراجعة».
 * (لا يختبر approveItem() لأنها تكتب في جدول purchase المستورد من SQL وغير الموجود
 *  في قاعدة اختبار SQLite — نحاكي الاعتماد بتغيير status مباشرةً.)
 */

function purchaseBatch(int $ownerId): PurchaseImportBatch
{
    return PurchaseImportBatch::create([
        'original_filename' => 'inv.pdf',
        'file_path'         => 'purchase/batches/inv.pdf',
        'file_hash'         => str_repeat('c', 64),
        'status'            => 'completed',
        'engine'            => 'openai',
        'create_user'       => $ownerId,
    ]);
}

function purchaseItem(int $batchId, string $status = 'needs_review', array $overrides = []): PurchaseImportItem
{
    return PurchaseImportItem::create(array_merge([
        'batch_id'       => $batchId,
        'page_from'      => 1,
        'page_to'        => 1,
        'status'         => $status,
        'confidence'     => 0.95,
        'extracted_json' => ['data' => ['invoice_no' => 'INV-'.uniqid(), 'supplier_name' => 'مورد', 'total' => 1150]],
    ], $overrides));
}

it('shows only needs_review items with a matching count', function () {
    $user = User::factory()->create();
    $batch = purchaseBatch($user->id);
    purchaseItem($batch->id, 'needs_review');
    purchaseItem($batch->id, 'needs_review');
    purchaseItem($batch->id, 'approved');
    purchaseItem($batch->id, 'rejected');
    purchaseItem($batch->id, 'failed');

    // عبر AJAX (تجنّب عرض الـ layout الذي يستعلم جداول القائمة permission المستوردة من SQL).
    $res = $this->actingAs($user)->get(route('dashboard.purchase.ai.review'), ['X-Requested-With' => 'XMLHttpRequest']);

    $res->assertOk();
    expect($res->json('count'))->toBe(2);
});

it('orders deterministically by id desc on equal created_at', function () {
    $user = User::factory()->create();
    $batch = purchaseBatch($user->id);
    $ts = now()->subDay();
    $a = purchaseItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]);
    $b = purchaseItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]);
    $c = purchaseItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]);

    $ordered = PurchaseImportItem::query()->needsReviewOrdered()->pluck('id')->all();

    expect($ordered)->toBe([$c->id, $b->id, $a->id]);
});

it('paginates 20 per page without overlap between pages', function () {
    $user = User::factory()->create();
    $batch = purchaseBatch($user->id);
    collect(range(1, 25))->each(fn () => purchaseItem($batch->id, 'needs_review'));

    $page1 = $this->actingAs($user)->get(route('dashboard.purchase.ai.review', ['page' => 1]), ['X-Requested-With' => 'XMLHttpRequest']);
    $page2 = $this->actingAs($user)->get(route('dashboard.purchase.ai.review', ['page' => 2]), ['X-Requested-With' => 'XMLHttpRequest']);

    expect($page1->json('count'))->toBe(25);
    expect(substr_count($page1->json('html'), '<tr data-item="'))->toBe(20);
    expect(substr_count($page2->json('html'), '<tr data-item="'))->toBe(5);

    preg_match_all('/<tr data-item="(\d+)"/', $page1->json('html'), $m1);
    preg_match_all('/<tr data-item="(\d+)"/', $page2->json('html'), $m2);
    expect(array_intersect($m1[1], $m2[1]))->toBe([]);
});

it('returns json partial for ajax requests', function () {
    $user = User::factory()->create();
    $batch = purchaseBatch($user->id);
    $item = purchaseItem($batch->id, 'needs_review');

    $res = $this->actingAs($user)->get(route('dashboard.purchase.ai.review'), ['X-Requested-With' => 'XMLHttpRequest']);

    $res->assertOk();
    expect($res->json('count'))->toBe(1);
    expect($res->json('html'))->toContain('data-item="'.$item->id.'"');
});

it('reveals the next item after one is approved (the reported bug)', function () {
    $user = User::factory()->create();
    $batch = purchaseBatch($user->id);
    $ts = now()->subDay();
    $items = collect(range(1, 21))->map(fn () => purchaseItem($batch->id, 'needs_review', ['created_at' => $ts, 'updated_at' => $ts]));
    $oldest = $items->first();

    $before = $this->actingAs($user)->get(route('dashboard.purchase.ai.review', ['page' => 1]), ['X-Requested-With' => 'XMLHttpRequest']);
    expect($before->json('count'))->toBe(21);
    expect($before->json('html'))->not->toContain('<tr data-item="'.$oldest->id.'"');

    $items->last()->update(['status' => 'approved']);

    $after = $this->actingAs($user)->get(route('dashboard.purchase.ai.review', ['page' => 1]), ['X-Requested-With' => 'XMLHttpRequest']);
    expect($after->json('count'))->toBe(20);
    expect($after->json('html'))->toContain('<tr data-item="'.$oldest->id.'"');
});

it('signals an emptied last page so the client can step back', function () {
    $user = User::factory()->create();
    $batch = purchaseBatch($user->id);
    collect(range(1, 21))->each(fn () => purchaseItem($batch->id, 'needs_review'));

    PurchaseImportItem::orderBy('id')->first()->update(['status' => 'approved']);

    $page2 = $this->actingAs($user)->get(route('dashboard.purchase.ai.review', ['page' => 2]), ['X-Requested-With' => 'XMLHttpRequest']);

    expect($page2->json('count'))->toBe(20);
    expect($page2->json('count') <= (2 - 1) * 20)->toBeTrue();
});

it('redirects guests to login', function () {
    $this->get(route('dashboard.purchase.ai.review'))->assertRedirect('/login');
});
