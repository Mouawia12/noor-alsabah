<?php

use App\Models\PurchaseImportBatch;
use App\Models\User;

// يمنع IDOR: لا يصل مستخدم إلى دفعة استيراد رفعها غيره إلا إن كان مديراً.

function makeBatch(int $ownerId): PurchaseImportBatch
{
    return PurchaseImportBatch::create([
        'original_filename' => 'inv.pdf',
        'file_path'         => 'purchase/batches/inv.pdf',
        'file_hash'         => str_repeat('a', 64),
        'status'            => 'completed',
        'engine'            => 'openai',
        'create_user'       => $ownerId,
    ]);
}

test('a non-owner non-admin cannot access another user\'s batch', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create(['emp_job' => 0]);
    $batch = makeBatch($owner->id);

    $this->actingAs($other)
        ->get(route('dashboard.purchase.ai.batch.json', $batch->id))
        ->assertForbidden();
});

test('the uploader can access their own batch', function () {
    $owner = User::factory()->create();
    $batch = makeBatch($owner->id);

    $this->actingAs($owner)
        ->get(route('dashboard.purchase.ai.batch.json', $batch->id))
        ->assertOk();
});

test('an admin can access any batch', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create(['emp_job' => 1]);
    $batch = makeBatch($owner->id);

    $this->actingAs($admin)
        ->get(route('dashboard.purchase.ai.batch.json', $batch->id))
        ->assertOk();
});

test('guests are redirected to login', function () {
    $batch = makeBatch(User::factory()->create()->id);

    $this->get(route('dashboard.purchase.ai.batch.json', $batch->id))
        ->assertRedirect('/login');
});
