<?php

use App\Jobs\ProcessPurchaseBatchJob;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\User;
use App\Services\Ai\DTO\ExtractionResult;
use App\Services\Ai\ExtractionManager;
use App\Services\Ai\PdfService;
use App\Services\Ai\PurchaseImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Support\FakeExtractionEngine;
use Tests\Support\FakeExtractionManager;

/**
 * اختبار المسار الكامل لمعالجة فواتير المشتريات: رفع → تجزئة صفحات → استخراج (محرك وهمي)
 * → قرار قبول/رفض → عدّادات الدفعة → اعتماد وإنشاء سجل مشتريات.
 * كل هذا بلا أي استدعاء OpenAI حقيقي أو أدوات PDF (كلاهما مُستبدَل بوهمي).
 */
beforeEach(function () {
    // جدول purchase من Oracle (لا هجرة له) — ننشئ نسخة SQLite مبسّطة.
    Schema::dropIfExists('purchase');
    Schema::create('purchase', function ($t) {
        $t->id('purchase_id');
        $t->string('purchase_no')->nullable();
        $t->string('purchase_dt')->nullable();
        $t->string('tax_number')->nullable();
        $t->string('currency')->nullable();
        $t->decimal('amount_before_tax', 15, 2)->nullable();
        $t->decimal('tax_amount', 15, 2)->nullable();
        $t->decimal('purchase_price', 15, 2)->nullable();
        $t->unsignedBigInteger('supplier_id')->nullable();
        $t->unsignedBigInteger('shop_id')->nullable();
        $t->string('purchasefile')->nullable();
        $t->text('note')->nullable();
        $t->unsignedBigInteger('import_item_id')->nullable();
        $t->timestamp('created_at')->nullable();
        $t->unsignedBigInteger('create_user')->nullable();
    });
});

/** يثبّت محرك استخراج وهمي في الحاوية ويُرجِعه. */
function fakePurchaseEngine(FakeExtractionEngine $engine): FakeExtractionEngine
{
    app()->instance(ExtractionManager::class, new FakeExtractionManager($engine));
    return $engine;
}

/** يزيّف PdfService ليُرجِع مسارات صفحات جاهزة بدل تحويل PDF حقيقي. */
function fakePages(array $paths): void
{
    $mock = Mockery::mock(PdfService::class);
    $mock->shouldReceive('rasterizeAll')->andReturn($paths);
    app()->instance(PdfService::class, $mock);
}

function plPurchaseBatch(): PurchaseImportBatch
{
    $u = User::factory()->create();
    return PurchaseImportBatch::create([
        'original_filename' => 'invoices.pdf',
        'file_path'         => 'purchase/batches/invoices.pdf',
        'file_hash'         => str_repeat('a', 64),
        'status'            => PurchaseImportBatch::STATUS_PENDING,
        'engine'            => 'openai',
        'create_user'       => $u->id,
    ]);
}

it('runs the full purchase pipeline: pages → extract → needs_review → completed', function () {
    fakePurchaseEngine(new FakeExtractionEngine(
        data: ['invoice_no' => 'INV-100', 'total' => 1150, 'supplier_name' => 'شركة النور', 'tax_number' => '300000000000003'],
        confidence: 0.95,
    ));
    fakePages(['/tmp/p1.png', '/tmp/p2.png']);

    $batch = plPurchaseBatch();
    (new ProcessPurchaseBatchJob($batch->id))->handle(app(PdfService::class));

    // صفحتان → عنصران، كلاهما اجتاز الاستخراج
    $items = PurchaseImportItem::where('batch_id', $batch->id)->get();
    expect($items)->toHaveCount(2);
    expect($items->pluck('status')->unique()->all())->toBe([PurchaseImportItem::STATUS_NEEDS_REVIEW]);
    expect((float) $items->first()->confidence)->toBe(0.95);

    // الدفعة اكتملت مع عدّادات صحيحة
    $batch->refresh();
    expect($batch->status)->toBe(PurchaseImportBatch::STATUS_COMPLETED);
    expect($batch->total_items)->toBe(2);
    expect($batch->processed_items)->toBe(2);
    expect($batch->failed_items)->toBe(0);
});

it('rejects an unreadable invoice with a clear Arabic reason', function () {
    fakePurchaseEngine(new FakeExtractionEngine(
        data: ['invoice_no' => null, 'total' => null, 'supplier_name' => null],
        confidence: 0.2,
    ));
    fakePages(['/tmp/blurry.png']);

    $batch = plPurchaseBatch();
    (new ProcessPurchaseBatchJob($batch->id))->handle(app(PdfService::class));

    $item = PurchaseImportItem::where('batch_id', $batch->id)->first();
    expect($item->status)->toBe(PurchaseImportItem::STATUS_FAILED);
    expect($item->error_reason)->toContain('لا يحتوي فاتورة واضحة');

    $batch->refresh();
    expect($batch->status)->toBe(PurchaseImportBatch::STATUS_COMPLETED); // اكتملت المعالجة رغم الرفض
    expect($batch->failed_items)->toBe(1);
    expect($batch->processed_items)->toBe(0);
});

it('escalates to the heavy model when confidence is low', function () {
    $engine = fakePurchaseEngine(new FakeExtractionEngine(
        data: ['invoice_no' => 'INV-ESC', 'total' => 500],
        confidence: 0.50, // أقل من العتبة 0.80 → تصعيد
        heavy: new ExtractionResult(
            data: ['invoice_no' => 'INV-ESC', 'total' => 500],
            confidence: 0.93,
            model: 'fake-heavy',
        ),
    ));
    fakePages(['/tmp/low.png']);

    $batch = plPurchaseBatch();
    (new ProcessPurchaseBatchJob($batch->id))->handle(app(PdfService::class));

    expect($engine->calls)->toBe(2); // استدعاء عادي + تصعيد
    $item = PurchaseImportItem::where('batch_id', $batch->id)->first();
    expect((float) $item->confidence)->toBe(0.93); // اعتُمدت نتيجة النموذج الأقوى
    expect($item->status)->toBe(PurchaseImportItem::STATUS_NEEDS_REVIEW);
});

it('approves a reviewed item and creates a real purchase record', function () {
    fakePurchaseEngine(new FakeExtractionEngine(
        data: ['invoice_no' => 'INV-APR', 'total' => 2300, 'amount_before_tax' => 2000, 'tax_amount' => 300, 'supplier_name' => 'مورد'],
        confidence: 0.97,
    ));
    fakePages(['/tmp/ok.png']);

    $batch = plPurchaseBatch();
    (new ProcessPurchaseBatchJob($batch->id))->handle(app(PdfService::class));

    $item = PurchaseImportItem::where('batch_id', $batch->id)->first();
    $purchaseId = app(PurchaseImportService::class)->approveItem($item, [], $batch->create_user);

    expect($purchaseId)->toBeGreaterThan(0);
    $row = DB::table('purchase')->where('purchase_id', $purchaseId)->first();
    expect($row->purchase_no)->toBe('INV-APR');
    expect((float) $row->purchase_price)->toBe(2300.0);
    expect($item->fresh()->status)->toBe(PurchaseImportItem::STATUS_APPROVED);
});

it('links the created purchase to the chosen branch (shop_id) on transfer', function () {
    fakePurchaseEngine(new FakeExtractionEngine(
        data: ['invoice_no' => 'INV-BR', 'total' => 900, 'supplier_name' => 'مورد'],
        confidence: 0.95,
    ));
    fakePages(['/tmp/br.png']);

    $batch = plPurchaseBatch();
    (new ProcessPurchaseBatchJob($batch->id))->handle(app(PdfService::class));

    $item = PurchaseImportItem::where('batch_id', $batch->id)->first();
    // ترحيل إلى الفرع رقم 42 (اختيار المستخدم من قائمة المحلات)
    $purchaseId = app(PurchaseImportService::class)->approveItem($item, ['shop_id' => 42], $batch->create_user);

    $row = DB::table('purchase')->where('purchase_id', $purchaseId)->first();
    expect((int) $row->shop_id)->toBe(42);
});

it('bulk-transfers accepted invoices to the named branch and returns its name', function () {
    // جدول المحلات + فرع (كما في نظام إدارة المحلات)
    Schema::dropIfExists('shop');
    Schema::create('shop', function ($t) {
        $t->id('shop_id');
        $t->string('shop_name')->nullable();
    });
    DB::table('shop')->insert(['shop_id' => 3, 'shop_name' => 'نور الصباح - المحمدية']);

    fakePurchaseEngine(new FakeExtractionEngine(
        data: ['invoice_no' => 'INV-ALL', 'total' => 300, 'supplier_name' => 'مورد'],
        confidence: 0.95,
    ));
    fakePages(['/tmp/all.png']);

    $batch = plPurchaseBatch();
    (new ProcessPurchaseBatchJob($batch->id))->handle(app(PdfService::class));
    $item = PurchaseImportItem::where('batch_id', $batch->id)->first();
    $user = User::find($batch->create_user);

    // الترحيل الجماعي إلى الفرع 3 → يجب أن يُعيد اسم الفرع ويربط الفاتورة به
    $res = $this->actingAs($user)->postJson(route('dashboard.purchase.ai.approve_all'), [
        'ids' => [$item->id], 'shop_id' => 3,
    ]);

    $res->assertOk()->assertJson(['ok' => true, 'approved' => 1, 'shop_name' => 'نور الصباح - المحمدية']);
    expect((int) DB::table('purchase')->where('purchase_no', 'INV-ALL')->value('shop_id'))->toBe(3);
});

it('fails a batch that exceeds the max pages/invoices cap', function () {
    config()->set('ai.max_pages_per_batch', 3);

    // PdfService وهمي: يُبلّغ أن الملف 5 صفحات (> الحد 3)
    $mock = Mockery::mock(PdfService::class);
    $mock->shouldReceive('pageCount')->andReturn(5);
    $mock->shouldReceive('rasterizeAll')->andReturn(['/tmp/a.png', '/tmp/b.png', '/tmp/c.png', '/tmp/d.png', '/tmp/e.png']);
    app()->instance(PdfService::class, $mock);

    $batch = plPurchaseBatch();
    try {
        (new ProcessPurchaseBatchJob($batch->id))->handle(app(PdfService::class));
    } catch (\Throwable $e) {
        // الوظيفة تُعيد رمي الخطأ بعد تعليم الدفعة فاشلة
    }

    $batch->refresh();
    expect($batch->status)->toBe(PurchaseImportBatch::STATUS_FAILED);
    expect($batch->error_reason)->toContain('الحد الأقصى');
    expect(PurchaseImportItem::where('batch_id', $batch->id)->count())->toBe(0);
});

it('refuses bulk transfer when no branch is chosen', function () {
    $batch = plPurchaseBatch();
    $user = User::find($batch->create_user);

    $this->actingAs($user)->postJson(route('dashboard.purchase.ai.approve_all'), ['ids' => []])
        ->assertStatus(422)
        ->assertJson(['ok' => false]);
});
