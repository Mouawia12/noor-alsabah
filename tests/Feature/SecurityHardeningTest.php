<?php

// يتحقق من المعالجات الأمنية والإعدادية التي طُبّقت في المراجعة.

test('oracle credentials are no longer hardcoded', function () {
    $oracle = config('database.connections.oracle');

    // القيم المسرّبة سابقاً يجب ألا تظهر في الإعداد
    expect($oracle['host'])->not->toBe('MINSDB03.GOV.PS');
    expect($oracle['password'])->not->toBe('web_2008');
    expect($oracle['username'])->not->toBe('MOL_PROJ_PORTAL');
});

test('queue retry_after exceeds the longest job timeout', function () {
    // أطول مهمة ProcessPurchaseBatchJob::$timeout = 1800
    expect(config('queue.connections.database.retry_after'))->toBeGreaterThan(1800);
});

test('clear route is closed to guests', function () {
    // محمي بـ auth → زائر يُحوَّل لتسجيل الدخول، لا ينفّذ artisan
    $this->get('/clear')->assertRedirect('/login');
});

test('image upload route requires authentication', function () {
    $this->post('/images_upload', [])->assertRedirect('/login');
});
