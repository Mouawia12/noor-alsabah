<?php

use App\Jobs\ProcessPurchaseBatchJob;
use App\Models\PurchaseImportBatch;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

/**
 * اختبار مسار الرفع: استقبال الملف → إنشاء دفعة → جدولة المعالجة في الخلفية،
 * دون تشغيل معالجة حقيقية (Queue::fake) ودون قرص حقيقي (Storage::fake).
 */
beforeEach(function () {
    Storage::fake(config('ai.disk'));
    Queue::fake();
});

it('accepts a pdf upload, stores it, creates a batch, and queues background processing', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('invoices.pdf', 300, 'application/pdf');

    $res = $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'), ['document' => $file]);

    $res->assertOk()->assertJson(['ok' => true]);
    expect($res->json('redirect'))->toContain('/purchase/ai/batch/');

    // دفعة أُنشئت والملف حُفظ على القرص الخاص
    $batch = PurchaseImportBatch::first();
    expect($batch)->not->toBeNull();
    expect($batch->create_user)->toBe($user->id);
    Storage::disk(config('ai.disk'))->assertExists($batch->file_path);

    // المعالجة جُدولت في الخلفية (لم تُشغَّل الآن)
    Queue::assertPushed(ProcessPurchaseBatchJob::class);
});

it('rejects a non-allowed file type', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('note.txt', 10, 'text/plain');

    $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'), ['document' => $file])
        ->assertStatus(422);

    expect(PurchaseImportBatch::count())->toBe(0);
    Queue::assertNotPushed(ProcessPurchaseBatchJob::class);
});

it('rejects a request with no file', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'), [])
        ->assertStatus(422);
});

it('does not create a second batch when the same file is re-uploaded (dedup)', function () {
    $user = User::factory()->create();

    $first = $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'),
        ['document' => UploadedFile::fake()->create('invoices.pdf', 300, 'application/pdf')]);
    $first->assertOk();

    $second = $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'),
        ['document' => UploadedFile::fake()->create('invoices.pdf', 300, 'application/pdf')]);
    $second->assertOk();

    // نفس الملف (بصمة متطابقة) → لا تُنشأ دفعة ثانية
    expect(PurchaseImportBatch::count())->toBe(1);
    expect($first->json('redirect'))->toBe($second->json('redirect'));
});

it('requires authentication to upload', function () {
    $file = UploadedFile::fake()->create('invoices.pdf', 100, 'application/pdf');

    $this->post(route('dashboard.purchase.ai.store'), ['document' => $file])
        ->assertRedirect('/login');
});
