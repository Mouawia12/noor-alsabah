<?php

use App\Models\AiAuditLog;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Support\AiDashboardStats;
use Illuminate\Support\Facades\Cache;

/**
 * مؤشّرات لوحة الذكاء الاصطناعي: العدّ الصحيح حسب الحالة + التخزين المؤقت وإبطاله.
 */
function statBatch(): PurchaseImportBatch
{
    $u = App\Models\User::factory()->create();
    return PurchaseImportBatch::create([
        'original_filename' => 'x.pdf', 'file_path' => 'p/x.pdf', 'file_hash' => str_repeat('c', 64),
        'status' => 'completed', 'engine' => 'openai', 'create_user' => $u->id,
    ]);
}

function statItem(int $batchId, string $status): void
{
    PurchaseImportItem::create(['batch_id' => $batchId, 'page_from' => 1, 'page_to' => 1, 'status' => $status]);
}

beforeEach(fn () => Cache::forget(AiDashboardStats::CACHE_KEY));

it('computes purchase KPIs from item statuses', function () {
    $b = statBatch();
    statItem($b->id, 'approved');       // مُرحّلة
    statItem($b->id, 'approved');
    statItem($b->id, 'needs_review');   // بانتظار
    statItem($b->id, 'failed');         // مرفوضة
    statItem($b->id, 'rejected');       // مرفوضة

    $s = AiDashboardStats::get()['purchase'];

    expect($s['processed'])->toBe(5);
    expect($s['transferred'])->toBe(2);
    expect($s['pending'])->toBe(1);
    expect($s['rejected'])->toBe(2);
    expect($s['accepted'])->toBe(3);            // approved(2) + needs_review(1)
    expect($s['success_rate'])->toBe(60);       // 3/5
    expect($s['batches'])->toBe(1);
});

it('caches the stats and forget() invalidates them', function () {
    $b = statBatch();
    statItem($b->id, 'approved');
    expect(AiDashboardStats::get()['purchase']['processed'])->toBe(1);

    // إضافة عنصر جديد لا يظهر ما دام الكاش حيّاً
    statItem($b->id, 'approved');
    expect(AiDashboardStats::get()['purchase']['processed'])->toBe(1);

    // بعد الإبطال يظهر العدد المحدّث
    AiDashboardStats::forget();
    expect(AiDashboardStats::get()['purchase']['processed'])->toBe(2);
});

it('presents recent activity from the audit log', function () {
    AiAuditLog::record('purchase_item', 1, 'approved', [], null);
    AiAuditLog::record('rent_batch', 2, 'completed', [], null);

    $activity = AiDashboardStats::get()['activity'];

    expect($activity)->toHaveCount(2);
    expect(collect($activity)->pluck('label')->implode(' | '))
        ->toContain('ترحيل فاتورة')
        ->toContain('اكتملت معالجة دفعة عقود');
});
