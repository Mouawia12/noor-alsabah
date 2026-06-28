<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Services\Ai\PdfService;
use App\Services\Ai\PurchaseImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * واجهة استيراد فواتير المشتريات بالذكاء الاصطناعي:
 * رفع → متابعة المعالجة → مراجعة واعتماد.
 */
class PurchaseAiController extends Controller
{
    use \App\Http\Controllers\Dashboard\Concerns\ExportsReports;
    use \App\Http\Controllers\Dashboard\Concerns\AuthorizesAiAccess;

    public function __construct(protected PurchaseImportService $importService) {}

    /** صفحة الرفع. */
    public function index()
    {
        $page_title = 'استيراد الفواتير بالذكاء الاصطناعي';
        $batches = PurchaseImportBatch::latest()->limit(10)->get();
        $imagickOk = PdfService::canRasterize();

        return view('dashboard.purchase.ai.upload', compact('page_title', 'batches', 'imagickOk'));
    }

    /** استقبال الملف وبدء المعالجة في الخلفية. */
    public function store(Request $request)
    {
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'], // 50MB
        ], [
            'document.required' => 'يرجى اختيار ملف.',
            'document.mimes'    => 'الملف يجب أن يكون PDF أو صورة (JPG/PNG).',
            'document.max'      => 'حجم الملف يتجاوز الحد المسموح (50 ميغابايت).',
        ]);

        $file = $request->file('document');
        $disk = config('ai.disk');
        $path = $file->store('purchase/batches', $disk);

        // تحذير من تكرار رفع نفس الملف
        $existing = $this->importService->findExistingByHash(Storage::disk($disk)->path($path));
        if ($existing) {
            return redirect()
                ->route('dashboard.purchase.ai.batch', $existing->id)
                ->with('alert.success', 'هذا الملف مرفوع مسبقاً — هذه نتيجة المعالجة السابقة.');
        }

        $batch = $this->importService->createBatch($path, $file->getClientOriginalName(), Auth::id());

        return redirect()
            ->route('dashboard.purchase.ai.batch', $batch->id)
            ->with('alert.success', 'بدأت المعالجة. يمكنك متابعة التقدّم هنا.');
    }

    /** صفحة متابعة دفعة. */
    public function batch(PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $page_title = 'متابعة معالجة الفواتير';
        $batch->load('items');

        return view('dashboard.purchase.ai.status', compact('page_title', 'batch'));
    }

    /** تقدّم الدفعة (JSON للـ polling). */
    public function batchJson(PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);

        return response()->json([
            'status'          => $batch->status,
            'total_items'     => $batch->total_items,
            'processed_items' => $batch->processed_items,
            'failed_items'    => $batch->failed_items,
            'error_reason'    => $batch->error_reason,
        ]);
    }

    /** قائمة العناصر التي تحتاج مراجعة. */
    public function review(Request $request)
    {
        $page_title = 'مراجعة واعتماد الفواتير المستخرجة';

        $query = PurchaseImportItem::with('batch')
            ->where('status', PurchaseImportItem::STATUS_NEEDS_REVIEW)
            ->latest();

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $items = $query->paginate(20);

        return view('dashboard.purchase.ai.review', compact('page_title', 'items'));
    }

    /** عرض صورة صفحة المستند الأصلي (من القرص الخاص) — للمراجعة. */
    public function image(PurchaseImportItem $item, int $page = 0)
    {
        $this->guardAiItem($item);
        $paths = array_values(array_filter(explode(',', (string) $item->source_file_path)));
        abort_if(empty($paths) || ! isset($paths[$page]) || ! is_file($paths[$page]), 404);

        return response()->file($paths[$page]);
    }

    /** العناصر الفاشلة + إعادة المعالجة. */
    public function failed()
    {
        $page_title = 'الفواتير غير المعالجة';
        $items = PurchaseImportItem::with('batch')
            ->where('status', PurchaseImportItem::STATUS_FAILED)
            ->latest()->paginate(20);
        $failedBatches = PurchaseImportBatch::where('status', PurchaseImportBatch::STATUS_FAILED)->latest()->get();

        return view('dashboard.purchase.ai.failed', compact('page_title', 'items', 'failedBatches'));
    }

    /** إعادة معالجة عنصر فاشل. */
    public function reprocess(PurchaseImportItem $item)
    {
        $this->guardAiItem($item);
        $item->update(['status' => PurchaseImportItem::STATUS_PENDING, 'error_reason' => null]);
        \App\Jobs\ProcessPurchaseItemJob::dispatch($item->id);

        return back()->with('alert.success', 'أُعيدت جدولة معالجة الفاتورة.');
    }

    /** إعادة معالجة دفعة فاشلة بالكامل. */
    public function reprocessBatch(PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $batch->items()->delete();
        $batch->update(['status' => PurchaseImportBatch::STATUS_PENDING, 'error_reason' => null, 'total_items' => 0, 'processed_items' => 0, 'failed_items' => 0]);
        \App\Jobs\ProcessPurchaseBatchJob::dispatch($batch->id);

        return back()->with('alert.success', 'أُعيدت جدولة معالجة الدفعة.');
    }

    /** تقارير وإحصائيات المعالجة. */
    public function reports()
    {
        $page_title = 'تقارير وإحصائيات المشتريات';

        $byStatus = PurchaseImportItem::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $approved = (int) ($byStatus['approved'] ?? 0);
        $rejected = (int) ($byStatus['rejected'] ?? 0);
        $failed   = (int) ($byStatus['failed'] ?? 0);
        $reviewed = $approved + $rejected;
        $successRate = $reviewed > 0 ? round($approved / $reviewed * 100) : 0;

        $stats = [
            'batches'      => PurchaseImportBatch::count(),
            'items'        => PurchaseImportItem::count(),
            'approved'     => $approved,
            'rejected'     => $rejected,
            'failed'       => $failed,
            'needs_review' => (int) ($byStatus['needs_review'] ?? 0),
            'success_rate' => $successRate,
            'total_amount' => DB::table('purchase')->whereNotNull('import_item_id')->sum('purchase_price'),
        ];

        $topSuppliers = DB::table('purchase as p')
            ->leftJoin('supplier as s', 's.supplier_id', '=', 'p.supplier_id')
            ->whereNotNull('p.import_item_id')
            ->selectRaw('COALESCE(s.name, "غير محدد") as name, count(*) c, SUM(p.purchase_price) total')
            ->groupBy('s.name')->orderByDesc('c')->limit(5)->get();

        return view('dashboard.purchase.ai.reports', compact('page_title', 'stats', 'topSuppliers'));
    }

    /** تصدير تقرير المشتريات (xlsx / pdf). */
    public function exportReports(Request $request)
    {
        $format = $request->input('format', 'xlsx');

        $byStatus = PurchaseImportItem::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $approved = (int) ($byStatus['approved'] ?? 0);
        $rejected = (int) ($byStatus['rejected'] ?? 0);
        $reviewed = $approved + $rejected;

        $rows = [
            ['المؤشر', 'القيمة'],
            ['عدد الدفعات', PurchaseImportBatch::count()],
            ['إجمالي الفواتير', PurchaseImportItem::count()],
            ['معتمدة', $approved],
            ['مرفوضة', $rejected],
            ['فاشلة', (int) ($byStatus['failed'] ?? 0)],
            ['بانتظار المراجعة', (int) ($byStatus['needs_review'] ?? 0)],
            ['نسبة الاعتماد %', $reviewed > 0 ? round($approved / $reviewed * 100) : 0],
            ['إجمالي قيمة المشتريات المستوردة', DB::table('purchase')->whereNotNull('import_item_id')->sum('purchase_price')],
        ];

        $top = DB::table('purchase as p')->leftJoin('supplier as s', 's.supplier_id', '=', 'p.supplier_id')
            ->whereNotNull('p.import_item_id')
            ->selectRaw('COALESCE(s.name, "غير محدد") name, count(*) c, SUM(p.purchase_price) total')
            ->groupBy('s.name')->orderByDesc('c')->limit(10)->get();

        return $this->exportData('تقرير_المشتريات', $rows, $top, ['المورد', 'عدد الفواتير', 'الإجمالي'], $format);
    }

    /** اعتماد عنصر وإنشاء سجل مشتريات. */
    public function approve(Request $request, PurchaseImportItem $item)
    {
        $overrides = $request->only([
            'invoice_no', 'invoice_date', 'tax_number', 'currency',
            'amount_before_tax', 'tax_amount', 'total', 'note',
            'supplier_id', 'new_supplier_name',
        ]);

        $this->guardAiItem($item);
        $purchaseId = $this->importService->approveItem($item, array_filter($overrides, fn ($v) => $v !== null && $v !== ''), Auth::id());

        return back()->with('alert.success', "تم اعتماد الفاتورة وإنشاء سجل المشتريات رقم {$purchaseId}.");
    }

    /** رفض عنصر. */
    public function reject(Request $request, PurchaseImportItem $item)
    {
        $this->guardAiItem($item);
        $this->importService->rejectItem($item, $request->input('reason'), Auth::id());

        return back()->with('alert.success', 'تم رفض الفاتورة.');
    }
}
