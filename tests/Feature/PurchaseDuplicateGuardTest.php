<?php

use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\User;
use App\Services\Ai\DuplicateInvoiceException;
use App\Services\Ai\PurchaseImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * منع تكرار الفاتورة عند الترحيل — القاعدة الجديدة: تُعتبر مكرّرة فقط عند تطابق
 * (رقم الفاتورة + المورّد)، مع الفرع كقيد اختياري. رقم الفاتورة وحده لا يكفي
 * (كان يسبب رفضاً كاذباً لأغلب الفواتير). جدول purchase مستورد من MySQL ولا هجرة
 * له في الاختبار، فننشئ نسخة مبسّطة له في SQLite.
 */
beforeEach(function () {
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
        $t->string('purchase_respon')->nullable();
        $t->unsignedBigInteger('shop_id')->nullable();
        $t->string('purchasefile')->nullable();
        $t->text('note')->nullable();
        $t->unsignedBigInteger('import_item_id')->nullable();
        $t->timestamp('created_at')->nullable();
        $t->unsignedBigInteger('create_user')->nullable();
    });
});

function dupBatch(): PurchaseImportBatch
{
    $u = User::factory()->create();
    return PurchaseImportBatch::create([
        'original_filename' => 'inv.pdf', 'file_path' => 'purchase/batches/inv.pdf',
        'file_hash' => str_repeat('d', 64), 'status' => 'completed',
        'engine' => 'openai', 'create_user' => $u->id,
    ]);
}

function dupItem(int $batchId, string $invoiceNo): PurchaseImportItem
{
    return PurchaseImportItem::create([
        'batch_id' => $batchId, 'page_from' => 1, 'page_to' => 1, 'status' => 'needs_review',
        'confidence' => 0.95,
        'extracted_json' => ['data' => ['invoice_no' => $invoiceNo, 'supplier_name' => 'مورد الاختبار', 'total' => 1150]],
    ]);
}

it('rejects a real duplicate: same invoice number AND same supplier', function () {
    DB::table('purchase')->insert(['purchase_no' => 'INV-DUP-1', 'supplier_id' => 7, 'purchase_price' => 100]);

    $item = dupItem(dupBatch()->id, 'INV-DUP-1');

    expect(fn () => app(PurchaseImportService::class)->approveItem($item, ['supplier_id' => 7]))
        ->toThrow(DuplicateInvoiceException::class);

    // لم تُنشأ فاتورة جديدة ولم تتغيّر الحالة إلى معتمدة
    expect(DB::table('purchase')->where('purchase_no', 'INV-DUP-1')->count())->toBe(1);
    expect($item->fresh()->status)->toBe('needs_review');
});

it('ALLOWS the same invoice number for a DIFFERENT supplier (the false-positive fix)', function () {
    DB::table('purchase')->insert(['purchase_no' => 'INV-DUP-1', 'supplier_id' => 7, 'purchase_price' => 100]);

    $item = dupItem(dupBatch()->id, 'INV-DUP-1');

    // نفس رقم الفاتورة لكن مورّد مختلف → يجب أن يُرحَّل بلا استثناء (كان يُرفض سابقاً)
    $id = app(PurchaseImportService::class)->approveItem($item, ['supplier_id' => 8]);

    expect($id)->toBeGreaterThan(0);
    expect(DB::table('purchase')->where('purchase_no', 'INV-DUP-1')->count())->toBe(2);
    expect($item->fresh()->status)->toBe('approved');
});

it('does NOT block when the supplier cannot be determined (no single-field rejection)', function () {
    DB::table('purchase')->insert(['purchase_no' => 'INV-DUP-1', 'supplier_id' => 7, 'purchase_price' => 100]);

    $item = dupItem(dupBatch()->id, 'INV-DUP-1');

    // بلا مورّد محدَّد → لا يُجرى فحص التكرار، فيُرحَّل
    $id = app(PurchaseImportService::class)->approveItem($item);

    expect($id)->toBeGreaterThan(0);
    expect($item->fresh()->status)->toBe('approved');
});

it('treats a different branch as NOT a duplicate, and the same branch as a duplicate', function () {
    DB::table('purchase')->insert(['purchase_no' => 'INV-B', 'supplier_id' => 5, 'shop_id' => 1, 'purchase_price' => 50]);

    // نفس الرقم + نفس المورّد لكن فرع مختلف → يُرحَّل
    $itemOther = dupItem(dupBatch()->id, 'INV-B');
    $id = app(PurchaseImportService::class)->approveItem($itemOther, ['supplier_id' => 5, 'shop_id' => 2]);
    expect($id)->toBeGreaterThan(0);

    // نفس الرقم + نفس المورّد + نفس الفرع → مكرّر
    $itemSame = dupItem(dupBatch()->id, 'INV-B');
    expect(fn () => app(PurchaseImportService::class)->approveItem($itemSame, ['supplier_id' => 5, 'shop_id' => 1]))
        ->toThrow(DuplicateInvoiceException::class);
});

it('includes the invoice number and existing record id in the duplicate message', function () {
    $existingId = DB::table('purchase')->insertGetId(['purchase_no' => 'INV-MSG', 'supplier_id' => 3, 'purchase_price' => 10]);

    $item = dupItem(dupBatch()->id, 'INV-MSG');

    try {
        app(PurchaseImportService::class)->approveItem($item, ['supplier_id' => 3]);
        $this->fail('توقّعنا رمي DuplicateInvoiceException');
    } catch (DuplicateInvoiceException $e) {
        expect($e->getMessage())->toContain('INV-MSG');
        expect($e->getMessage())->toContain((string) $existingId);
    }
});

it('saves an invoice whose (number + supplier) combo is new', function () {
    DB::table('purchase')->insert(['purchase_no' => 'INV-DUP-1', 'supplier_id' => 7, 'purchase_price' => 100]);

    $item = dupItem(dupBatch()->id, 'INV-NEW-9');

    $id = app(PurchaseImportService::class)->approveItem($item, ['supplier_id' => 7]);

    expect($id)->toBeGreaterThan(0);
    expect(DB::table('purchase')->where('purchase_no', 'INV-NEW-9')->count())->toBe(1);
    expect($item->fresh()->status)->toBe('approved');
    // اسم المورد يُحفظ في purchase_respon ليظهر في شاشة مصاريف شراء المحلات
    expect(DB::table('purchase')->where('purchase_no', 'INV-NEW-9')->value('purchase_respon'))->toBe('مورد الاختبار');
});
