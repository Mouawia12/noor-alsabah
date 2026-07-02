<?php

use App\Models\User;
use App\Notifications\BatchCompletedNotification;
use App\Notifications\RentAlertNotification;
use App\Support\NotificationPresenter;

/**
 * نظام الإشعارات: القائمة المنسدلة الحيّة (recent) + تعليم كمقروء + عرض موحّد لكل الأنواع.
 */
it('returns recent notifications with unread count as json', function () {
    $user = User::factory()->create();
    $user->notify(new BatchCompletedNotification('purchase', 'invoices.pdf', 10, 7, 3, '/x'));

    $res = $this->actingAs($user)->getJson(route('dashboard.notifications.recent'));

    $res->assertOk()->assertJson(['unread' => 1]);
    expect($res->json('html'))->toContain('اكتملت معالجة الفواتير');
});

it('marks a single notification as read via ajax and decrements the counter', function () {
    $user = User::factory()->create();
    $user->notify(new BatchCompletedNotification('rent', 'contracts.pdf', 5, 5, 0, '/y'));
    $id = $user->notifications()->first()->id;

    expect($user->unreadNotifications()->count())->toBe(1);

    $res = $this->actingAs($user)->postJson(route('dashboard.notifications.read', $id));

    $res->assertOk()->assertJson(['ok' => true, 'unread' => 0]);
    expect($user->fresh()->unreadNotifications()->count())->toBe(0);
});

it('marks all notifications as read via ajax', function () {
    $user = User::factory()->create();
    $user->notify(new BatchCompletedNotification('purchase', 'a.pdf', 1, 1, 0, '/a'));
    $user->notify(new BatchCompletedNotification('purchase', 'b.pdf', 2, 2, 0, '/b'));
    expect($user->unreadNotifications()->count())->toBe(2);

    $this->actingAs($user)->postJson(route('dashboard.notifications.read_all'))
        ->assertOk()->assertJson(['ok' => true, 'unread' => 0]);

    expect($user->fresh()->unreadNotifications()->count())->toBe(0);
});

it('presents each notification type with its own title and icon (no mislabeling)', function () {
    $user = User::factory()->create();
    $user->notify(new BatchCompletedNotification('purchase', 'inv.pdf', 3, 2, 1, '/r'));
    $user->notify(new RentAlertNotification(['upcoming' => 2, 'overdue' => 1, 'expiring' => 0], ['عقد #5'], '/rent'));

    $byType = $user->notifications()->get()
        ->map(fn ($n) => NotificationPresenter::present($n))
        ->keyBy(fn ($it) => str_contains($it['title'], 'الإيجارات') ? 'rent' : 'purchase');

    // نوع الفواتير: أيقونة الفاتورة + لون تحذيري (لوجود مرفوضة)
    expect($byType['purchase']['title'])->toBe('اكتملت معالجة الفواتير');
    expect($byType['purchase']['icon'])->toBe('fa-file-invoice');
    expect($byType['purchase']['color'])->toBe('warning');

    // نوع الإيجارات: عنوان مختلف + لون خطر (لوجود متأخرة) + عيّنات
    expect($byType['rent']['title'])->toBe('تنبيهات الإيجارات');
    expect($byType['rent']['color'])->toBe('danger');
    expect($byType['rent']['samples'])->toContain('عقد #5');
});
