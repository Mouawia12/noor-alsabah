<?php

use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

/**
 * سجل دفعات عقود الإيجار + صفحة نتائج الدفعة (بطاقات إحصائية وأعمدة تفصيلية)
 * + حفظ التعديل داخل الخلية (inline) في البيانات المستخرجة.
 */

// جداول مستوردة من SQL (لا migration لها) يحتاجها القالب/الشاشة — ننشئها فارغة لاختبار SQLite.
beforeEach(function () {
    if (! Schema::hasTable('shop')) {
        Schema::create('shop', function ($t) {
            $t->bigIncrements('shop_id');
            $t->string('shop_name')->nullable();
            $t->string('shop_code')->nullable();
        });
    }
    foreach ([
        'permission'     => ['role_id', 'emp_id', 'function_id'],
        'role_per'       => ['role_id', 'function_id'],
        'per_function'   => ['parent_id'],
        'per_controller' => [],
    ] as $table => $cols) {
        if (! Schema::hasTable($table)) {
            Schema::create($table, function ($t) use ($cols) {
                $t->id();
                foreach ($cols as $c) {
                    $t->unsignedBigInteger($c)->nullable();
                }
            });
        }
    }
});

function resBatch(int $ownerId, array $overrides = []): RentContractImportBatch
{
    return RentContractImportBatch::create(array_merge([
        'original_filename' => 'leases.pdf',
        'file_path'         => 'rent/batches/leases.pdf',
        'file_hash'         => str_repeat('r', 64),
        'status'            => 'completed',
        'engine'            => 'openai',
        'total_items'       => 2,
        'processed_items'   => 2,
        'create_user'       => $ownerId,
    ], $overrides));
}

function resItem(int $batchId, string $status = 'needs_review', array $data = []): RentContractImportItem
{
    return RentContractImportItem::create([
        'batch_id'       => $batchId,
        'page_from'      => 1,
        'page_to'        => 1,
        'status'         => $status,
        'confidence'     => 0.9,
        'extracted_json' => ['data' => array_merge([
            'contract_no' => 'C-1', 'tenant' => 'مستأجر', 'landlord' => 'مالك',
            'rent_value'  => 60000, 'payments_count' => 4,
        ], $data)],
    ]);
}

it('lists extraction batches with per-batch counts', function () {
    $user  = User::factory()->create();
    $batch = resBatch($user->id);
    resItem($batch->id, 'needs_review');
    resItem($batch->id, 'approved');

    $res = $this->actingAs($user)->get(route('dashboard.rent.ai.batches'));

    $res->assertOk();
    $res->assertSee('leases.pdf');
    $res->assertSee('سجل عمليات استخراج عقود الإيجار', false);
});

it('shows batch results with stat cards and rich contract columns', function () {
    $user  = User::factory()->create();
    $batch = resBatch($user->id);
    resItem($batch->id, 'needs_review');
    resItem($batch->id, 'approved');
    resItem($batch->id, 'failed');

    $res = $this->actingAs($user)->get(route('dashboard.rent.ai.batch.results', $batch->id));

    $res->assertOk();
    // بطاقات إحصائية
    $res->assertSee('عدد العقود', false);
    $res->assertSee('تحتاج مراجعة', false);
    $res->assertSee('مُعتمدة', false);
    // أعمدة تفصيلية + تعديل داخل الخلية
    foreach (['رقم العقد', 'المستأجر', 'المؤجر', 'الوحدة / العقار', 'عدد الدفعات'] as $col) {
        $res->assertSee($col, false);
    }
    $res->assertSee('cell-edit', false);
});

it('excludes merged pages from the results table', function () {
    $user  = User::factory()->create();
    $batch = resBatch($user->id);
    resItem($batch->id, 'needs_review', ['contract_no' => 'C-KEEP']);
    resItem($batch->id, 'merged', ['contract_no' => 'C-MERGED']);

    $res = $this->actingAs($user)->get(route('dashboard.rent.ai.batch.results', $batch->id));

    $res->assertOk()->assertSee('C-KEEP')->assertDontSee('C-MERGED');
});

it('saves an inline cell edit into the extracted data', function () {
    $user  = User::factory()->create();
    $batch = resBatch($user->id);
    $item  = resItem($batch->id);

    $this->actingAs($user)->postJson(route('dashboard.rent.ai.item.field', $item->id), [
        'field' => 'landlord', 'value' => 'المؤجر الجديد',
    ])->assertOk()->assertJson(['ok' => true]);

    expect($item->fresh()->extracted_json['data']['landlord'])->toBe('المؤجر الجديد');
});

it('casts numeric fields and rejects unknown fields on inline edit', function () {
    $user  = User::factory()->create();
    $item  = resItem(resBatch($user->id)->id);

    // رقم يُحفظ كعدد (مع إزالة الفواصل)
    $this->actingAs($user)->postJson(route('dashboard.rent.ai.item.field', $item->id), [
        'field' => 'rent_value', 'value' => '120,000',
    ])->assertOk();
    expect((float) $item->fresh()->extracted_json['data']['rent_value'])->toBe(120000.0);

    // حقل غير مسموح → رفض التحقق
    $this->actingAs($user)->postJson(route('dashboard.rent.ai.item.field', $item->id), [
        'field' => 'status', 'value' => 'approved',
    ])->assertStatus(422);
});

it('forbids a non-owner from viewing batch results', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create(['emp_job' => 0]);
    $batch = resBatch($owner->id);

    $this->actingAs($other)->get(route('dashboard.rent.ai.batch.results', $batch->id))->assertForbidden();
});
