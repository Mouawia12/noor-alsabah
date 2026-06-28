<?php

// التسجيل الذاتي معطّل عمداً: المستخدمون يُنشأون من لوحة التحكم بصلاحيات محددة.

test('registration screen is disabled', function () {
    $this->get('/register')->assertNotFound();
});

test('self-registration is rejected and creates no user', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
});
