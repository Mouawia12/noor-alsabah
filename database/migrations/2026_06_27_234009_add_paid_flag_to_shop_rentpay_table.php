<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة علامة الدفع لجدول دفعات الإيجار (لتمييز المستحق/المتأخر/المسدَّد في التنبيهات).
     * محميّ ضد غياب الجدول/تكرار الأعمدة.
     */
    public function up(): void
    {
        if (! Schema::hasTable('shop_rentpay')) {
            return;
        }

        Schema::table('shop_rentpay', function (Blueprint $table) {
            if (! Schema::hasColumn('shop_rentpay', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->index();
            }
            if (! Schema::hasColumn('shop_rentpay', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('shop_rentpay')) {
            return;
        }

        Schema::table('shop_rentpay', function (Blueprint $table) {
            foreach (['is_paid', 'paid_at'] as $col) {
                if (Schema::hasColumn('shop_rentpay', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
