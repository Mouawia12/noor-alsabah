<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\AiDashboardStats;

/**
 * لوحة معلومات الذكاء الاصطناعي — نظرة شاملة على المشتريات والإيجارات المعالَجة
 * بمؤشّرات مخزّنة مؤقتاً ونشاط حيّ.
 */
class AiDashboardController extends Controller
{
    public function overview()
    {
        $page_title = 'لوحة معلومات الذكاء الاصطناعي';
        $stats = AiDashboardStats::get();

        return view('dashboard.ai.overview', compact('page_title', 'stats'));
    }
}
