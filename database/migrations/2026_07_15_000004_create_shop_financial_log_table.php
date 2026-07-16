<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * السجل المالي لكل محل: دفتر يقيّد كل عملية إيجار (استحقاق دفعة / قبض / تعديل)،
     * مرتبط بالمحل + العقد + الدفعة + السند. مرجع موحّد للتقارير المالية.
     */
    public function up(): void
    {
        if (Schema::hasTable('shop_financial_log')) {
            return;
        }

        Schema::create('shop_financial_log', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('shop_id')->index();
            $table->unsignedBigInteger('shop_rent_id')->nullable()->index();
            $table->unsignedBigInteger('rentpay_id')->nullable()->index();
            $table->unsignedBigInteger('receipt_id')->nullable()->index();
            $table->string('direction', 10);                     // credit (قبض) / debit (استحقاق)
            $table->string('event', 30);                         // payment / due / adjustment / reversal
            $table->decimal('amount', 15, 2);
            $table->string('description', 500)->nullable();
            $table->unsignedBigInteger('create_user')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_financial_log');
    }
};
