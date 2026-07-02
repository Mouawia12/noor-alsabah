<?php

use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\User;
use App\Services\Ai\DuplicateInvoiceException;
use App\Services\Ai\PurchaseImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * يغطّي منع تكرار الفاتورة عند الحفظ (البند و/4).
 * جدول purchase قادم من Oracle ولا هجرة له، فننشئ نسخة مبسّطة له في SQLite.
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

it('rejects an invoice whose number already exists in purchases', function () {
    DB::table('purchase')->insert(['purchase_no' => 'INV-DUP-1', 'purchase_price' => 100]);

    $item = dupItem(dupBatch()->id, 'INV-DUP-1');

    expect(fn () => app(PurchaseImportService::class)->approveItem($item))
        ->toThrow(DuplicateInvoiceException::class);

    // لم تُنشأ فاتورة جديدة ولم يتغيّر الحالة إلى معتمدة
    expect(DB::table('purchase')->where('purchase_no', 'INV-DUP-1')->count())->toBe(1);
    expect($item->fresh()->status)->toBe('needs_review');
});

it('saves an invoice whose number is new', function () {
    $item = dupItem(dupBatch()->id, 'INV-NEW-9');

    $id = app(PurchaseImportService::class)->approveItem($item);

    expect($id)->toBeGreaterThan(0);
    expect(DB::table('purchase')->where('purchase_no', 'INV-NEW-9')->count())->toBe(1);
    expect($item->fresh()->status)->toBe('approved');
});
