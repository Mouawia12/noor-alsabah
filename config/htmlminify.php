<?php
return [
    'default' => env('HTML_MINIFY', true),
    // exclude route name for exclude from minify
    // ملاحظة مهمّة: مصغّر HTML يحذف الأسطر الجديدة فيبتلع تعليقات JS من نوع //
    // ويكسر السكربتات المضمّنة («Unexpected end of input»). لذلك نستثني صفحات
    // الذكاء الاصطناعي التي تعتمد على سكربتات مضمّنة حرجة (معالجة/مراجعة/رفع/سداد).
    'exclude_route' => [
        'dashboard.emps.upd_role',
        // معالجة لحظية (السائق المضمّن)
        'dashboard.rent.ai.batch',
        'dashboard.purchase.ai.batch',
        // مراجعة واعتماد (select2 + أزرار الاعتماد/الترحيل)
        'dashboard.rent.ai.review',
        'dashboard.purchase.ai.review',
        // رفع الملفات (سكربت الرفع/التقدّم)
        'dashboard.rent.ai.index',
        'dashboard.purchase.ai.index',
        // شاشات السداد والعناصر الفاشلة
        'dashboard.shop.payments',
        'dashboard.shop.financial_report',
        'dashboard.rent.ai.failed',
        'dashboard.purchase.ai.failed',
        // صفحة إعدادات مفاتيح الـ API (سكربت فحص الاتصال المضمّن)
        'dashboard.settings.index',
        // سجل ونتائج عقود الإيجار (سكربت التعديل داخل الخلية)
        'dashboard.rent.ai.batches',
        'dashboard.rent.ai.batch.results',
    ],
];
