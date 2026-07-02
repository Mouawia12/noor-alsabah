<?php

use App\Jobs\ProcessRentContractBatchJob;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Models\User;
use App\Services\Ai\ExtractionManager;
use App\Services\Ai\PdfService;
use App\Services\Ai\RentImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
