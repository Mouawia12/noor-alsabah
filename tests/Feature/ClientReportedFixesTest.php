<?php

use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * ملاحظات العميل الثلاث — مُثبتة باختبارات فعلية:
 * (1) حذف الفواتير قبل الترحيل ⇒ يُسمح برفع نفس الملف مجدداً؛ وبقاؤها/ترحيلها ⇒ يُرفض كمكرر.
 * (2) تسجيل سداد دفعة ينعكس في شاشة الدفعات (حالة/مدفوع/متبقٍّ/تاريخ السداد).
 * (3) مرفق الفاتورة المُرحّلة يُفتح (لا «غير متوفر»/404) لأنه على القرص الخاص.
 */

beforeEach(function () {
    Storage::fake(config('ai.disk'));

    if (! Schema::hasTable('purchase')) {
        Schema::create('purchase', function ($t) {
            $t->id('purchase_id');
            $t->string('purchase_no')->nullable();
            $t->string('purchasefile')->nullable();
            $t->unsignedBigInteger('import_item_id')->nullable();
        });
    }
});

// ---------- (1) التكرار: الحذف قبل الترحيل يعيد السماح ----------

it('ALLOWS re-uploading the same file after its invoices were deleted before transfer', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('invoices.pdf', 120, 'application/pdf');

    // رفع أول → تُنشأ دفعة
    $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'), ['document' => $file])->assertOk();
    $batch = PurchaseImportBatch::first();
    expect($batch)->not->toBeNull();

    // فاتورة بانتظار المراجعة ثم يحذفها المستخدم قبل الترحيل
    $item = PurchaseImportItem::create([
        'batch_id' => $batch->id, 'page_from' => 1, 'page_to' => 1,
        'status' => PurchaseImportItem::STATUS_NEEDS_REVIEW,
    ]);
    $this->actingAs($user)->postJson(route('dashboard.purchase.ai.destroy', $item->id))->assertOk();
    expect(PurchaseImportItem::where('batch_id', $batch->id)->count())->toBe(0);

    // إعادة رفع نفس الملف → يجب أن يُقبل (لا «مكرر») وتُنشأ دفعة جديدة
    $again = UploadedFile::fake()->create('invoices.pdf', 120, 'application/pdf');
    $res = $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'), ['document' => $again]);

    $res->assertOk()->assertJson(['ok' => true]);
    expect(PurchaseImportBatch::count())->toBe(2);
});

it('REJECTS re-uploading the same file while its invoices still exist (e.g. transferred)', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('invoices.pdf', 120, 'application/pdf');

    $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'), ['document' => $file])->assertOk();
    $batch = PurchaseImportBatch::first();

    // فاتورة مُرحّلة (معتمدة) تبقى في النظام
    PurchaseImportItem::create([
        'batch_id' => $batch->id, 'page_from' => 1, 'page_to' => 1,
        'status' => PurchaseImportItem::STATUS_APPROVED, 'purchase_id' => 77,
    ]);

    $again = UploadedFile::fake()->create('invoices.pdf', 120, 'application/pdf');
    $this->actingAs($user)->postJson(route('dashboard.purchase.ai.store'), ['document' => $again])
        ->assertStatus(409)->assertJson(['duplicate' => true]);

    expect(PurchaseImportBatch::count())->toBe(1); // لم تُنشأ دفعة ثانية
});

it('applies the same delete-then-reupload rule to rent contracts', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('leases.pdf', 120, 'application/pdf');

    $this->actingAs($user)->postJson(route('dashboard.rent.ai.store'), ['document' => $file])->assertOk();
    $batch = RentContractImportBatch::first();
    $item = RentContractImportItem::create([
        'batch_id' => $batch->id, 'page_from' => 1, 'page_to' => 1,
        'status' => RentContractImportItem::STATUS_NEEDS_REVIEW,
    ]);

    // ما دام العقد قائماً → مكرر
    $this->actingAs($user)->postJson(route('dashboard.rent.ai.store'),
        ['document' => UploadedFile::fake()->create('leases.pdf', 120, 'application/pdf')])->assertStatus(409);

    // بعد حذفه → يُقبل
    $item->delete();
    $this->actingAs($user)->postJson(route('dashboard.rent.ai.store'),
        ['document' => UploadedFile::fake()->create('leases.pdf', 120, 'application/pdf')])->assertOk();

    expect(RentContractImportBatch::count())->toBe(2);
});

// ---------- (3) مرفق الفاتورة المُرحّلة يُفتح ----------

it('serves the transferred invoice attachment from the private disk (no 404)', function () {
    $user = User::factory()->create();

    // فاتورة مُرحّلة بالـAI: purchasefile يحمل مسار القرص الخاص (كما يحفظه الاعتماد)
    $rel = 'purchase/batches/scan.pdf';
    Storage::disk(config('ai.disk'))->put($rel, '%PDF-1.4 fake');

    $purchaseId = DB::table('purchase')->insertGetId([
        'purchase_no' => 'INV-1', 'purchasefile' => $rel, 'import_item_id' => null,
    ]);

    $res = $this->actingAs($user)->get(route('dashboard.purchase.attachment', $purchaseId));

    $res->assertOk(); // كان 404 «غير متوفر» قبل الإصلاح
    expect($res->headers->get('content-type'))->toContain('application/pdf');
});

it('serves the attachment from the import item page image when available', function () {
    $user  = User::factory()->create();
    $batch = PurchaseImportBatch::create([
        'original_filename' => 'scan.pdf', 'file_path' => 'purchase/batches/scan.pdf',
        'file_hash' => str_repeat('h', 64), 'status' => 'completed', 'engine' => 'openai',
        'create_user' => $user->id,
    ]);
    $img = sys_get_temp_dir() . '/att_page.png';
    file_put_contents($img, 'png-bytes');
    $item = PurchaseImportItem::create([
        'batch_id' => $batch->id, 'page_from' => 1, 'page_to' => 1,
        'status' => PurchaseImportItem::STATUS_APPROVED, 'source_file_path' => $img,
    ]);
    $purchaseId = DB::table('purchase')->insertGetId([
        'purchase_no' => 'INV-2', 'purchasefile' => 'purchase/batches/scan.pdf', 'import_item_id' => $item->id,
    ]);

    $this->actingAs($user)->get(route('dashboard.purchase.attachment', $purchaseId))->assertOk();
});

it('returns a clear 404 only when the attachment truly does not exist', function () {
    $user = User::factory()->create();
    $purchaseId = DB::table('purchase')->insertGetId([
        'purchase_no' => 'INV-3', 'purchasefile' => 'purchase/batches/missing.pdf',
    ]);

    $this->actingAs($user)->get(route('dashboard.purchase.attachment', $purchaseId))->assertNotFound();
});

// ---------- (2) السداد ينعكس في شاشة الدفعات ----------

it('a recorded payment reflects in the payments screen row (status/paid/remaining/date)', function () {
    \Tests\Support\RentTestSchema::install();
    $admin = User::factory()->create(['emp_job' => 1]); // emp_job=1 يتجاوز الصلاحيات

    $rentpayId = DB::table('shop_rentpay')->insertGetId([
        'shop_id' => 5, 'shop_rent_id' => 1, 'seq_no' => 1,
        'rentpay_dt' => now()->addDays(5)->toDateString(),
        'rentpay_price' => 10000, 'paid_amount' => 0, 'status' => 'unpaid', 'is_paid' => false,
    ], 'rentpay_id');

    // المستخدم يسجّل السداد من شاشة متابعة السداد
    $this->actingAs($admin)->postJson(route('dashboard.rent.pay.record', $rentpayId), [
        'amount' => 10000, 'method' => 'نقدي',
    ])->assertOk()->assertJson(['ok' => true]);

    // الأعمدة التي تقرأها شاشة الدفعات صارت محدّثة
    $row = DB::table('shop_rentpay')->where('rentpay_id', $rentpayId)->first();
    expect($row->status)->toBe('paid');
    expect((float) $row->paid_amount)->toBe(10000.0);
    expect($row->paid_at)->not->toBeNull();

    // وشاشة الدفعات (DataTables القديمة) تُظهر الحالة والمبالغ فعلياً
    // الشاشة القديمة (DataTables) تقرأ $_POST وتطبع الناتج بـ echo → نلتقط المخرجات
    $_POST['start'] = 0; $_POST['length'] = 10; $_POST['draw'] = 1;
    ob_start();
    $res = $this->actingAs($admin)->post(route('dashboard.shop.ajax_search_rentpay'), [
        'shop_id' => 5, 'start' => 0, 'length' => 10, 'draw' => 1,
    ]);
    $echoed = ob_get_clean();
    unset($_POST['start'], $_POST['length'], $_POST['draw']);

    $res->assertOk();
    $raw = $echoed !== '' ? $echoed : $res->getContent();
    $decoded = json_decode($raw, true);
    expect($decoded)->toBeArray();
    $body = json_encode($decoded, JSON_UNESCAPED_UNICODE);
    expect($body)->toContain('مسدَّدة');   // شارة الحالة تظهر في الصف
    expect($body)->toContain('10,000.00'); // المبلغ المدفوع يظهر في الصف
});

it('the payments screen table declares the payment status columns', function () {
    $blade = file_get_contents(base_path('resources/views/dashboard/shop/tbl_rentpay.blade.php'));
    foreach (['حالة السداد', 'المدفوع', 'المتبقّي', 'تاريخ السداد'] as $col) {
        expect($blade)->toContain($col);
    }
});
