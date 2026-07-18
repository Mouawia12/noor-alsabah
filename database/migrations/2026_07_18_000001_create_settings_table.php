<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول إعدادات النظام (مفتاح/قيمة) — يتجاوز قيم .env لمفاتيح ونماذج الذكاء الاصطناعي.
 * محميّ: لا يُنشأ إن كان موجوداً (بعض البيئات أنشأته يدوياً).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            return;
        }

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    public function down(): void
    {
        // لا نُسقط الجدول تلقائياً حفاظاً على الإعدادات المخزّنة.
    }
};
