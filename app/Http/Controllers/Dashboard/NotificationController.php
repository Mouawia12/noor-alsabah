<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\NotificationPresenter;
use Illuminate\Http\Request;

/**
 * إشعارات النظام الداخلية (قناة database) للمستخدم الحالي:
 * صفحة كاملة + قائمة منسدلة حيّة في الترويسة (عبر recent) + تعليم كمقروء.
 */
class NotificationController extends Controller
{
    /** صفحة كل الإشعارات. */
    public function index(Request $request)
    {
        $page_title = 'الإشعارات';
        $notifications = $request->user()->notifications()->paginate(20);

        return view('dashboard.notifications.index', compact('page_title', 'notifications'));
    }

    /** أحدث الإشعارات + عدد غير المقروء (للقائمة المنسدلة والاستطلاع الدوري). */
    public function recent(Request $request)
    {
        $user = $request->user();
        $items = $user->notifications()->latest()->limit(8)->get()
            ->map(fn ($n) => NotificationPresenter::present($n));

        return response()->json([
            'unread' => $user->unreadNotifications()->count(),
            'html'   => view('partials.topbar._notifications_list', ['items' => $items])->render(),
        ]);
    }

    /** تعليم إشعار واحد كمقروء. */
    public function markRead(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->first();
        if ($n) {
            $n->markAsRead();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'     => true,
                'unread' => $request->user()->unreadNotifications()->count(),
                'url'    => $n->data['url'] ?? null,
            ]);
        }

        return back();
    }

    /** تعليم كل الإشعارات كمقروءة. */
    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'unread' => 0]);
        }

        return back()->with('alert.success', 'تم تعليم جميع الإشعارات كمقروءة.');
    }
}
