<?php

use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Models\User;

/**
 * تصدير قوائم المراجعة (فواتير/عقود) إلى PDF و Excel — أزرار التصدير في الشاشة.
 */
function exportUser(): User
{
    return User::factory()->create();
}

it('exports the purchase review list as Excel', function () {
    $user = exportUser();
    $batch = PurchaseImportBatch::create([
        'original_filename' => 'inv.pdf', 'file_path' => 'p/inv.pdf', 'file_hash' => str_repeat('e', 64),
        'status' => 'completed', 'engine' => 'openai', 'create_user' => $user->id,
    ]);
    PurchaseImportItem::create([
        'batch_id' => $batch->id, 'page_from' => 1, 'page_to' => 1, 'status' => 'needs_review', 'confidence' => 0.9,
        'extracted_json' => ['data' => ['invoice_no' => 'INV-1', 'supplier_name' => 'مورد', 'total' => 100, 'tax_number' => '300000000000003']],
    ]);

    $res = $this->actingAs($user)->get(route('dashboard.purchase.ai.review.export', ['format' => 'xlsx']));
    $res->assertOk();
    expect($res->headers->get('content-disposition'))->toContain('.xlsx');
});

it('exports the purchase review list as PDF', function () {
    $user = exportUser();
    $batch = PurchaseImportBatch::create([
        'original_filename' => 'inv.pdf', 'file_path' => 'p/inv.pdf', 'file_hash' => str_repeat('f', 64),
        'status' => 'completed', 'engine' => 'openai', 'create_user' => $user->id,
    ]);
    PurchaseImportItem::create([
        'batch_id' => $batch->id, 'page_from' => 1, 'page_to' => 1, 'status' => 'needs_review', 'confidence' => 0.9,
        'extracted_json' => ['data' => ['invoice_no' => 'INV-2', 'supplier_name' => 'مورد', 'total' => 200]],
    ]);

    $res = $this->actingAs($user)->get(route('dashboard.purchase.ai.review.export', ['format' => 'pdf']));
    $res->assertOk();
    expect($res->headers->get('content-type'))->toContain('pdf');
});

it('exports the rent review list as Excel', function () {
    $user = exportUser();
    $batch = RentContractImportBatch::create([
        'original_filename' => 'c.pdf', 'file_path' => 'r/c.pdf', 'file_hash' => str_repeat('g', 64),
        'status' => 'completed', 'engine' => 'openai', 'create_user' => $user->id,
    ]);
    RentContractImportItem::create([
        'batch_id' => $batch->id, 'page_from' => 1, 'page_to' => 1, 'status' => 'needs_review', 'confidence' => 0.95,
        'extracted_json' => ['data' => ['contract_no' => 'C-1', 'landlord' => 'المالك', 'tenant' => 'المستأجر', 'rent_value' => 60000, 'payments_count' => 12]],
    ]);

    $res = $this->actingAs($user)->get(route('dashboard.rent.ai.review.export', ['format' => 'xlsx']));
    $res->assertOk();
    expect($res->headers->get('content-disposition'))->toContain('.xlsx');
});

it('requires authentication to export', function () {
    $this->get(route('dashboard.purchase.ai.review.export', ['format' => 'xlsx']))->assertRedirect('/login');
});
