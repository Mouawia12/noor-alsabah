<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * إدارة أكواد المحلات: تعيين كود فريد لكل محل، وتفعيله/إلغاؤه، والبحث بالاسم أو الكود.
 * الكود يُستخدم كمعرّف مختصر للفرع في الترحيل والتقارير مع استمرار عرض الاسم.
 */
class ShopCodeController extends Controller
{
    /** قائمة المحلات وأكوادها + بحث بالاسم/الكود. */
    public function index(Request $request)
    {
        $page_title = 'إدارة أكواد المحلات';
        $q = trim((string) $request->input('q', ''));

        $shops = Shop::query()
            ->leftJoin('users as u', 'u.id', '=', 'shop.shop_code_by')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('shop.shop_name', 'like', "%{$q}%")
                      ->orWhere('shop.shop_code', 'like', "%{$q}%");
                });
            })
            ->orderBy('shop.shop_name')
            ->paginate(25, ['shop.*', 'u.name as code_by_name'])
            ->withQueryString();

        return view('dashboard.shop_codes.index', compact('page_title', 'shops', 'q'));
    }

    /** تعيين/تعديل كود محل (يجب أن يكون فريداً غير مكرر). */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'shop_id'   => ['required', 'integer', Rule::exists('shop', 'shop_id')],
            'shop_code' => [
                'required', 'string', 'max:50',
                // فريد بين المحلات الأخرى (نسمح ببقاء نفس الكود على نفس المحل عند التعديل)
                Rule::unique('shop', 'shop_code')->ignore($request->shop_id, 'shop_id'),
            ],
        ], [
            'shop_code.required' => 'كود الفرع مطلوب.',
            'shop_code.unique'   => 'هذا الكود مستخدم لمحل آخر — يجب أن يكون الكود فريداً.',
            'shop_id.exists'     => 'المحل غير موجود.',
        ]);

        $existing = DB::table('shop')->where('shop_id', $validated['shop_id'])->value('shop_code');

        DB::table('shop')->where('shop_id', $validated['shop_id'])->update([
            'shop_code'    => $validated['shop_code'],
            // عند إنشاء الكود لأول مرة نسجّل المنشئ والتاريخ
            'shop_code_by' => $existing ? DB::raw('shop_code_by') : Auth::id(),
            'shop_code_at' => $existing ? DB::raw('shop_code_at') : Carbon::now(),
        ]);

        $msg = $existing ? 'تم تعديل كود الفرع.' : 'تم إنشاء كود الفرع.';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => $msg]);
        }

        return back()->with('alert.success', $msg);
    }

    /** تفعيل/إلغاء تفعيل كود محل. */
    public function toggle(Request $request)
    {
        $request->validate(['shop_id' => ['required', 'integer', Rule::exists('shop', 'shop_id')]]);

        $current = (int) DB::table('shop')->where('shop_id', $request->shop_id)->value('shop_code_active');
        $new = $current === 1 ? 0 : 1;
        DB::table('shop')->where('shop_id', $request->shop_id)->update(['shop_code_active' => $new]);

        $msg = $new === 1 ? 'تم تفعيل الكود.' : 'تم إلغاء تفعيل الكود.';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'active' => $new, 'message' => $msg]);
        }

        return back()->with('alert.success', $msg);
    }
}
