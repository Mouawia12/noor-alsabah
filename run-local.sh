#!/bin/bash
# تشغيل النظام محلياً: الخادم + عامل الطابور + المجدول
cd "$(dirname "$0")"
echo "تشغيل نور الصباح محلياً على http://127.0.0.1:8009 ..."
php artisan queue:work --tries=3 > storage/logs/queue.log 2>&1 &
echo "  - عامل الطابور يعمل (سجل: storage/logs/queue.log)"
php artisan schedule:work > storage/logs/schedule.log 2>&1 &
echo "  - المجدول يعمل (سجل: storage/logs/schedule.log)"
php artisan serve --host=127.0.0.1 --port=8009
