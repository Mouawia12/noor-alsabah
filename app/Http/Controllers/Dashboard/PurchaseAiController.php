<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\Shop;
use App\Services\Ai\PdfService;
use App\Services\Ai\PurchaseImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // رفض الملف المكرّر قبل تخزينه: نحسب البصمة من الملف المؤقّت مباشرةً
        // فلا يُرفع ولا تُنشأ دفعة جديدة (طلب العميل: المكرّر لا يُضاف أصلاً).
        $existing = $this->importService->findByHash(hash_file('sha256', $file->getRealPath()));
        if ($existing) {
            return $this->duplicateResponse($request, $existing->id);
        }

        $path = $file->store('purchase/batches', $disk);
        $batch = $this->importService->createBatch($path, $file->getClientOriginalName(), Auth::id());

        return $this->uploadResponse($request, $batch->id, 'بدأت المعالجة. يمكنك متابعة التقدّم هنا.');
    }

    /** استجابة الرفع: JSON عند AJAX (لمتابعة التقدّم بلا إعادة تحميل)، وإلا تحويل عادي. */
    protected function uploadResponse(Request $request, int $batchId, string $message)
    {
        $url = route('dashboard.purchase.ai.batch', $batchId);
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['ok' => true, 'redirect' => $url, 'message' => $message]);
        }

        return redirect()->to($url)->with('alert.success', $message);
    }

    /**
     * استجابة الملف المكرّر: لا يُرفع ولا تُنشأ دفعة — رسالة واضحة + رابط النتيجة السابقة.
     * لا نُرسل «redirect» حتى لا تنتقل الواجهة تلقائياً (المطلوب: إخبار المستخدم أنه مكرّر فقط).
     */
    protected function duplicateResponse(Request $request, int $existingId)
    {
        $message = 'هذا الملف مكرّر — سبق رفعه ومعالجته، ولم تتم إضافته مرّة أخرى.';
        $existingUrl = route('dashboard.purchase.ai.batch', $existingId);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'ok'           => false,
                'duplicate'    => true,
                'message'      => $message,
                'existing_url' => $existingUrl,
            ], 409);
        }

        return redirect()->to($existingUrl)->with('alert.error', $message);
    }

    /** صفحة متابعة دفعة. */
    public function batch(PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $page_title = 'متابعة معالجة الفواتير';
        $batch->load('items');

        return view('dashboard.purchase.ai.status', compact('page_title', 'batch'));
    }

    /** تقدّم الدفعة (JSON) — للتوافق ولإعادة القراءة عند فتح صفحة دفعة مكتملة. */
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

    /**
     * خطوة معالجة لحظية يقودها المتصفّح (بدل الطابور الخلفي):
     * أول نداء يجهّز العناصر (تحويل لصور + تقسيم)، ثم كل نداء يعالج فاتورة واحدة ويُعيد التقدّم.
     * المتصفّح يكرّر النداء حتى done=true — فلا اعتماد على عامل طابور قد يتوقّف.
     */
    public function step(Request $request, PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        @set_time_limit(0); // التجهيز/الاستخراج قد يطول للملفات الكبيرة

        // انتهت أصلاً (اكتملت/فشلت) → أعِد التقدّم فقط
        if (in_array($batch->status, [PurchaseImportBatch::STATUS_COMPLETED, PurchaseImportBatch::STATUS_FAILED], true)) {
            return $this->stepProgress($batch);
        }

        // 1) تجهيز العناصر أول مرّة (تحويل الملف لصور وتقسيمه لفواتير)
        if (! $batch->items()->exists()) {
            try {
                $this->importService->prepareItems($batch);
            } catch (\Throwable $e) {
                Log::error('فشل تجهيز دفعة الفواتير ' . $batch->id . ': ' . $e->getMessage(), ['exception' => $e]);
                // prepareItems ضبطت الحالة=failed وسبب الخطأ
            }
            return $this->stepProgress($batch->fresh(), 'prepared');
        }

        // 2) عالِج الفاتورة المعلّقة التالية لحظياً
        $id = $batch->items()
            ->where('status', PurchaseImportItem::STATUS_PENDING)
            ->orderBy('page_from')->value('id');
        if ($id) {
            try {
                dispatch_sync(new \App\Jobs\ProcessPurchaseItemJob($id));
            } catch (\Throwable $e) {
                // العنصر يُعلّم «فشل» داخلياً قبل رمي الاستثناء — نتابع بقية الفواتير
                Log::warning('تعذّرت معالجة الفاتورة ' . $id . ' لحظياً: ' . $e->getMessage());
            }
        }

        return $this->stepProgress($batch->fresh());
    }

    /** يبني استجابة تقدّم موحّدة تُخبر المتصفّح بالعدّادات وهل انتهت المعالجة. */
    protected function stepProgress(PurchaseImportBatch $batch, ?string $phase = null)
    {
        $pending = $batch->items()
            ->whereIn('status', [PurchaseImportItem::STATUS_PENDING, PurchaseImportItem::STATUS_PROCESSING])
            ->count();
        $terminal = in_array($batch->status, [PurchaseImportBatch::STATUS_COMPLETED, PurchaseImportBatch::STATUS_FAILED], true);

        return response()->json([
            'status'          => $batch->status,
            'total_items'     => $batch->total_items,
            'processed_items' => $batch->processed_items,
            'failed_items'    => $batch->failed_items,
            'error_reason'    => $batch->error_reason,
            'pending'         => $pending,
            'phase'           => $phase,
            'done'            => $terminal || ($batch->total_items > 0 && $pending === 0),
        ]);
    }

    /** قائمة العناصر التي تحتاج مراجعة. */
    public function review(Request $request)
    {
        $page_title = 'مراجعة واعتماد الفواتير المستخرجة';

        $query = PurchaseImportItem::with('batch')->needsReviewOrdered();

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $items = $query->paginate(20)->withQueryString();

        // طلب AJAX (إعادة جلب بعد اعتماد/رفض/تنقّل) → نُعيد جزء الجدول فقط.
        if ($request->ajax()) {
            return response()->json([
                'html'  => view('dashboard.purchase.ai._review_list', compact('items'))->render(),
                'count' => $items->total(),
            ]);
        }

        // قائمة المحلات لاختيار الفرع الذي تُرحَّل إليه الفواتير (مع كود الفرع)
        $shops = Shop::orderBy('shop_name')->get(['shop_id', 'shop_name', 'shop_code']);

        return view('dashboard.purchase.ai.review', compact('page_title', 'items', 'shops'));
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

    /** إعادة معالجة عنصر فاشل — لحظياً (بلا طابور خلفي). */
    public function reprocess(PurchaseImportItem $item)
    {
        $this->guardAiItem($item);
        @set_time_limit(0);
        $item->update(['status' => PurchaseImportItem::STATUS_PENDING, 'error_reason' => null]);
        try {
            dispatch_sync(new \App\Jobs\ProcessPurchaseItemJob($item->id));
        } catch (\Throwable $e) {
            Log::warning('تعذّرت إعادة معالجة الفاتورة ' . $item->id . ': ' . $e->getMessage());
        }

        return back()->with('alert.success', 'أُعيدت معالجة الفاتورة.');
    }

    /** إعادة معالجة دفعة فاشلة بالكامل — تُصفَّر ثم تُعالَج لحظياً من صفحة المتابعة. */
    public function reprocessBatch(PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $batch->items()->delete();
        $batch->update(['status' => PurchaseImportBatch::STATUS_PENDING, 'error_reason' => null, 'total_items' => 0, 'processed_items' => 0, 'failed_items' => 0]);

        // صفحة المتابعة تقود المعالجة لحظياً عبر step (بلا اعتماد على عامل طابور)
        return redirect()->to(route('dashboard.purchase.ai.batch', $batch->id))
            ->with('alert.success', 'بدأت إعادة المعالجة.');
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

    /** تقرير حالة كل فاتورة داخل دفعة (نجاح/فشل/سبب) — لطلب العميل عند الملفات الكبيرة. */
    public function batchReport(PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $page_title = 'تقرير حالة الفواتير — ' . $batch->original_filename;
        $rows = $this->batchReportRows($batch);

        return view('dashboard.purchase.ai.batch_report', compact('page_title', 'batch', 'rows'));
    }

    /** تصدير تقرير حالة الفواتير (xlsx / pdf). */
    public function exportBatchReport(Request $request, PurchaseImportBatch $batch)
    {
        $this->guardAiBatch($batch);
        $format = $request->input('format', 'xlsx');
        $rows = $this->batchReportRows($batch);

        $detail = array_map(fn ($r) => [
            $r['seq'], $r['pages'], $r['status_label'], $r['invoice_no'],
            $r['supplier'], $r['tax_number'], $r['total'], $r['reason'],
        ], $rows);

        $header = ['#', 'الصفحات', 'الحالة', 'رقم الفاتورة', 'المورد', 'الرقم الضريبي', 'الإجمالي', 'السبب/ملاحظات'];
        $summary = [
            ['الملف', $batch->original_filename],
            ['إجمالي الفواتير', count($rows)],
            ['نجحت', count(array_filter($rows, fn ($r) => $r['ok']))],
            ['فشلت/مرفوضة', count(array_filter($rows, fn ($r) => ! $r['ok']))],
        ];

        return $this->exportData('تقرير_حالة_الفواتير', $summary, $detail, $header, $format);
    }

    /** يبني صفوف تقرير الحالة لكل عنصر في الدفعة. */
    protected function batchReportRows(PurchaseImportBatch $batch): array
    {
        $labels = [
            'pending'      => 'قيد الانتظار',
            'processing'   => 'قيد المعالجة',
            'needs_review' => 'مقبولة (بانتظار المراجعة)',
            'approved'     => 'معتمدة/مرحّلة',
            'rejected'     => 'مرفوضة يدوياً',
            'failed'       => 'فشلت المعالجة',
            'merged'       => 'صفحة تكملة (مدموجة)',
        ];
        $okStatuses = ['needs_review', 'approved'];

        $items = PurchaseImportItem::where('batch_id', $batch->id)->orderBy('page_from')->get();
        $rows = [];
        $seq = 0;
        foreach ($items as $item) {
            $d = (array) ($item->extracted_json['data'] ?? []);
            $pages = $item->page_from == $item->page_to
                ? (string) $item->page_from
                : "{$item->page_from}–{$item->page_to}";
            $rows[] = [
                'seq'          => ++$seq,
                'pages'        => $pages,
                'status'       => $item->status,
                'status_label' => $labels[$item->status] ?? $item->status,
                'ok'           => in_array($item->status, $okStatuses, true),
                'invoice_no'   => $d['invoice_no'] ?? '—',
                'supplier'     => $d['supplier_name'] ?? '—',
                'tax_number'   => $d['tax_number'] ?? '—',
                'total'        => $d['total'] ?? '—',
                'confidence'   => $item->confidence !== null ? round($item->confidence * 100) . '%' : '—',
                'reason'       => $item->error_reason ?? '',
            ];
        }

        return $rows;
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

    /** تصدير قائمة الفواتير بانتظار المراجعة (PDF/Excel) — نفس ما يظهر في الشاشة. */
    public function exportReview(Request $request)
    {
        $format = $request->input('format', 'xlsx');
        $query = PurchaseImportItem::with('batch')->needsReviewOrdered();
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }
        $items = $query->get();

        $header = ['#', 'رقم الفاتورة', 'المورد', 'الرقم الضريبي', 'التاريخ', 'الإجمالي', 'الثقة %', 'الملف'];
        $rows = [];
        foreach ($items as $i => $it) {
            $d = (array) ($it->extracted_json['data'] ?? []);
            $rows[] = [
                $i + 1,
                $d['invoice_no'] ?? '—',
                $d['supplier_name'] ?? '—',
                $d['tax_number'] ?? '—',
                $d['invoice_date'] ?? '—',
                $d['total'] ?? '—',
                $it->confidence !== null ? round($it->confidence * 100) : '—',
                $it->batch->original_filename ?? '—',
            ];
        }
        $summary = [['التقرير', 'الفواتير بانتظار المراجعة'], ['العدد', count($rows)], ['التاريخ', now()->toDateString()]];

        return $this->exportData('الفواتير_بانتظار_المراجعة', $summary, $rows, $header, $format);
    }

    /** اعتماد عنصر وإنشاء سجل مشتريات. */
    public function approve(Request $request, PurchaseImportItem $item)
    {
        $overrides = $request->only([
            'invoice_no', 'invoice_date', 'tax_number', 'currency',
            'amount_before_tax', 'tax_amount', 'total', 'note',
            'supplier_id', 'new_supplier_name', 'shop_id',
        ]);
        $overrides = array_filter($overrides, fn ($v) => $v !== null && $v !== '');

        // إن لم يُحدَّد مورد، استخدم المطابق أو أنشئ المقترح من الاسم المستخرج
        if (empty($overrides['supplier_id']) && empty($overrides['new_supplier_name'])) {
            $sup = $item->extracted_json['supplier'] ?? [];
            $data = $item->extracted_json['data'] ?? [];
            if (! empty($sup['matched'])) {
                $overrides['supplier_id'] = $sup['supplier_id'];
            } elseif (! empty($data['supplier_name'])) {
                $overrides['new_supplier_name'] = $data['supplier_name'];
            }
        }

        $this->guardAiItem($item);

        try {
            $purchaseId = $this->importService->approveItem($item, $overrides, Auth::id());
        } catch (\App\Services\Ai\DuplicateInvoiceException $e) {
            // فاتورة مكررة: رسالة واضحة للمستخدم بدل خطأ عام
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('alert.error', $e->getMessage());
        } catch (\Throwable $e) {
            // أي فشل حقيقي في الترحيل: سجّله وأعطِ المستخدم رسالة واضحة بدل خطأ 500 صامت
            Log::error('فشل ترحيل الفاتورة (العنصر ' . $item->id . '): ' . $e->getMessage(), ['exception' => $e]);
            $msg = 'تعذّر ترحيل الفاتورة بسبب خطأ غير متوقّع. لم يُحفظ أي سجل، يرجى المحاولة مجدداً.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $msg], 500);
            }
            return back()->with('alert.error', $msg);
        }

        // اسم الفرع في الرسالة حتى يعرف المستخدم أين رُحّلت الفاتورة بالضبط
        $shopName = ! empty($overrides['shop_id'])
            ? Shop::where('shop_id', $overrides['shop_id'])->value('shop_name')
            : null;
        $msg = $shopName
            ? "تم ترحيل الفاتورة إلى فرع «{$shopName}» وإنشاء سجل المشتريات رقم {$purchaseId}."
            : "تم ترحيل الفاتورة وإنشاء سجل المشتريات رقم {$purchaseId}.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => $msg, 'item_id' => $item->id, 'shop_name' => $shopName]);
        }
        return back()->with('alert.success', $msg);
    }

    /** ترحيل جماعي للفواتير المقبولة إلى الفرع المحدد (المورد المطابق أو المقترح). */
    public function approveAll(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $shopId = $request->input('shop_id');

        // الفرع إلزامي للترحيل الجماعي (طلب العميل: اختيار المحل قبل الترحيل)
        if ($shopId === null || $shopId === '') {
            return response()->json(['ok' => false, 'message' => 'يرجى اختيار الفرع/المحل قبل ترحيل الفواتير.'], 422);
        }

        // اسم الفرع للتأكيد الواضح (حتى لا تختلط الفواتير بين الفروع)
        $shopName = Shop::where('shop_id', $shopId)->value('shop_name');
        if (! $shopName) {
            return response()->json(['ok' => false, 'message' => 'الفرع المحدد غير موجود، يرجى اختيار فرع صحيح.'], 422);
        }

        $approved = 0;
        $errors = [];

        foreach ($ids as $id) {
            $item = PurchaseImportItem::find($id);
            if (! $item || $item->status !== PurchaseImportItem::STATUS_NEEDS_REVIEW) {
                continue;
            }
            try {
                $this->guardAiItem($item);
                // استخدم المورد المطابق أو أنشئ المقترح من الاسم المستخرج
                $sup = $item->extracted_json['supplier'] ?? [];
                $data = $item->extracted_json['data'] ?? [];
                $overrides = ! empty($sup['matched'])
                    ? ['supplier_id' => $sup['supplier_id']]
                    : (! empty($data['supplier_name']) ? ['new_supplier_name' => $data['supplier_name']] : []);
                $overrides['shop_id'] = $shopId; // ربط الفاتورة بالفرع المحدد
                $this->importService->approveItem($item, $overrides, Auth::id());
                $approved++;
            } catch (\App\Services\Ai\DuplicateInvoiceException $e) {
                $errors[] = ['id' => (int) $id, 'msg' => $e->getMessage()];
            } catch (\Throwable $e) {
                // نُسجّل السبب الحقيقي للتشخيص، ونُبقي رسالة موجزة للمستخدم في الترحيل الجماعي
                Log::error('فشل الترحيل الجماعي (العنصر ' . $id . '): ' . $e->getMessage(), ['exception' => $e]);
                $errors[] = ['id' => (int) $id, 'msg' => 'تعذّر الترحيل'];
            }
        }

        return response()->json(['ok' => true, 'approved' => $approved, 'errors' => $errors, 'shop_name' => $shopName]);
    }

    /** رفض عنصر. */
    public function reject(Request $request, PurchaseImportItem $item)
    {
        $this->guardAiItem($item);
        $this->importService->rejectItem($item, $request->input('reason'), Auth::id());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'تم رفض الفاتورة.', 'item_id' => $item->id]);
        }
        return back()->with('alert.success', 'تم رفض الفاتورة.');
    }

    /**
     * حذف عنصر واحد من قائمة «المقبولة بانتظار الترحيل» (تخفيف تراكم القائمة).
     * مقصور على العناصر التي لم تُرحَّل بعد (needs_review وبلا سجل مشتريات) حتى لا تُمسّ أي فاتورة مُرحّلة.
     */
    public function destroy(Request $request, PurchaseImportItem $item)
    {
        $this->guardAiItem($item);

        if ($item->status !== PurchaseImportItem::STATUS_NEEDS_REVIEW || $item->purchase_id) {
            $msg = 'لا يمكن حذف فاتورة مُرحّلة أو غير معلّقة.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $msg], 422);
            }
            return back()->with('alert.error', $msg);
        }

        $item->delete();
        $this->forgetAiStats();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'تم حذف الفاتورة من قائمة الانتظار.', 'item_id' => $item->id]);
        }
        return back()->with('alert.success', 'تم حذف الفاتورة من قائمة الانتظار.');
    }

    /** حذف متعدد للفواتير المحددة من قائمة الانتظار (needs_review فقط). */
    public function destroyMany(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $deleted = 0;
        $errors = [];

        foreach ($ids as $id) {
            $item = PurchaseImportItem::find($id);
            // تجاهُل بصمت لأي عنصر مُرحّل/محذوف/غير موجود — لا تُمسّ الفواتير المُرحّلة
            if (! $item || $item->status !== PurchaseImportItem::STATUS_NEEDS_REVIEW || $item->purchase_id) {
                continue;
            }
            try {
                $this->guardAiItem($item);
                $item->delete();
                $deleted++;
            } catch (\Throwable $e) {
                Log::error('فشل حذف عنصر من قائمة الانتظار (العنصر ' . $id . '): ' . $e->getMessage(), ['exception' => $e]);
                $errors[] = ['id' => (int) $id, 'msg' => 'تعذّر الحذف'];
            }
        }

        if ($deleted > 0) {
            $this->forgetAiStats();
        }

        return response()->json([
            'ok'      => true,
            'deleted' => $deleted,
            'errors'  => $errors,
            'message' => $deleted > 0 ? "تم حذف {$deleted} فاتورة من قائمة الانتظار." : 'لم يُحذف أي عنصر.',
        ]);
    }

    /** تحديث مؤشّرات اللوحة بأمان — فشل الكاش لا يُبطل الحذف. */
    protected function forgetAiStats(): void
    {
        try {
            \App\Support\AiDashboardStats::forget();
        } catch (\Throwable $e) {
            Log::warning('فشل تحديث مؤشّرات لوحة الذكاء الاصطناعي بعد الحذف: ' . $e->getMessage());
        }
    }
}
