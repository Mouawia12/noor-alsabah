<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * بيانات وصفية لكود الفرع (شاشة إدارة أكواد المحلات):
 * حالة الكود (فعّال/غير فعّال) + المستخدم المنشئ + تاريخ الإنشاء.
 * العمود shop_code نفسه أُضيف في هجرة سابقة. الجدول shop من Oracle.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shop')) {
            return;
        }

        Schema::table('shop', function (Blueprint $table) {
            if (! Schema::hasColumn('shop', 'shop_code_active')) {
                $table->tinyInteger('shop_code_active')->default(1);
            }
            if (! Schema::hasColumn('shop', 'shop_code_by')) {
                $table->unsignedBigInteger('shop_code_by')->nullable();
            }
            if (! Schema::hasColumn('shop', 'shop_code_at')) {
                $table->timestamp('shop_code_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('shop')) {
            return;
        }
        Schema::table('shop', function (Blueprint $table) {
            foreach (['shop_code_active', 'shop_code_by', 'shop_code_at'] as $col) {
                if (Schema::hasColumn('shop', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
