<?php

use App\Jobs\ProcessPurchaseBatchJob;
use App\Jobs\ProcessRentContractBatchJob;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\RentContractImportBatch;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

/**
 * اختبار شاشة متابعة معالجة الفواتير/العقود بالـ AI (status.blade) بعد التقوية:
 * - endpoint الاستطلاع (batchJson) يُعيد كل الحقول التي تقرأها الواجهة (شرط عدم تعليقها).
 * - صفحة الحالة تصيّر البانرات الجديدة: نجاح واضح، فشل مع زر إعادة المحاولة، وبانر انقطاع الاتصال.
 * - زر «إعادة المحاولة» (نموذج POST) يستهدف مسار reprocess ويعيد جدولة المعالجة.
 * مستقل تماماً عن MySQL: يعمل على sqlite in-memory وطابور sync/مزيّف.
 */

// ---------- مساعدات إنشاء الدفعات ----------

function statusPurchaseBatch(int $ownerId, array $overrides = []): PurchaseImportBatch
{
    return PurchaseImportBatch::create(array_merge([
        'original_filename' => 'invoices.pdf',
        'file_path'         => 'purchase/batches/invoices.pdf',
        'file_hash'         => str_repeat('a', 64),
        'status'            => PurchaseImportBatch::STATUS_PROCESSING,
        'engine'            => 'openai',
        'create_user'       => $ownerId,
    ], $overrides));
}

function statusRentBatch(int $ownerId, array $overrides = []): RentContractImportBatch
{
    return RentContractImportBatch::create(array_merge([
        'original_filename' => 'contracts.pdf',
        'file_path'         => 'rent/batches/contracts.pdf',
        'file_hash'         => str_repeat('b', 64),
        'status'            => RentContractImportBatch::STATUS_PROCESSING,
        'engine'            => 'openai',
        'create_user'       => $ownerId,
    ], $overrides));
}

// ---------- عقد endpoint الاستطلاع (batchJson) ----------

it('purchase batchJson returns every field the frontend polls, including error_reason', function () {
    $user  = User::factory()->create();
    $batch = statusPurchaseBatch($user->id, [
        'status' => 'failed', 'total_items' => 3, 'processed_items' => 1,
        'failed_items' => 2, 'error_reason' => 'تعذّر تحويل الملف',
    ]);

    $this->actingAs($user)
        ->getJson(route('dashboard.purchase.ai.batch.json', $batch->id))
        ->assertOk()
        ->assertExactJson([
            'status'          => 'failed',
            'total_items'     => 3,
            'processed_items' => 1,
            'failed_items'    => 2,
            'error_reason'    => 'تعذّر تحويل الملف',
        ]);
});

it('rent batchJson returns every field the frontend polls', function () {
    $user  = User::factory()->create();
    $batch = statusRentBatch($user->id, [
        'status' => 'processing', 'total_items' => 4, 'processed_items' => 1, 'failed_items' => 0,
    ]);

    $this->actingAs($user)
        ->getJson(route('dashboard.rent.ai.batch.json', $batch->id))
        ->assertOk()
        ->assertJson([
            'status'          => 'processing',
            'total_items'     => 4,
            'processed_items' => 1,
            'failed_items'    => 0,
            'error_reason'    => null,
        ]);
});

// ---------- شاشة الحالة (blade): البانرات الجديدة موجودة وتُصرَّف بلا خطأ ----------
// ملاحظة: صفحة الحالة الكاملة تمتدّ layouts.app الذي يستعلم جداول صلاحيات من Oracle
// (لا هجرة لها في sqlite)، لذا نتحقّق من قالب الـ blade نفسه (تصريف + الوصلات) بدل تصييره كاملاً.

/** يتحقّق أن قالب blade يُصرَّف بلا أخطاء ويحتوي كل الوصلات المطلوبة. */
function assertBladeHas(string $relPath, array $needles): void
{
    $full = base_path($relPath);
    $src  = file_get_contents($full);
    // تصريف بلا استثناء = بنية blade سليمة
    app('blade.compiler')->compileString($src);
    foreach ($needles as $needle) {
        expect($src)->toContain($needle);
    }
}

it('purchase status blade drives realtime step with an animated, creeping progress bar', function () {
    assertBladeHas('resources/views/dashboard/purchase/ai/status.blade.php', [
        'id="doneBox"', 'id="failBox"', 'id="connBox"', 'id="sumTotal"',
        'تمت معالجة الفواتير بنجاح',            // بانر نجاح واضح بالعدد
        'إعادة المحاولة',                       // زر إعادة المحاولة
        "dashboard.purchase.ai.batch.reprocess", // نموذج الزر يستهدف مسار reprocess
        'dashboard.purchase.ai.batch.step',     // السائق يستدعي endpoint المعالجة اللحظية
        'progress-bar-animated',                // شريط متحرّك احترافي
        'function startCreep',                   // زحف سلس أثناء انتظار الاستخراج الطويل
        'capPct',                               // سقف العنصر الجاري
        "res.status === 401",                   // كشف انتهاء الجلسة بدل الدوران الصامت
        "addEventListener('pagehide'",          // تنظيف المؤقّت عند مغادرة الصفحة
    ]);
});

it('rent status blade drives realtime step with an animated, creeping progress bar', function () {
    assertBladeHas('resources/views/dashboard/rent/ai/status.blade.php', [
        'id="failBox"', 'id="connBox"', 'id="sumTotal"',
        'تمت معالجة العقود بنجاح',
        'إعادة المحاولة',
        "dashboard.rent.ai.batch.reprocess",
        'dashboard.rent.ai.batch.step',
        'progress-bar-animated',
        'function startCreep',
        'capPct',
        "addEventListener('pagehide'",
    ]);
});

it('failed status is fully covered by the batchJson contract (error_reason surfaced)', function () {
    // العرض المرئي لسبب الفشل يعتمد على error_reason القادم من batchJson — نضمن وصوله للواجهة.
    $user  = User::factory()->create();
    $batch = statusPurchaseBatch($user->id, [
        'status' => 'failed', 'error_reason' => 'الملف لا يحتوي فاتورة واضحة',
    ]);

    $this->actingAs($user)
        ->getJson(route('dashboard.purchase.ai.batch.json', $batch->id))
        ->assertOk()
        ->assertJson(['status' => 'failed', 'error_reason' => 'الملف لا يحتوي فاتورة واضحة']);
});

// ---------- زر إعادة المحاولة → مسار reprocess ----------

it('purchase retry (reprocessBatch) resets counters and redirects to the realtime processing page', function () {
    $user  = User::factory()->create();
    $batch = statusPurchaseBatch($user->id, [
        'status' => 'failed', 'total_items' => 2, 'processed_items' => 0,
        'failed_items' => 2, 'error_reason' => 'خطأ سابق',
    ]);
    // عنصر قديم يجب أن يُحذف عند إعادة المحاولة
    PurchaseImportItem::create([
        'batch_id' => $batch->id, 'status' => PurchaseImportItem::STATUS_FAILED,
        'page_from' => 1, 'page_to' => 1,
    ]);

    // إعادة المحاولة تُصفّر الدفعة وتحوّل لصفحة المتابعة التي تقود المعالجة لحظياً (بلا طابور)
    $this->actingAs($user)
        ->post(route('dashboard.purchase.ai.batch.reprocess', $batch->id))
        ->assertRedirect(route('dashboard.purchase.ai.batch', $batch->id));

    $batch->refresh();
    expect($batch->status)->toBe(PurchaseImportBatch::STATUS_PENDING);
    expect($batch->error_reason)->toBeNull();
    expect($batch->processed_items)->toBe(0);
    expect($batch->failed_items)->toBe(0);
    expect(PurchaseImportItem::where('batch_id', $batch->id)->count())->toBe(0);
});

it('rent retry (reprocessBatch) resets and redirects to the realtime processing page', function () {
    $user  = User::factory()->create();
    $batch = statusRentBatch($user->id, ['status' => 'failed', 'error_reason' => 'خطأ']);

    $this->actingAs($user)
        ->post(route('dashboard.rent.ai.batch.reprocess', $batch->id))
        ->assertRedirect(route('dashboard.rent.ai.batch', $batch->id));

    expect($batch->fresh()->status)->toBe(RentContractImportBatch::STATUS_PENDING);
});

// ---------- ضبط الوصول: زر إعادة المحاولة محميّ ----------

it('a non-owner cannot trigger reprocess on someone else batch', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create(['emp_job' => 0]);
    $batch = statusPurchaseBatch($owner->id, ['status' => 'failed']);

    $this->actingAs($other)
        ->post(route('dashboard.purchase.ai.batch.reprocess', $batch->id))
        ->assertForbidden();
});
