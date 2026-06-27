<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * إشعارات النظام الداخلية (قناة database) للمستخدم الحالي.
 */
class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $page_title = 'الإشعارات';
        $notifications = $request->user()->notifications()->paginate(20);

        return view('dashboard.notifications.index', compact('page_title', 'notifications'));
    }

    public function markRead(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->first();
        if ($n) {
            $n->markAsRead();
        }

        return back();
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('alert.success', 'تم تعليم جميع الإشعارات كمقروءة.');
    }
}
