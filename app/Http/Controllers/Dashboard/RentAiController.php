<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use App\Services\Ai\PdfService;
use App\Services\Ai\RentImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * واجهة استيراد عقود الإيجار بالذكاء الاصطناعي:
 * رفع → متابعة → مراجعة → اعتماد (إنشاء عقد + توليد دفعات).
 */
class RentAiController extends Controller
{
    use \App\Http\Controllers\Dashboard\Concerns\ExportsReports;
    use \App\Http\Controllers\Dashboard\Concerns\AuthorizesAiAccess;

    public function __construct(protected RentImportService $importService) {}

    public function index()
    {
        $page_title = 'استيراد عقود الإيجار بالذكاء الاصطناعي';
        $batches = RentContractImportBatch::latest()->limit(10)->get();
        $imagickOk = PdfService::canRasterize();

        return view('dashboard.rent.ai.upload', compact('page_title', 'batches', 'imagickOk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
        ], [
            'document.required' => 'يرجى اختيار ملف.',
            'document.mimes'    => 'الملف يجب أن يكون PDF أو صورة (JPG/PNG).',
            'document.max'      => 'حجم الملف يتجاوز الحد المسموح (50 ميغابايت).',
        ]);

        $file = $request->file('document');
        $disk = config('ai.disk');
        $path = $file->store('rent/batches', $disk);

        $existing = $this->importService->findExistingByHash(Storage::disk($disk)->path($path));
        if ($existing) {
            return $this->uploadResponse($request, $existing->id, 'هذا الملف مرفوع مسبقاً — هذه نتيجة المعالجة السابقة.');
        }

        $batch = $this->importService->createBatch($path, $file->getClientOriginalName(), Auth::id());

        return $this->uploadResponse($request, $batch->id, 'بدأت المعالجة. يمكنك متابعة التقدّم هنا.');
    }

    /** استجابة الرفع: JSON عند AJAX (لمتابعة التقدّم بلا إعادة تحميل)، وإلا تحويل عادي. */
    protected function uploadResponse(Request $request, int $batchId, string $message)
    {
        $url = route('dashboard.rent.ai.batch', $batchId);
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['ok' => true, 'redirect' => $url, 'message' => $message]);
        }

        return redirect()->to($url)->with('alert.success', $message);
    }

    public function batch(RentContractImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $page_title = 'متابعة معالجة العقود';
        $batch->load('items');

        return view('dashboard.rent.ai.status', compact('page_title', 'batch'));
    }

    public function batchJson(RentContractImportBatch $batch)
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

    public function review(Request $request)
    {
        $page_title = 'مراجعة واعتماد العقود المستخرجة';

        $query = RentContractImportItem::with('batch')->needsReviewOrdered();

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $items = $query->paginate(20)->withQueryString();
        $shops = DB::table('shop')->orderBy('shop_name')->get(['shop_id', 'shop_name', 'shop_code']);

        // طلب AJAX (إعادة جلب بعد اعتماد/رفض/تنقّل) → نُعيد جزء الجدول فقط.
        if ($request->ajax()) {
            return response()->json([
                'html'  => view('dashboard.rent.ai._review_list', compact('items', 'shops'))->render(),
                'count' => $items->total(),
            ]);
        }

        return view('dashboard.rent.ai.review', compact('page_title', 'items', 'shops'));
    }

    /** عرض صورة صفحة العقد الأصلي (من القرص الخاص). */
    public function image(RentContractImportItem $item, int $page = 0)
    {
        $this->guardAiItem($item);
        $paths = array_values(array_filter(explode(',', (string) $item->source_file_path)));
        abort_if(empty($paths) || ! isset($paths[$page]) || ! is_file($paths[$page]), 404);

        return response()->file($paths[$page]);
    }

    public function failed()
    {
        $page_title = 'العقود غير المعالجة';
        $items = RentContractImportItem::with('batch')
            ->where('status', RentContractImportItem::STATUS_FAILED)
            ->latest()->paginate(20);
        $failedBatches = RentContractImportBatch::where('status', RentContractImportBatch::STATUS_FAILED)->latest()->get();

        return view('dashboard.rent.ai.failed', compact('page_title', 'items', 'failedBatches'));
    }

    public function reprocess(RentContractImportItem $item)
    {
        $this->guardAiItem($item);
        $item->update(['status' => RentContractImportItem::STATUS_PENDING, 'error_reason' => null]);
        \App\Jobs\ProcessRentContractItemJob::dispatch($item->id);

        return back()->with('alert.success', 'أُعيدت جدولة معالجة العقد.');
    }

    public function reprocessBatch(RentContractImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $batch->items()->delete();
        $batch->update(['status' => RentContractImportBatch::STATUS_PENDING, 'error_reason' => null, 'total_items' => 0, 'processed_items' => 0, 'failed_items' => 0]);
        \App\Jobs\ProcessRentContractBatchJob::dispatch($batch->id);

        return back()->with('alert.success', 'أُعيدت جدولة معالجة الدفعة.');
    }

    public function reports()
    {
        $page_title = 'تقارير وإحصائيات الإيجارات';
        $today = now()->toDateString();

        $byStatus = RentContractImportItem::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');

        $stats = [
            'batches'        => RentContractImportBatch::count(),
            'items'          => RentContractImportItem::count(),
            'approved'       => (int) ($byStatus['approved'] ?? 0),
            'needs_review'   => (int) ($byStatus['needs_review'] ?? 0),
            'failed'         => (int) ($byStatus['failed'] ?? 0),
            'contracts'      => DB::table('shop_rent')->whereNotNull('import_item_id')->count(),
            'active'         => DB::table('shop_rent')->whereNotNull('end_date')->where('end_date', '>=', $today)->count(),
            'expired'        => DB::table('shop_rent')->whereNotNull('end_date')->where('end_date', '<', $today)->count(),
            'payments'       => DB::table('shop_rentpay')->count(),
            'unpaid_amount'  => DB::table('shop_rentpay')->where('is_paid', 0)->sum('rentpay_price'),
        ];

        return view('dashboard.rent.ai.reports', compact('page_title', 'stats'));
    }

    /** تصدير تقرير الإيجارات (xlsx / pdf). */
    public function exportReports(Request $request)
    {
        $format = $request->input('format', 'xlsx');
        $today = now()->toDateString();
        $byStatus = RentContractImportItem::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');

        $rows = [
            ['المؤشر', 'القيمة'],
            ['عقود مستوردة', DB::table('shop_rent')->whereNotNull('import_item_id')->count()],
            ['عقود نشطة', DB::table('shop_rent')->whereNotNull('end_date')->where('end_date', '>=', $today)->count()],
            ['عقود منتهية', DB::table('shop_rent')->whereNotNull('end_date')->where('end_date', '<', $today)->count()],
            ['عقود معالَجة', RentContractImportItem::count()],
            ['معتمدة', (int) ($byStatus['approved'] ?? 0)],
            ['بانتظار المراجعة', (int) ($byStatus['needs_review'] ?? 0)],
            ['فاشلة', (int) ($byStatus['failed'] ?? 0)],
            ['دفعات مولّدة', DB::table('shop_rentpay')->count()],
            ['إجمالي غير المسدَّد', DB::table('shop_rentpay')->where('is_paid', 0)->sum('rentpay_price')],
        ];

        return $this->exportData('تقرير_الإيجارات', $rows, [], [], $format);
    }

    public function approve(Request $request, RentContractImportItem $item)
    {
        $request->validate(['shop_id' => ['required']], ['shop_id.required' => 'يجب اختيار المحل/العقار المرتبط بالعقد.']);

        $overrides = $request->only([
            'contract_no', 'start_date', 'end_date', 'landlord', 'tenant',
            'property_info', 'rent_value', 'payments_count', 'payment_amount',
            'renewal_terms', 'termination_terms', 'note', 'shop_id',
        ]);

        $this->guardAiItem($item);
        $shopRentId = $this->importService->approveItem($item, array_filter($overrides, fn ($v) => $v !== null && $v !== ''), Auth::id());

        $msg = "تم اعتماد العقد رقم {$shopRentId} وتوليد دفعاته.";
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => $msg, 'item_id' => $item->id]);
        }
        return back()->with('alert.success', $msg);
    }

    /** اعتماد جماعي للعقود (يستخدم البيانات المستخرجة + المحل المختار لكل صف). */
    public function approveAll(Request $request)
    {
        $rows = (array) $request->input('items', []);
        $approved = 0;
        $errors = [];

        foreach ($rows as $r) {
            $id = $r['id'] ?? null;
            $shopId = $r['shop_id'] ?? null;
            $item = $id ? RentContractImportItem::find($id) : null;
            if (! $item || $item->status !== RentContractImportItem::STATUS_NEEDS_REVIEW) {
                continue;
            }
            if (empty($shopId)) {
                $errors[] = ['id' => (int) $id, 'msg' => 'لم يُختر المحل'];
                continue;
            }
            try {
                $this->guardAiItem($item);
                $this->importService->approveItem($item, ['shop_id' => $shopId], Auth::id());
                $approved++;
            } catch (\Throwable $e) {
                $errors[] = ['id' => (int) $id, 'msg' => 'تعذّر الاعتماد'];
            }
        }

        return response()->json(['ok' => true, 'approved' => $approved, 'errors' => $errors]);
    }

    public function reject(Request $request, RentContractImportItem $item)
    {
        $this->guardAiItem($item);
        $this->importService->rejectItem($item, $request->input('reason'), Auth::id());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'تم رفض العقد.', 'item_id' => $item->id]);
        }
        return back()->with('alert.success', 'تم رفض العقد.');
    }
}
