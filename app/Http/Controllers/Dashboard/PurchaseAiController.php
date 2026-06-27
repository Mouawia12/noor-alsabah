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
        $page_title = 'متابعة معالجة الفواتير';
        $batch->load('items');

        return view('dashboard.purchase.ai.status', compact('page_title', 'batch'));
    }

    /** تقدّم الدفعة (JSON للـ polling). */
    public function batchJson(PurchaseImportBatch $batch)
    {
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

    /** اعتماد عنصر وإنشاء سجل مشتريات. */
    public function approve(Request $request, PurchaseImportItem $item)
    {
        $overrides = $request->only([
            'invoice_no', 'invoice_date', 'tax_number', 'currency',
            'amount_before_tax', 'tax_amount', 'total', 'note',
            'supplier_id', 'new_supplier_name',
        ]);

        $purchaseId = $this->importService->approveItem($item, array_filter($overrides, fn ($v) => $v !== null && $v !== ''), Auth::id());

        return back()->with('alert.success', "تم اعتماد الفاتورة وإنشاء سجل المشتريات رقم {$purchaseId}.");
    }

    /** رفض عنصر. */
    public function reject(Request $request, PurchaseImportItem $item)
    {
        $this->importService->rejectItem($item, $request->input('reason'), Auth::id());

        return back()->with('alert.success', 'تم رفض الفاتورة.');
    }
}
