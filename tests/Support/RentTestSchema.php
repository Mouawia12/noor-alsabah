<?php

namespace Tests\Support;

use Illuminate\Support\Facades\Schema;

/**
 * ينشئ نسخ SQLite من الجداول الموروثة (shop/shop_rent/shop_rentpay) بأعمدتها الكاملة
 * بما فيها أعمدة متابعة السداد الجديدة — لأن migrations هذه الجداول محميّة بـ hasTable
 * فتُتخطّى في اختبارات SQLite. الجداول الجديدة (receipt/financial_log/match_item) تُنشئها الهجرات.
 */
class RentTestSchema
{
    public static function install(): void
    {
        Schema::dropIfExists('shop');
        Schema::create('shop', function ($t) {
            $t->id('shop_id');
            $t->string('shop_name')->nullable();
            $t->string('shop_code')->nullable();
        });

        Schema::dropIfExists('shop_rent');
        Schema::create('shop_rent', function ($t) {
            $t->id('shop_rent_id');
            $t->unsignedBigInteger('shop_id')->nullable();
            $t->string('rent_no')->nullable();
            $t->string('contract_no')->nullable();
            $t->string('contract_type')->nullable();
            $t->string('tenant')->nullable();
            $t->string('tenant_id_no')->nullable();
            $t->string('tenant_mobile')->nullable();
            $t->string('landlord')->nullable();
            $t->string('unit_no')->nullable();
            $t->string('floor_no')->nullable();
            $t->decimal('area', 10, 2)->nullable();
            $t->string('payment_cycle')->nullable();
            $t->date('start_date')->nullable();
            $t->date('end_date')->nullable();
            $t->decimal('rent_value', 15, 2)->nullable();
            $t->decimal('annual_value', 15, 2)->nullable();
            $t->decimal('total_value', 15, 2)->nullable();
            $t->decimal('first_payment', 15, 2)->nullable();
            $t->unsignedInteger('payments_count')->nullable();
            $t->timestamps();
        });

        Schema::dropIfExists('shop_rentpay');
        Schema::create('shop_rentpay', function ($t) {
            $t->id('rentpay_id');
            $t->unsignedBigInteger('shop_id')->nullable();
            $t->unsignedBigInteger('shop_rent_id')->nullable();
            $t->unsignedInteger('seq_no')->nullable();
            $t->date('rentpay_dt')->nullable();
            $t->date('issue_date')->nullable();
            $t->string('due_date_hijri')->nullable();
            $t->decimal('rentpay_price', 15, 2)->nullable();
            $t->decimal('rent_amount', 15, 2)->nullable();
            $t->decimal('vat_amount', 15, 2)->default(0);
            $t->decimal('services_amount', 15, 2)->default(0);
            $t->decimal('paid_amount', 15, 2)->default(0);
            $t->string('status', 20)->default('unpaid');
            $t->boolean('is_paid')->default(false);
            $t->timestamp('paid_at')->nullable();
            $t->string('rentpay_note')->nullable();
            $t->unsignedBigInteger('create_user')->nullable();
            $t->unsignedBigInteger('update_user')->nullable();
            $t->timestamps();
        });
    }
}
