<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * أكواد الفروع: عمود رمز خاص لكل محل/فرع يظهر بجانب اسمه ويُستخدم في البحث/الاختيار.
 * الجدول shop قادم من Oracle؛ نضيف العمود فقط إن وُجد الجدول ولم يوجد العمود.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shop') || Schema::hasColumn('shop', 'shop_code')) {
            return;
        }

        Schema::table('shop', function (Blueprint $table) {
            $table->string('shop_code', 50)->nullable()->index();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('shop') && Schema::hasColumn('shop', 'shop_code')) {
            Schema::table('shop', function (Blueprint $table) {
                $table->dropColumn('shop_code');
            });
        }
    }
};
