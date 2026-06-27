<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Ai\RentAlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return back()->with('alert.success', 'تم تعليم الدفعة كمسدَّدة.');
    }
}
