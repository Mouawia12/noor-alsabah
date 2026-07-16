<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShopFinancialLog;
use App\Models\ShopReceipt;
use App\Models\Shop_rent;
use App\Models\ShopRentpay;
use App\Services\Rent\PaymentRecordingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * متابعة سداد العقود على مستوى المحل: عرض العقود ودفعاتها وسنداتها والملخص المالي،
 * وتسجيل الدفعات (سداد + سند + قيد مالي) — كل شيء مرتبط بقاعدة بيانات المحلات المرجعية.
 */
class ShopPaymentController extends Controller
{
    public function __construct(protected PaymentRecordingService $recorder) {}

    /** صفحة سداد المحل: العقود + جداول الدفعات (سابقة/مستحقة/قادمة) + السندات + الملخص. */
    public function show(int $shop)
    {
        $shopRow = DB::table('shop')->where('shop_id', $shop)->first();
        abort_if(! $shopRow, 404, 'المحل غير موجود.');

        $today = Carbon::today();
        $contracts = Shop_rent::where('shop_id', $shop)
            ->orderByDesc('shop_rent_id')->get();

        $data = $contracts->map(function ($c) use ($today) {
            $payments = ShopRentpay::where('shop_rent_id', $c->shop_rent_id)
                ->orderBy('rentpay_dt')->orderBy('seq_no')->get();

            return [
                'contract' => $c,
                'summary'  => $this->recorder->contractSummary($c->shop_rent_id, $today),
                'past'     => $payments->filter(fn ($p) => $p->status === ShopRentpay::STATUS_PAID)->values(),
                'due'      => $payments->filter(fn ($p) => $p->status !== ShopRentpay::STATUS_PAID
                                && $p->rentpay_dt && Carbon::parse($p->rentpay_dt)->lte($today))->values(),
                'upcoming' => $payments->filter(fn ($p) => $p->status !== ShopRentpay::STATUS_PAID
                                && $p->rentpay_dt && Carbon::parse($p->rentpay_dt)->gt($today))->values(),
                'receipts' => ShopReceipt::where('shop_rent_id', $c->shop_rent_id)->latest('paid_at')->get(),
            ];
        });

        $page_title = 'متابعة سداد العقود — ' . ($shopRow->shop_name ?? '');

        return view('dashboard.shop.payments', [
            'page_title' => $page_title,
            'shop'       => $shopRow,
            'contracts'  => $data,
        ]);
    }

    /** تسجيل دفعة على بند سداد: يحدّث الحالة + ينشئ سنداً + يقيّد في السجل المالي (ذرّياً). */
    public function record(Request $request, int $rentpay)
    {
        $data = $request->validate([
            'amount'  => ['nullable', 'numeric', 'min:0'],
            'method'  => ['nullable', 'string', 'max:30'],
            'note'    => ['nullable', 'string', 'max:500'],
            'paid_at' => ['nullable', 'date'],
        ], [
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً.',
            'paid_at.date'   => 'تاريخ القبض غير صالح.',
        ]);

        $pay = ShopRentpay::find($rentpay);
        if (! $pay) {
            return $this->fail($request, 'الدفعة غير موجودة.', 404);
        }
        if ($pay->status === ShopRentpay::STATUS_PAID) {
            return $this->fail($request, 'هذه الدفعة مسدَّدة بالكامل مسبقاً.', 422);
        }

        try {
            $receipt = $this->recorder->record(
                $rentpay,
                isset($data['amount']) ? (float) $data['amount'] : null,
                ['method' => $data['method'] ?? null, 'note' => $data['note'] ?? null, 'paid_at' => $data['paid_at'] ?? null],
                Auth::id()
            );
        } catch (\Throwable $e) {
            Log::error('فشل تسجيل سداد الدفعة ' . $rentpay . ': ' . $e->getMessage(), ['exception' => $e]);
            return $this->fail($request, 'تعذّر تسجيل الدفعة بسبب خطأ غير متوقّع.', 500);
        }

        $fresh = $pay->fresh();
        $msg = 'تم تسجيل الدفعة وإصدار سند القبض ' . $receipt->receipt_no . '.';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'          => true,
                'message'     => $msg,
                'receipt_no'  => $receipt->receipt_no,
                'receipt_id'  => $receipt->receipt_id,
                'status'      => $fresh->status,
                'paid_amount' => $fresh->paid_amount,
                'remaining'   => $fresh->remaining,
            ]);
        }

        return back()->with('alert.success', $msg);
    }

    /** التقرير المالي للمحل: إجمالي مستحق/مسدَّد/متبقٍّ/متأخّر، قابل للتصفية بالعقد/الفترة. */
    public function report(Request $request, int $shop)
    {
        $shopRow = DB::table('shop')->where('shop_id', $shop)->first();
        abort_if(! $shopRow, 404, 'المحل غير موجود.');

        $today = Carbon::today();
        $q = ShopRentpay::where('shop_rentpay.shop_id', $shop);

        if ($request->filled('contract_id')) {
            $q->where('shop_rent_id', (int) $request->contract_id);
        }
        if ($request->filled('from')) {
            $q->whereDate('rentpay_dt', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('rentpay_dt', '<=', $request->to);
        }

        $payments = $q->get();
        $total = $paid = $overdue = 0.0;
        $overdueCount = 0;
        foreach ($payments as $p) {
            $total += (float) $p->rentpay_price;
            $paid  += (float) $p->paid_amount;
            if ($p->isOverdue($today)) {
                $overdue += $p->remaining;
                $overdueCount++;
            }
        }
        $summary = [
            'total'         => round($total, 2),
            'paid'          => round($paid, 2),
            'remaining'     => round(max(0.0, $total - $paid), 2),
            'overdue'       => round($overdue, 2),
            'overdue_count' => $overdueCount,
            'count'         => $payments->count(),
        ];

        $contracts = Shop_rent::where('shop_id', $shop)->get(['shop_rent_id', 'contract_no', 'rent_no']);
        $ledger = ShopFinancialLog::where('shop_id', $shop)->latest('created_at')->limit(200)->get();

        return view('dashboard.shop.financial_report', [
            'page_title' => 'التقرير المالي — ' . ($shopRow->shop_name ?? ''),
            'shop'       => $shopRow,
            'summary'    => $summary,
            'contracts'  => $contracts,
            'ledger'     => $ledger,
            'filters'    => $request->only(['contract_id', 'from', 'to']),
        ]);
    }

    protected function fail(Request $request, string $msg, int $code)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => false, 'message' => $msg], $code);
        }

        return back()->with('alert.error', $msg);
    }
}
