<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * سندات القبض الإلكترونية: تُحفظ فعلياً (كانت تُولَّد كـPDF غير محفوظ)، مرقّمة رسمياً،
     * ومرتبطة بالمحل + العقد + الدفعة. سجل دائم مستقل لكل عملية قبض.
     */
    public function up(): void
    {
        if (Schema::hasTable('shop_receipt')) {
            return;
        }

        Schema::create('shop_receipt', function (Blueprint $table) {
            $table->id('receipt_id');
            // رقم السند الرسمي المتسلسل — يُشتق من المفتاح الأساسي بعد الإدراج (فريد وتسلسلي).
            $table->string('receipt_no', 40)->nullable()->unique();
            $table->unsignedBigInteger('shop_id')->index();
            $table->unsignedBigInteger('shop_rent_id')->nullable()->index();
            $table->unsignedBigInteger('rentpay_id')->nullable()->index();
            $table->decimal('amount', 15, 2);                    // المبلغ المقبوض
            $table->string('method', 30)->nullable();            // نقدي/تحويل/شبكة...
            $table->timestamp('paid_at')->nullable();            // تاريخ القبض الفعلي
            $table->string('note', 500)->nullable();
            $table->unsignedBigInteger('create_user')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_receipt');
    }
};
