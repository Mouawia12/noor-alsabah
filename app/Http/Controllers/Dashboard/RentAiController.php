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
            return redirect()->route('dashboard.rent.ai.batch', $existing->id)
                ->with('alert.success', 'هذا الملف مرفوع مسبقاً — هذه نتيجة المعالجة السابقة.');
        }

        $batch = $this->importService->createBatch($path, $file->getClientOriginalName(), Auth::id());

        return redirect()->route('dashboard.rent.ai.batch', $batch->id)
            ->with('alert.success', 'بدأت المعالجة. يمكنك متابعة التقدّم هنا.');
    }

    public function batch(RentContractImportBatch $batch)
    {
        $page_title = 'متابعة معالجة العقود';
        $batch->load('items');

        return view('dashboard.rent.ai.status', compact('page_title', 'batch'));
    }

    public function batchJson(RentContractImportBatch $batch)
    {
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

        $query = RentContractImportItem::with('batch')
            ->where('status', RentContractImportItem::STATUS_NEEDS_REVIEW)
            ->latest();

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $items = $query->paginate(20);
        $shops = DB::table('shop')->orderBy('shop_name')->get(['shop_id', 'shop_name']);

        return view('dashboard.rent.ai.review', compact('page_title', 'items', 'shops'));
    }

    public function approve(Request $request, RentContractImportItem $item)
    {
        $request->validate(['shop_id' => ['required']], ['shop_id.required' => 'يجب اختيار المحل/العقار المرتبط بالعقد.']);

        $overrides = $request->only([
            'contract_no', 'start_date', 'end_date', 'landlord', 'tenant',
            'property_info', 'rent_value', 'payments_count', 'payment_amount',
            'renewal_terms', 'termination_terms', 'note', 'shop_id',
        ]);

        $shopRentId = $this->importService->approveItem($item, array_filter($overrides, fn ($v) => $v !== null && $v !== ''), Auth::id());

        return back()->with('alert.success', "تم اعتماد العقد رقم {$shopRentId} وتوليد دفعاته.");
    }

    public function reject(Request $request, RentContractImportItem $item)
    {
        $this->importService->rejectItem($item, $request->input('reason'), Auth::id());

        return back()->with('alert.success', 'تم رفض العقد.');
    }
}
