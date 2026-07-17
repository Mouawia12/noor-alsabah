<?php

use App\Jobs\ProcessRentContractItemJob;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Services\Ai\PageMergeService;

/**
 * يتحقّق أن العقد متعدّد الصفحات (نموذج REGA الموحّد الذي يكرّر رقم العقد في ترويسة كل صفحة،
 * وصفحة التواقيع/الملاحق) يُدمَج في بند مراجعة واحد لا عدّة بنود — مع بقاء العقود المختلفة منفصلة.
 */

function mergeBatch(): RentContractImportBatch
{
    return RentContractImportBatch::create([
        'original_filename' => 'contracts.pdf',
        'file_path'         => 'rent/batches/contracts.pdf',
        'file_hash'         => str_repeat('c', 64),
        'status'            => 'completed',
        'engine'            => 'openai',
        'create_user'       => 1,
    ]);
}

function mergeItem(int $batchId, int $page, array $data, string $file): RentContractImportItem
{
    return RentContractImportItem::create([
        'batch_id'         => $batchId,
        'page_from'        => $page,
        'page_to'          => $page,
        'status'           => RentContractImportItem::STATUS_NEEDS_REVIEW,
        'confidence'       => 0.95,
        'source_file_path' => $file,
        'extracted_json'   => ['data' => $data],
    ]);
}

function runMerge(int $batchId): int
{
    $items = RentContractImportItem::where('batch_id', $batchId)
        ->where('status', RentContractImportItem::STATUS_NEEDS_REVIEW)
        ->orderBy('page_from')->get();

    return app(PageMergeService::class)->merge(
        $items, ['contract_no'], ['rent_value', 'payment_amount'], RentContractImportItem::STATUS_MERGED
    );
}

it('merges a signature/continuation page (no id, no amount) into its contract', function () {
    $b = mergeBatch();
    $p1 = mergeItem($b->id, 1, ['contract_no' => 'C-1001', 'landlord' => 'مالك', 'rent_value' => 120000], 'p1.png');
    mergeItem($b->id, 2, ['tenant' => 'مستأجر'], 'p2.png'); // صفحة تواقيع بلا مُعرّف/مبلغ

    expect(runMerge($b->id))->toBe(1);
    expect(RentContractImportItem::where('status', 'needs_review')->count())->toBe(1);
    // الأب ضمّ صورة صفحة التكملة وامتدّ page_to
    expect($p1->fresh()->page_to)->toBe(2);
    expect($p1->fresh()->source_file_path)->toContain('p2.png');
});

it('merges a REGA page 2 that repeats the SAME contract_no into one document', function () {
    $b = mergeBatch();
    mergeItem($b->id, 1, ['contract_no' => 'REGA-77', 'landlord' => 'مالك', 'rent_value' => 90000], 'a1.png');
    // نموذج REGA: الترويسة تكرّر رقم العقد في الصفحة الثانية (قد يستخرجه الذكاء الاصطناعي)
    mergeItem($b->id, 2, ['contract_no' => 'REGA-77', 'tenant' => 'مستأجر', 'property_info' => 'محل 5'], 'a2.png');

    expect(runMerge($b->id))->toBe(1);
    expect(RentContractImportItem::where('status', 'needs_review')->count())->toBe(1);
});

it('keeps two DIFFERENT contracts as separate documents', function () {
    $b = mergeBatch();
    mergeItem($b->id, 1, ['contract_no' => 'C-1', 'rent_value' => 1000], 'x1.png');
    mergeItem($b->id, 2, ['contract_no' => 'C-2', 'rent_value' => 2000], 'x2.png');

    expect(runMerge($b->id))->toBe(2);
    expect(RentContractImportItem::where('status', 'needs_review')->count())->toBe(2);
});
