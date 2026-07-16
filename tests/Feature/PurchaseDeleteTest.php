<?php

use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\User;

/**
 * حذف الفواتير من قائمة «المقبولة بانتظار الترحيل» (فردي + متعدد).
 * الضمان الأهم: لا يُحذف أبداً عنصر مُرحّل (approved أو له purchase_id) حتى لا يُكسر سجل المشتريات.
 * الطلبات AJAX/JSON لتفادي عرض layout يستعلم جداول مستوردة من MySQL.
 */

function delBatch(int $ownerId): PurchaseImportBatch
{
    return PurchaseImportBatch::create([
        'original_filename' => 'inv.pdf',
        'file_path'         => 'purchase/batches/inv.pdf',
        'file_hash'         => str_repeat('e', 64),
        'status'            => 'completed',
        'engine'            => 'openai',
        'create_user'       => $ownerId,
    ]);
}

function delItem(int $batchId, string $status = 'needs_review', array $overrides = []): PurchaseImportItem
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

function ajax(): array
{
    return ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];
}

it('deletes a pending (needs_review) item from the queue', function () {
    $user = User::factory()->create();
    $item = delItem(delBatch($user->id)->id, 'needs_review');

    $res = $this->actingAs($user)
        ->postJson(route('dashboard.purchase.ai.destroy', $item->id), [], ajax());

    $res->assertOk();
    expect($res->json('ok'))->toBeTrue();
    expect(PurchaseImportItem::find($item->id))->toBeNull();
});

it('refuses to delete a posted (approved) invoice and keeps it', function () {
    $user = User::factory()->create();
    $item = delItem(delBatch($user->id)->id, 'approved', ['purchase_id' => 999]);

    $res = $this->actingAs($user)
        ->postJson(route('dashboard.purchase.ai.destroy', $item->id), [], ajax());

    $res->assertStatus(422);
    expect($res->json('ok'))->toBeFalse();
    expect(PurchaseImportItem::find($item->id))->not->toBeNull();
});

it('refuses to delete a needs_review item that already has a purchase_id (anomaly protection)', function () {
    $user = User::factory()->create();
    // شذوذ من البق القديم: needs_review لكن له سجل مشتريات مرتبط
    $item = delItem(delBatch($user->id)->id, 'needs_review', ['purchase_id' => 6447]);

    $res = $this->actingAs($user)
        ->postJson(route('dashboard.purchase.ai.destroy', $item->id), [], ajax());

    $res->assertStatus(422);
    expect(PurchaseImportItem::find($item->id))->not->toBeNull();
});

it('bulk-deletes only the pending items and skips posted ones', function () {
    $user = User::factory()->create();
    $batch = delBatch($user->id);
    $p1 = delItem($batch->id, 'needs_review');
    $p2 = delItem($batch->id, 'needs_review');
    $approved = delItem($batch->id, 'approved', ['purchase_id' => 111]);

    $res = $this->actingAs($user)->postJson(
        route('dashboard.purchase.ai.destroy_many'),
        ['ids' => [$p1->id, $p2->id, $approved->id]],
        ajax()
    );

    $res->assertOk();
    expect($res->json('deleted'))->toBe(2);
    expect(PurchaseImportItem::find($p1->id))->toBeNull();
    expect(PurchaseImportItem::find($p2->id))->toBeNull();
    expect(PurchaseImportItem::find($approved->id))->not->toBeNull(); // المُرحّل محميّ
});

it('forbids deleting an item from a batch the user does not own', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create(['emp_job' => 0]);
    $item = delItem(delBatch($owner->id)->id, 'needs_review');

    $this->actingAs($other)
        ->postJson(route('dashboard.purchase.ai.destroy', $item->id), [], ajax())
        ->assertForbidden();

    expect(PurchaseImportItem::find($item->id))->not->toBeNull();
});

it('lets an admin delete any pending item', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create(['emp_job' => 1]);
    $item = delItem(delBatch($owner->id)->id, 'needs_review');

    $this->actingAs($admin)
        ->postJson(route('dashboard.purchase.ai.destroy', $item->id), [], ajax())
        ->assertOk();

    expect(PurchaseImportItem::find($item->id))->toBeNull();
});

it('redirects guests to login', function () {
    $item = delItem(delBatch(User::factory()->create()->id)->id, 'needs_review');

    $this->post(route('dashboard.purchase.ai.destroy', $item->id))
        ->assertRedirect('/login');
});
