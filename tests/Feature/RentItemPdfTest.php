<?php

use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Models\User;

/**
 * زر «تنزيل PDF» بجانب مراجعة العقد: يبني ملف PDF من صور صفحات العنصر ويُنزّله،
 * مع حماية الوصول (غير المالك ممنوع).
 */

/** يولّد صورة PNG صالحة صغيرة على القرص عبر GD ويُعيد مسارها (يحتاجها TCPDF). */
function tmpPng(string $name): string
{
    $path = sys_get_temp_dir() . '/' . $name;
    $im = imagecreatetruecolor(4, 6); // عمودي (يحاكي صفحة عقد)
    imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));
    imagepng($im, $path);
    imagedestroy($im);
    return $path;
}

function pdfItem(int $ownerId, array $pages): RentContractImportItem
{
    $batch = RentContractImportBatch::create([
        'original_filename' => 'contracts.pdf',
        'file_path'         => 'rent/batches/contracts.pdf',
        'file_hash'         => str_repeat('d', 64),
        'status'            => 'completed',
        'engine'            => 'openai',
        'create_user'       => $ownerId,
    ]);

    return RentContractImportItem::create([
        'batch_id'         => $batch->id,
        'page_from'        => 1,
        'page_to'          => count($pages),
        'status'           => RentContractImportItem::STATUS_NEEDS_REVIEW,
        'confidence'       => 0.95,
        'source_file_path' => implode(',', $pages),
        'extracted_json'   => ['data' => ['contract_no' => 'C-PDF-1']],
    ]);
}

it('downloads the contract pages as a single PDF', function () {
    $user = User::factory()->create();
    $item = pdfItem($user->id, [tmpPng('rpdf_a.png'), tmpPng('rpdf_b.png')]);

    $res = $this->actingAs($user)->get(route('dashboard.rent.ai.item.pdf', $item->id));

    $res->assertOk();
    expect($res->headers->get('content-type'))->toContain('application/pdf');
    // توقيع ملف PDF في بداية المحتوى
    expect(substr($res->streamedContent ?? $res->getContent(), 0, 4))->toBe('%PDF');
});

it('404s when the item has no page images on disk', function () {
    $user = User::factory()->create();
    $item = pdfItem($user->id, ['/tmp/does-not-exist-xyz.png']);

    $this->actingAs($user)->get(route('dashboard.rent.ai.item.pdf', $item->id))->assertNotFound();
});

it('forbids a non-owner from downloading the contract PDF', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create(['emp_job' => 0]);
    $item  = pdfItem($owner->id, [tmpPng('rpdf_c.png')]);

    $this->actingAs($other)->get(route('dashboard.rent.ai.item.pdf', $item->id))->assertForbidden();
});
