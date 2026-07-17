<?php

use App\Jobs\ProcessRentContractBatchJob;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Models\User;
use App\Services\Ai\ExtractionManager;
use App\Services\Ai\PdfService;
use App\Services\Ai\RentImportService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\Support\FakeExtractionEngine;
use Tests\Support\FakeExtractionManager;

/**
 * اختبار المسار الكامل لمعالجة عقود الإيجار: رفع → تجزئة → استخراج (محرك وهمي)
 * → كشف تكرار العقد → مراجعة → اعتماد وإنشاء العقد + توليد جدول الدفعات آلياً.
 */
beforeEach(function () {
    // جدولا shop_rent و shop_rentpay من Oracle — ننشئ نسخة SQLite مبسّطة.
    Schema::dropIfExists('shop_rent');
    Schema::create('shop_rent', function ($t) {
        $t->id('shop_rent_id');
        $t->unsignedBigInteger('shop_id')->nullable();
        $t->string('rent_no')->nullable();
        $t->string('rent_name')->nullable();
        $t->string('rent_sdt')->nullable();
        $t->string('rent_edt')->nullable();
        $t->text('rent_note')->nullable();
        $t->string('contract_no')->nullable();
        $t->string('start_date')->nullable();
        $t->string('end_date')->nullable();
        $t->string('landlord')->nullable();
        $t->string('tenant')->nullable();
        $t->text('property_info')->nullable();
        $t->decimal('rent_value', 15, 2)->nullable();
        $t->integer('payments_count')->nullable();
        $t->text('renewal_terms')->nullable();
        $t->text('termination_terms')->nullable();
        $t->unsignedBigInteger('import_item_id')->nullable();
        $t->string('ai_contract_file', 500)->nullable();
        $t->unsignedBigInteger('create_user')->nullable();
        $t->timestamp('created_at')->nullable();
    });

    Schema::dropIfExists('shop_rentpay');
    Schema::create('shop_rentpay', function ($t) {
        $t->id('shop_rentpay_id');
        $t->unsignedBigInteger('shop_id')->nullable();
        $t->string('rentpay_dt')->nullable();
        $t->decimal('rentpay_price', 15, 2)->nullable();
        $t->text('rentpay_note')->nullable();
        $t->unsignedBigInteger('create_user')->nullable();
        $t->timestamp('created_at')->nullable();
    });
});

function fakeRentEngine(FakeExtractionEngine $engine): FakeExtractionEngine
{
    app()->instance(ExtractionManager::class, new FakeExtractionManager($engine));
    return $engine;
}

function fakeRentPages(array $paths): void
{
    $mock = Mockery::mock(PdfService::class);
    $mock->shouldReceive('rasterizeAll')->andReturn($paths);
    app()->instance(PdfService::class, $mock);
}

function plRentBatch(): RentContractImportBatch
{
    $u = User::factory()->create();
    return RentContractImportBatch::create([
        'original_filename' => 'contracts.pdf',
        'file_path'         => 'rent/batches/contracts.pdf',
        'file_hash'         => str_repeat('b', 64),
        'status'            => RentContractImportBatch::STATUS_PENDING,
        'engine'            => 'openai',
        'create_user'       => $u->id,
    ]);
}

it('runs the full rent pipeline and auto-generates the payment schedule', function () {
    fakeRentEngine(new FakeExtractionEngine(
        data: [
            'contract_no'    => 'C-2026-01',
            'start_date'     => '2026-01-01',
            'end_date'       => '2026-12-31',
            'landlord'       => 'المالك',
            'tenant'         => 'المستأجر',
            'property_info'  => 'محل رقم 5',
            'rent_value'     => 120000,
            'payments_count' => 12,
        ],
        confidence: 0.96,
    ));
    fakeRentPages(['/tmp/c1.png']);

    $batch = plRentBatch();
    (new ProcessRentContractBatchJob($batch->id))->handle(app(PdfService::class));

    $item = RentContractImportItem::where('batch_id', $batch->id)->first();
    expect($item->status)->toBe(RentContractImportItem::STATUS_NEEDS_REVIEW);

    $batch->refresh();
    expect($batch->status)->toBe(RentContractImportBatch::STATUS_COMPLETED);
    expect($batch->total_items)->toBe(1);

    // الاعتماد → عقد + 12 دفعة مولّدة آلياً بمجموع يساوي قيمة العقد
    $shopRentId = app(RentImportService::class)->approveItem($item, ['shop_id' => 7], $batch->create_user);
    expect($shopRentId)->toBeGreaterThan(0);

    $payments = DB::table('shop_rentpay')->where('shop_id', 7)->get();
    expect($payments)->toHaveCount(12);
    expect(round($payments->sum('rentpay_price'), 2))->toBe(120000.0);
    expect($item->fresh()->status)->toBe(RentContractImportItem::STATUS_APPROVED);
});

it('END-TO-END: a user uploads a file then the page drives it to completion in realtime (zero background queue)', function () {
    // نحاكي ما يفعله المستخدم والمتصفّح بالضبط عبر مسارات HTTP الحقيقية (رفع → صفحة المتابعة → نداءات step).
    Storage::fake(config('ai.disk'));
    fakeRentEngine(new FakeExtractionEngine(
        data: ['contract_no' => 'C-E2E', 'start_date' => '2026-01-01', 'end_date' => '2026-12-31',
               'landlord' => 'المالك', 'tenant' => 'المستأجر', 'rent_value' => 48000, 'payments_count' => 4],
        confidence: 0.95,
    ));
    fakeRentPages(['/tmp/e2e1.png']);

    $user = User::factory()->create();

    // 1) المستخدم يرفع ملف العقد (نفس ما يرسله المتصفّح: AJAX/JSON)
    $file = UploadedFile::fake()->create('عقد.pdf', 200, 'application/pdf');
    $upload = $this->actingAs($user)->postJson(route('dashboard.rent.ai.store'), ['document' => $file]);
    $upload->assertOk()->assertJson(['ok' => true]);

    $batch = RentContractImportBatch::latest()->first();
    expect($batch)->not->toBeNull();
    // لا معالجة خلفية إطلاقاً: مع طابور الاختبار المتزامن (sync)، لو كان هناك أي dispatch لكانت
    // الدفعة اكتملت أثناء الرفع. بقاؤها «pending» بلا عناصر يُثبت أن لا شيء يعمل في الخلفية.
    expect($batch->status)->toBe(RentContractImportBatch::STATUS_PENDING);
    expect($batch->items()->count())->toBe(0);

    // 2) صفحة المتابعة تُصيّر سائق المعالجة اللحظية الذي يستدعي endpoint الخطوة
    //    (نفحص المصدر لأن القالب الكامل يستعلم جدول permission المستورد غير الموجود في SQLite)
    $blade = file_get_contents(base_path('resources/views/dashboard/rent/ai/status.blade.php'));
    expect($blade)->toContain("dashboard.rent.ai.batch.step");

    // 3) المتصفّح يكرّر نداء step حتى done=true (تحضير ثم استخراج)
    $stepUrl = route('dashboard.rent.ai.batch.step', $batch->id);
    $steps = 0; $done = false; $sawPrepared = false;
    while ($steps++ < 8) {
        $d = $this->actingAs($user)->postJson($stepUrl)->assertOk()->json();
        if (($d['phase'] ?? null) === 'prepared') { $sawPrepared = true; }
        if ($d['done']) { $done = true; break; }
    }

    expect($sawPrepared)->toBeTrue();  // مرّت مرحلة التحضير
    expect($done)->toBeTrue();          // اكتملت المعالجة فعلاً
    $batch->refresh();
    expect($batch->status)->toBe(RentContractImportBatch::STATUS_COMPLETED);
    expect($batch->total_items)->toBe(1);
    // العقد صار جاهزاً للمراجعة/الاعتماد (ما يراه المستخدم في الشاشة التالية)
    expect(RentContractImportItem::where('batch_id', $batch->id)
        ->where('status', RentContractImportItem::STATUS_NEEDS_REVIEW)->count())->toBe(1);
});

it('processes contracts in realtime via the browser-driven step endpoint (no queue)', function () {
    fakeRentEngine(new FakeExtractionEngine(
        data: [
            'contract_no' => 'C-STEP', 'start_date' => '2026-02-01', 'end_date' => '2026-12-31',
            'landlord' => 'المالك', 'tenant' => 'المستأجر', 'rent_value' => 60000, 'payments_count' => 6,
        ],
        confidence: 0.95,
    ));
    fakeRentPages(['/tmp/rc1.png']);

    $batch = plRentBatch();
    $stepUrl = route('dashboard.rent.ai.batch.step', $batch->id);
    $user = User::find($batch->create_user);

    // أول خطوة تجهّز العناصر، ثم نكرّر كما يفعل المتصفّح حتى تنتهي
    $this->actingAs($user)->postJson($stepUrl)
        ->assertOk()->assertJson(['phase' => 'prepared', 'total_items' => 1, 'done' => false]);

    $last = null;
    for ($i = 0; $i < 4; $i++) {
        $last = $this->actingAs($user)->postJson($stepUrl)->assertOk()->json();
        if ($last['done']) {
            break;
        }
    }

    expect($last['done'])->toBeTrue();
    $batch->refresh();
    expect($batch->status)->toBe(RentContractImportBatch::STATUS_COMPLETED);
    expect(RentContractImportItem::where('batch_id', $batch->id)->first()->status)
        ->toBe(RentContractImportItem::STATUS_NEEDS_REVIEW);
});

it('flags a contract whose number already exists as duplicate', function () {
    DB::table('shop_rent')->insert(['contract_no' => 'C-DUP', 'rent_no' => 'C-DUP']);

    fakeRentEngine(new FakeExtractionEngine(
        data: ['contract_no' => 'C-DUP', 'start_date' => '2026-03-01', 'payments_count' => 6, 'rent_value' => 60000],
        confidence: 0.9,
    ));
    fakeRentPages(['/tmp/dup.png']);

    $batch = plRentBatch();
    (new ProcessRentContractBatchJob($batch->id))->handle(app(PdfService::class));

    $item = RentContractImportItem::where('batch_id', $batch->id)->first();
    expect((bool) $item->is_duplicate)->toBeTrue();
    expect($item->status)->toBe(RentContractImportItem::STATUS_NEEDS_REVIEW); // يظل للمراجعة البشرية
});
