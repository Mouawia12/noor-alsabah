<?php

namespace App\Services\Ai;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * تحليلات ومؤشرات ذكية للإيجارات والمشتريات + ملخّص تنبؤي بالذكاء الاصطناعي.
 * المؤشرات محسوبة من البيانات (موثوقة)؛ الملخّص النصّي اختياري عبر OpenAI.
 */
class AnalyticsService
{
    /** التدفق النقدي المتوقّع (دفعات غير مسدَّدة) لكل شهر للأشهر القادمة. */
    public function cashFlowProjection(int $months = 12): array
    {
        $start = Carbon::today()->startOfMonth();
        $end = $start->copy()->addMonths($months)->endOfMonth();

        $rows = DB::table('shop_rentpay')
            ->where('is_paid', 0)
            ->whereBetween('rentpay_dt', [$start->toDateString(), $end->toDateString()])
            ->selectRaw("DATE_FORMAT(rentpay_dt, '%Y-%m') ym, SUM(rentpay_price) amount, COUNT(*) c")
            ->groupBy('ym')->pluck('amount', 'ym');

        $out = [];
        for ($i = 0; $i < $months; $i++) {
            $m = $start->copy()->addMonths($i)->format('Y-m');
            $out[] = ['month' => $m, 'amount' => (float) ($rows[$m] ?? 0)];
        }
        return $out;
    }

    /** إجمالي الإيرادات المتوقّعة (كل الدفعات غير المسدَّدة المستقبلية). */
    public function expectedRevenue(): float
    {
        return (float) DB::table('shop_rentpay')
            ->where('is_paid', 0)
            ->where('rentpay_dt', '>=', Carbon::today()->toDateString())
            ->sum('rentpay_price');
    }

    /** نسبة التحصيل = المسدَّد ÷ الإجمالي. */
    public function collectionRate(): array
    {
        $paid = (float) DB::table('shop_rentpay')->where('is_paid', 1)->sum('rentpay_price');
        $total = (float) DB::table('shop_rentpay')->sum('rentpay_price');
        $rate = $total > 0 ? round($paid / $total * 100) : 0;

        return ['paid' => $paid, 'total' => $total, 'unpaid' => $total - $paid, 'rate' => $rate];
    }

    /** عقود/محلات عالية المخاطر (الأكثر دفعات متأخرة غير مسدَّدة). */
    public function highRiskShops(int $limit = 10): \Illuminate\Support\Collection
    {
        return DB::table('shop_rentpay as rp')
            ->leftJoin('shop as s', 's.shop_id', '=', 'rp.shop_id')
            ->where('rp.is_paid', 0)
            ->where('rp.rentpay_dt', '<', Carbon::today()->toDateString())
            ->selectRaw('rp.shop_id, COALESCE(s.shop_name, CONCAT("محل #", rp.shop_id)) name, COUNT(*) overdue_count, SUM(rp.rentpay_price) overdue_amount')
            ->groupBy('rp.shop_id', 's.shop_name')
            ->orderByDesc('overdue_count')
            ->limit($limit)->get();
    }

    /** اتجاه المشتريات الشهري (آخر N شهر). */
    public function purchaseTrend(int $months = 12): array
    {
        $start = Carbon::today()->startOfMonth()->subMonths($months - 1);

        $rows = DB::table('purchase')
            ->whereNotNull('purchase_dt')
            ->where('purchase_dt', '>=', $start->toDateString())
            ->selectRaw("DATE_FORMAT(purchase_dt, '%Y-%m') ym, SUM(purchase_price) amount, COUNT(*) c")
            ->groupBy('ym')->pluck('amount', 'ym');

        $out = [];
        for ($i = 0; $i < $months; $i++) {
            $m = $start->copy()->addMonths($i)->format('Y-m');
            $out[] = ['month' => $m, 'amount' => (float) ($rows[$m] ?? 0)];
        }
        return $out;
    }

    /** ملخّص تنبؤي ذكي بالـAI (اختياري، مُخزَّن مؤقتاً 6 ساعات). فارغ عند تعذّر الاتصال. */
    public function aiInsight(array $metrics): ?string
    {
        $cfg = (array) config('ai.openai');
        if (empty($cfg['api_key'])) {
            return null;
        }

        $key = 'ai_insight_' . md5(json_encode($metrics));

        return Cache::remember($key, now()->addHours(6), function () use ($cfg, $metrics) {
            try {
                $http = new Client(['base_uri' => rtrim($cfg['base_url'], '/') . '/', 'timeout' => 30]);
                $prompt = "أنت محلل مالي. بناءً على هذه المؤشرات لنظام إيجارات/مشتريات، اكتب 3-4 جُمل عربية موجزة: ملاحظات وتوصيات عملية للإدارة (تحصيل، تدفق نقدي، مخاطر). البيانات: " . json_encode($metrics, JSON_UNESCAPED_UNICODE);
                $res = $http->post('chat/completions', [
                    'headers' => ['Authorization' => 'Bearer ' . $cfg['api_key'], 'Content-Type' => 'application/json'],
                    'json' => [
                        'model' => $cfg['model'] ?? 'gpt-4o-mini',
                        'temperature' => 0.3,
                        'messages' => [['role' => 'user', 'content' => $prompt]],
                    ],
                ]);
                $body = json_decode((string) $res->getBody(), true);
                return trim($body['choices'][0]['message']['content'] ?? '') ?: null;
            } catch (\Throwable $e) {
                return null;
            }
        });
    }
}
