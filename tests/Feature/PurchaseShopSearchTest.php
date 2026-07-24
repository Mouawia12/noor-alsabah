<?php

use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\User;
use App\Services\Ai\PurchaseImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * البحث في «إدارة مصاريف شراء المحلات» بمحلٍّ رُحّلت إليه فاتورة (shops=on) — كان يُرجع 500
 * («غير واضحة» للعميل) لأن محل «تجربة» بلا مدينة (municip=null) يُسقط بناء الصف عند
 * $shop->municip->municip_no. الإصلاح: قراءة آمنة (optional).
 */

beforeEach(function () {
    // جداول legacy مستوردة من SQL — ننشئ المطلوب لاختبار الاستعلام على SQLite.
    if (! Schema::hasTable('purchase')) {
        Schema::create('purchase', function ($t) {
            $t->id('purchase_id');
            $t->string('purchase_no')->nullable();
            $t->string('purchase_dt')->nullable();
            $t->string('tax_number')->nullable();
            $t->decimal('purchase_price', 15, 2)->nullable();
            $t->unsignedBigInteger('shop_id')->nullable();
            $t->unsignedBigInteger('manager_id')->nullable();
            $t->string('manager_name')->nullable();
            $t->string('purchase_respon')->nullable();
            $t->string('purchasefile')->nullable();
            $t->text('note')->nullable();
            $t->unsignedBigInteger('import_item_id')->nullable();
            $t->unsignedBigInteger('supplier_id')->nullable();
            $t->timestamp('created_at')->nullable();
            $t->unsignedBigInteger('create_user')->nullable();
        });
    }
    if (! Schema::hasTable('shop')) {
        Schema::create('shop', function ($t) {
            $t->bigIncrements('shop_id');
            $t->string('shop_name')->nullable();
            $t->string('shop_code')->nullable();
            $t->string('shop_mobile')->nullable();
            $t->unsignedBigInteger('city_id')->nullable();
        });
    }
    if (! Schema::hasTable('shop_municip')) {
        Schema::create('shop_municip', function ($t) {
            $t->id();
            $t->unsignedBigInteger('shop_id')->nullable();
            $t->string('municip_no')->nullable();
        });
    }
    // الاستعلام القديم يعمل JOIN مع manager (والمستخدمين) — ننشئ manager الفارغ.
    if (! Schema::hasTable('manager')) {
        Schema::create('manager', function ($t) {
            $t->bigIncrements('manager_id');
            $t->string('manager_name')->nullable();
        });
    }
    foreach ([
        'permission' => ['role_id', 'emp_id', 'function_id'],
        'role_per' => ['role_id', 'function_id'], 'per_function' => ['parent_id'], 'per_controller' => [],
    ] as $table => $cols) {
        if (! Schema::hasTable($table)) {
            Schema::create($table, function ($t) use ($cols) {
                $t->id();
                foreach ($cols as $c) { $t->unsignedBigInteger($c)->nullable(); }
            });
        }
    }
});

/** ينفّذ بحث المشتريات بالمحل (shops=on) عبر HTTP ويُعيد ناتج DataTables. */
function runShopSearch($test, User $admin, int $shopId): array
{
    $_POST['start'] = 0; $_POST['length'] = 25; $_POST['draw'] = 1;
    ob_start();
    $res = $test->actingAs($admin)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest']) // الكود القديم يشترط $request->ajax()
        ->post(route('dashboard.purchase.ajax_search_purchase'), [
            'shop_id' => $shopId, 'shops' => 'on', 'start' => 0, 'length' => 25, 'draw' => 1,
        ]);
    $echoed = ob_get_clean();
    unset($_POST['start'], $_POST['length'], $_POST['draw']);
    $res->assertOk();

    return [$res, json_decode($echoed !== '' ? $echoed : $res->getContent(), true)];
}

it('does NOT crash when searching a shop that has NO city (municip null) — the reported 500', function () {
    $admin = User::factory()->create(['emp_job' => 1]);

    // محل «تجربة» بلا مدينة (لا صفّ في municip)
    DB::table('shop')->insert(['shop_id' => 281, 'shop_name' => 'تجربة', 'shop_mobile' => '0500000000']);
    // فاتورة مُرحّلة إلى هذا المحل (كما يحفظها اعتماد الـAI: shop_id مضبوط، manager_id null)
    DB::table('purchase')->insert([
        'purchase_no' => 'INV-T1', 'purchase_dt' => '2026-07-20', 'purchase_price' => 115,
        'shop_id' => 281, 'manager_id' => null, 'purchase_respon' => 'مورد', 'create_user' => $admin->id,
        'created_at' => now(),
    ]);

    [$res, $json] = runShopSearch($this, $admin, 281);

    expect($json)->toBeArray();
    expect((int) $json['recordsTotal'])->toBe(1);           // الفاتورة تظهر (كانت 500 قبل الإصلاح)
    $rowText = json_encode($json['data'], JSON_UNESCAPED_UNICODE);
    expect($rowText)->toContain('تجربة');                    // اسم المحل بلا « - » للمدينة الغائبة
    expect($rowText)->toContain('INV-T1');
});

it('shows the city suffix when the shop has a municip', function () {
    $admin = User::factory()->create(['emp_job' => 1]);
    DB::table('shop')->insert(['shop_id' => 300, 'shop_name' => 'فرع الرياض', 'shop_mobile' => '0500000000']);
    DB::table('shop_municip')->insert(['shop_id' => 300, 'municip_no' => '39121528024']);
    DB::table('purchase')->insert([
        'purchase_no' => 'INV-C', 'purchase_dt' => '2026-07-20', 'purchase_price' => 200,
        'shop_id' => 300, 'manager_id' => null, 'create_user' => $admin->id, 'created_at' => now(),
    ]);

    [$res, $json] = runShopSearch($this, $admin, 300);

    expect((int) $json['recordsTotal'])->toBe(1);
    expect(json_encode($json['data'], JSON_UNESCAPED_UNICODE))->toContain('39121528024');
});

it('does not crash when the invoice creator user was deleted', function () {
    $admin = User::factory()->create(['emp_job' => 1]);
    DB::table('shop')->insert(['shop_id' => 281, 'shop_name' => 'تجربة', 'shop_mobile' => '05']);
    DB::table('purchase')->insert([
        'purchase_no' => 'INV-U', 'purchase_dt' => '2026-07-20', 'purchase_price' => 100,
        'shop_id' => 281, 'manager_id' => null, 'create_user' => 999999, 'created_at' => now(), // مستخدم غير موجود
    ]);

    [$res, $json] = runShopSearch($this, $admin, 281);
    expect((int) $json['recordsTotal'])->toBe(1); // لا 500 رغم غياب المستخدم
});

it('AI transfer stores manager_id as NULL so the invoice matches the shop search', function () {
    // نثبت أن الاعتماد يضبط manager_id=null صراحةً (شرط ظهور الفاتورة في بحث المحل)
    $svc = new ReflectionClass(PurchaseImportService::class);
    $src = file_get_contents($svc->getFileName());
    expect($src)->toContain("'manager_id'        => null");
});
