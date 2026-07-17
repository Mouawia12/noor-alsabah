<?php

use App\Jobs\ProcessRentContractBatchJob;
use App\Models\RentContractImportBatch;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

/**
 * رفع عقود الإيجار: إنشاء دفعة «قيد الانتظار» بلا جدولة خلفية (المعالجة لحظية عبر step)،
 * ورفض الملف المكرّر قبل تخزينه (المكرّر لا يُرفع أصلاً).
 */
beforeEach(function () {
    Storage::fake(config('ai.disk'));
    Queue::fake();
});

it('creates a pending rent batch WITHOUT background queue', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('contracts.pdf', 300, 'application/pdf');

    $res = $this->actingAs($user)->postJson(route('dashboard.rent.ai.store'), ['document' => $file]);

    $res->assertOk()->assertJson(['ok' => true]);
    $batch = RentContractImportBatch::first();
    expect($batch)->not->toBeNull();
    expect($batch->status)->toBe(RentContractImportBatch::STATUS_PENDING);
    Storage::disk(config('ai.disk'))->assertExists($batch->file_path);
    Queue::assertNotPushed(ProcessRentContractBatchJob::class);
});

it('rejects a duplicate contract re-upload and does NOT store or add it', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson(route('dashboard.rent.ai.store'),
        ['document' => UploadedFile::fake()->create('contracts.pdf', 300, 'application/pdf')])->assertOk();

    $second = $this->actingAs($user)->postJson(route('dashboard.rent.ai.store'),
        ['document' => UploadedFile::fake()->create('contracts.pdf', 300, 'application/pdf')]);

    $second->assertStatus(409)->assertJson(['ok' => false, 'duplicate' => true]);
    expect($second->json('redirect'))->toBeNull();
    expect($second->json('existing_url'))->toContain('/rent/ai/batch/');

    expect(RentContractImportBatch::count())->toBe(1);
    expect(Storage::disk(config('ai.disk'))->files('rent/batches'))->toHaveCount(1);
});
