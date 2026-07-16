<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RentPaymentMatchItem;
use App\Models\Shop_rent;
use App\Models\ShopRentpay;
use App\Services\Ai\PaymentMatchingService;
use App\Services\Rent\PaymentRecordingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * مطابقة الدفعات بالذكاء الاصطناعي: استقبال دفعة غير مربوطة، اقتراح ربطها بالعقد/المحل/البند
 * بنسبة ثقة وسبب، ثم اعتماد المستخدم (لا اعتماد آلي). عند الاعتماد يُسجَّل السداد فعلياً.
 */
class PaymentMatchController extends Controller
{
    use \App\Http\Controllers\Dashboard\Concerns\ExportsReports;

    public function __construct(
        protected PaymentMatchingService $matcher,
        protected PaymentRecordingService $recorder,
    ) {}

    /** تصدير قائمة مطابقة الدفعات بانتظار المراجعة (PDF/Excel). */
    public function export(Request $request)
    {
        $format = $request->input('format', 'xlsx');
        $items = RentPaymentMatchItem::needsReviewOrdered()->get();

        $statusAr = [
            'matched' => 'مطابَقة', 'orphan' => 'غير مرتبطة', 'underpaid' => 'مبلغ ناقص',
            'overpaid' => 'مبلغ زائد', 'duplicate' => 'مكرّرة',
        ];
        $header = ['#', 'رقم العقد', 'المستأجر', 'المبلغ', 'تاريخ الاستحقاق', 'حالة المطابقة', 'الثقة %', 'السبب'];
        $rows = [];
        foreach ($items as $i => $it) {
            $rows[] = [
                $i + 1,
                $it->contract_no ?? '—',
                $it->tenant_name ?? '—',
                $it->amount !== null ? number_format((float) $it->amount, 2) : '—',
                $it->due_date ?? '—',
                $statusAr[$it->match_status] ?? $it->match_status,
                $it->confidence !== null ? round($it->confidence * 100) : '—',
                $it->match_reason ?? '—',
            ];
        }
        $summary = [['التقرير', 'مطابقة الدفعات بانتظار المراجعة'], ['العدد', count($rows)], ['التاريخ', now()->toDateString()]];

        return $this->exportData('مطابقة_الدفعات', $summary, $rows, $header, $format);
    }

    /** قائمة الدفعات الواردة بانتظار المراجعة/التأكيد. */
    public function review(Request $request)
    {
        $items = RentPaymentMatchItem::needsReviewOrdered()->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html'  => view('dashboard.rent.payment_match._list', compact('items'))->render(),
                'count' => $items->total(),
            ]);
        }

        return view('dashboard.rent.payment_match.review', [
            'page_title' => 'مطابقة الدفعات بالذكاء الاصطناعي',
            'items'      => $items,
        ]);
    }

    /** استقبال دفعة واردة، تشغيل المطابقة، وتخزين الاقتراح للمراجعة. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'contract_no'  => ['nullable', 'string', 'max:255'],
            'tenant_name'  => ['nullable', 'string', 'max:255'],
            'tenant_id_no' => ['nullable', 'string', 'max:50'],
            'unit_no'      => ['nullable', 'string', 'max:100'],
            'amount'       => ['nullable', 'numeric'],
            'due_date'     => ['nullable', 'date'],
        ]);

        $match = $this->matcher->match($data);

        $item = RentPaymentMatchItem::create([
            'raw'                  => $data,
            'contract_no'          => $data['contract_no'] ?? null,
            'tenant_name'          => $data['tenant_name'] ?? null,
            'tenant_id_no'         => $data['tenant_id_no'] ?? null,
            'unit_no'              => $data['unit_no'] ?? null,
            'amount'               => $data['amount'] ?? null,
            'due_date'             => $data['due_date'] ?? null,
            'matched_shop_id'      => $match['shop_id'],
            'matched_shop_rent_id' => $match['shop_rent_id'],
            'matched_rentpay_id'   => $match['rentpay_id'],
            'confidence'           => $match['confidence'],
            'match_reason'         => $match['reason'],
            'match_status'         => $match['status'],
            'status'               => RentPaymentMatchItem::STATUS_NEEDS_REVIEW,
            'create_user'          => Auth::id(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'item_id' => $item->id, 'match' => $match]);
        }

        return back()->with('alert.success', 'استُلمت الدفعة واقتُرح ربطها — راجع القائمة للتأكيد.');
    }

    /** إعادة تشغيل المطابقة لعنصر (بعد تعديل البيانات مثلاً). */
    public function rematch(Request $request, RentPaymentMatchItem $item)
    {
        $this->guardItem($item);

        $match = $this->matcher->match([
            'contract_no'  => $item->contract_no,
            'tenant_name'  => $item->tenant_name,
            'tenant_id_no' => $item->tenant_id_no,
            'unit_no'      => $item->unit_no,
            'amount'       => $item->amount,
            'due_date'     => optional($item->due_date)->format('Y-m-d'),
        ]);

        $item->update([
            'matched_shop_id'      => $match['shop_id'],
            'matched_shop_rent_id' => $match['shop_rent_id'],
            'matched_rentpay_id'   => $match['rentpay_id'],
            'confidence'           => $match['confidence'],
            'match_reason'         => $match['reason'],
            'match_status'         => $match['status'],
        ]);

        return response()->json(['ok' => true, 'match' => $match]);
    }

    /**
     * تأكيد الربط واعتماد الدفعة: يربط بالبند المستحق (أو ينشئ بنداً للعقد) ويسجّل السداد فعلياً.
     * يقبل تصحيح المستخدم عبر shop_rent_id/rentpay_id.
     */
    public function approve(Request $request, RentPaymentMatchItem $item)
    {
        $this->guardItem($item);

        if ($item->status === RentPaymentMatchItem::STATUS_APPROVED) {
            return $this->ok($request, 'سبق اعتماد هذه الدفعة.', $item);
        }

        $shopRentId = (int) ($request->input('shop_rent_id') ?: $item->matched_shop_rent_id);
        $rentpayId  = (int) ($request->input('rentpay_id') ?: $item->matched_rentpay_id);

        if (! $shopRentId) {
            return $this->fail($request, 'لا يمكن الاعتماد بلا عقد مطابق — حدّد العقد الصحيح أولاً.', 422);
        }

        $contract = Shop_rent::find($shopRentId);
        if (! $contract) {
            return $this->fail($request, 'العقد المحدَّد غير موجود.', 422);
        }

        try {
            $receipt = DB::transaction(function () use ($item, $contract, &$rentpayId) {
                // لا بند مستحق محدَّد → أنشئ بنداً جديداً للعقد بمبلغ/تاريخ الدفعة الواردة
                if (! $rentpayId) {
                    $rentpayId = DB::table('shop_rentpay')->insertGetId([
                        'shop_id'       => $contract->shop_id,
                        'shop_rent_id'  => $contract->shop_rent_id,
                        'rentpay_dt'    => $item->due_date ? Carbon::parse($item->due_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                        'rentpay_price' => (float) $item->amount,
                        'paid_amount'   => 0,
                        'status'        => ShopRentpay::STATUS_UNPAID,
                        'is_paid'       => false,
                        'rentpay_note'  => 'أُنشئت من مطابقة دفعة واردة',
                        'create_user'   => Auth::id(),
                        'created_at'    => Carbon::now(),
                    ]);
                }

                return $this->recorder->record($rentpayId, (float) $item->amount, [
                    'note' => 'اعتماد مطابقة دفعة (عنصر ' . $item->id . ')',
                ], Auth::id());
            });
        } catch (\Throwable $e) {
            Log::error('فشل اعتماد مطابقة الدفعة ' . $item->id . ': ' . $e->getMessage(), ['exception' => $e]);
            return $this->fail($request, 'تعذّر اعتماد الدفعة بسبب خطأ غير متوقّع.', 500);
        }

        $item->update([
            'status'            => RentPaymentMatchItem::STATUS_APPROVED,
            'created_rentpay_id' => $rentpayId,
            'matched_shop_rent_id' => $shopRentId,
            'matched_rentpay_id'  => $rentpayId,
            'reviewed_by'       => Auth::id(),
            'reviewed_at'       => Carbon::now(),
        ]);

        return $this->ok($request, 'تم اعتماد الدفعة وربطها بالعقد وإصدار السند ' . $receipt->receipt_no . '.', $item, ['receipt_no' => $receipt->receipt_no]);
    }

    /** رفض دفعة واردة (غير صحيحة/مكررة). */
    public function reject(Request $request, RentPaymentMatchItem $item)
    {
        $this->guardItem($item);
        $item->update([
            'status'      => RentPaymentMatchItem::STATUS_REJECTED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => Carbon::now(),
        ]);

        return $this->ok($request, 'تم رفض الدفعة.', $item);
    }

    /** حماية: المالك (منشئ العنصر) أو المدير فقط. */
    protected function guardItem(RentPaymentMatchItem $item): void
    {
        $isOwner = $item->create_user !== null && (int) $item->create_user === (int) Auth::id();
        $isAdmin = (int) (optional(Auth::user())->emp_job) === 1;
        abort_unless($isOwner || $isAdmin, 403, 'لا تملك صلاحية الوصول إلى هذه الدفعة.');
    }

    protected function ok(Request $request, string $msg, RentPaymentMatchItem $item, array $extra = [])
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(array_merge(['ok' => true, 'message' => $msg, 'item_id' => $item->id], $extra));
        }

        return back()->with('alert.success', $msg);
    }

    protected function fail(Request $request, string $msg, int $code)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => false, 'message' => $msg], $code);
        }

        return back()->with('alert.error', $msg);
    }
}
