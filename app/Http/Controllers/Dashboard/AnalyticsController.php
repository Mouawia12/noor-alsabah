<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Ai\AnalyticsService;

/**
 * لوحة التحليلات والتنبؤات الذكية (إيجارات + مشتريات).
 */
class AnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $analytics) {}

    public function index()
    {
        $page_title = 'التحليلات والتنبؤات الذكية';

        $cashFlow      = $this->analytics->cashFlowProjection(12);
        $expectedRev   = $this->analytics->expectedRevenue();
        $collection    = $this->analytics->collectionRate();
        $highRisk      = $this->analytics->highRiskShops(10);
        $purchaseTrend = $this->analytics->purchaseTrend(12);

        // ملخّص ذكي بالـAI بناءً على المؤشرات الأساسية
        $insight = $this->analytics->aiInsight([
            'expected_revenue'    => round($expectedRev),
            'collection_rate_pct' => $collection['rate'],
            'unpaid_total'        => round($collection['unpaid']),
            'high_risk_shops'     => $highRisk->count(),
            'next_3_months'       => array_slice(array_map(fn ($r) => $r['amount'], $cashFlow), 0, 3),
        ]);

        $maxCash = max(1, max(array_map(fn ($r) => $r['amount'], $cashFlow)));
        $maxPur  = max(1, max(array_map(fn ($r) => $r['amount'], $purchaseTrend)));

        return view('dashboard.analytics.index', compact(
            'page_title', 'cashFlow', 'expectedRev', 'collection', 'highRisk', 'purchaseTrend', 'insight', 'maxCash', 'maxPur'
        ));
    }
}
