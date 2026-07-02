<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Ai\RentAlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * لوحة متابعة الإيجارات: الدفعات المستحقة/المتأخرة والعقود قاربت الانتهاء + تعليم الدفع.
 */
class RentAlertsController extends Controller
{
    public function __construct(protected RentAlertService $alerts) {}

    public function index(Request $request)
    {
        $page_title = 'لوحة متابعة الإيجارات';
        $dueDays = (int) $request->input('days', 10);
        $expiryDays = (int) $request->input('expiry', 30);

        $upcoming = $this->alerts->upcomingPayments($dueDays);
        $overdue  = $this->alerts->overduePayments();
        $expiring = $this->alerts->expiringContracts($expiryDays);

        return view('dashboard.rent.alerts.index', compact('page_title', 'upcoming', 'overdue', 'expiring', 'dueDays', 'expiryDays'));
    }

    public function markPaid(Request $request, int $rentpay)
    {
        $this->alerts->markPaid($rentpay, Auth::id());

        return back()
            ->with('alert.success', 'تم تعليم الدفعة كمسدَّدة. يمكنك تنزيل سند الاستلام.')
            ->with('receipt_url', route('dashboard.rent.alerts.receipt', $rentpay));
    }

    /** سند استلام دفعة رسمي (PDF بترويسة المنشأة + اسم الموظف). */
    public function receipt(int $rentpay)
    {
        $p = DB::table('shop_rentpay as rp')
            ->leftJoin('shop as s', 's.shop_id', '=', 'rp.shop_id')
            ->leftJoin('users as u', 'u.id', '=', 'rp.update_user')
            ->where('rp.rentpay_id', $rentpay)
            ->selectRaw('rp.*, s.shop_name, u.name as employee_name,
                (SELECT COUNT(*) FROM shop_rentpay r2 WHERE r2.shop_id = rp.shop_id AND r2.rentpay_dt <= rp.rentpay_dt) as pay_no,
                (SELECT COUNT(*) FROM shop_rentpay r3 WHERE r3.shop_id = rp.shop_id) as pay_total,
                (SELECT contract_no FROM shop_rent sr WHERE sr.shop_id = rp.shop_id ORDER BY sr.shop_rent_id DESC LIMIT 1) as contract_no,
                (SELECT landlord FROM shop_rent sr WHERE sr.shop_id = rp.shop_id ORDER BY sr.shop_rent_id DESC LIMIT 1) as landlord,
                (SELECT tenant FROM shop_rent sr WHERE sr.shop_id = rp.shop_id ORDER BY sr.shop_rent_id DESC LIMIT 1) as tenant')
            ->first();

        abort_if(! $p, 404);
        $employee = $p->employee_name ?: (Auth::user()->name ?? '—');

        // مبلغ كتابةً (إن توفّرت مكتبة ar-php)
        $amountWords = '';
        try {
            if (class_exists(\ArPHP\I18N\Arabic::class)) {
                $amountWords = (new \ArPHP\I18N\Arabic())->int2str((int) round((float) $p->rentpay_price)) . ' ريال';
            }
        } catch (\Throwable $e) {
            $amountWords = '';
        }

        $html = view('dashboard.rent.alerts.receipt', compact('p', 'employee', 'amountWords'))->render();

        \PDF::SetTitle('سند استلام دفعة');
        \PDF::setPrintHeader(false);
        \PDF::setPrintFooter(false);
        \PDF::AddPage();
        // مطبوعة الفوالين الرسمية إن وُفّرت (ترويسة كاملة العرض)، وإلا الشعار الافتراضي
        $letterhead = config('ai.receipt_letterhead');
        $letterheadPath = $letterhead ? public_path($letterhead) : null;
        if ($letterheadPath && is_file($letterheadPath)) {
            $ext = strtoupper(pathinfo($letterheadPath, PATHINFO_EXTENSION));
            $ext = $ext === 'JPEG' ? 'JPG' : ($ext ?: 'JPG');
            \PDF::Image($letterheadPath, 10, 8, 190, '', $ext, '', 'C');
            \PDF::SetY(45);
        } else {
            $logo = public_path('assets/media/logos/Logopdf.jpg');
            if (is_file($logo)) {
                \PDF::Image($logo, 90, 8, 30, '', 'JPG', '', 'C');
                \PDF::SetY(40);
            }
        }
        \PDF::SetFont('aealarabiya', '', 12);
        \PDF::writeHTML($html, true, false, true, false, '');

        return \PDF::Output('receipt-' . $rentpay . '.pdf', 'I');
    }
}
