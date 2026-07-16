<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * طبقة مطابقة الدفعات بالذكاء الاصطناعي: تخزّن دفعة واردة غير مربوطة، واقتراح المطابقة
     * (العقد/المحل/الدفعة المستحقة) بنسبة ثقة وسبب وحالة، بانتظار تأكيد المستخدم قبل الاعتماد.
     */
    public function up(): void
    {
        if (Schema::hasTable('rent_payment_match_item')) {
            return;
        }

        Schema::create('rent_payment_match_item', function (Blueprint $table) {
            $table->id();

            // بيانات الدفعة الواردة كما وردت (خام) + الحقول المفتاحية للمطابقة
            $table->json('raw')->nullable();
            $table->string('contract_no', 255)->nullable()->index();
            $table->string('tenant_name', 255)->nullable();
            $table->string('tenant_id_no', 50)->nullable();
            $table->string('unit_no', 100)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('due_date')->nullable();

            // اقتراح المطابقة (لا يُعتمد آلياً)
            $table->unsignedBigInteger('matched_shop_id')->nullable()->index();
            $table->unsignedBigInteger('matched_shop_rent_id')->nullable()->index();
            $table->unsignedBigInteger('matched_rentpay_id')->nullable()->index();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->text('match_reason')->nullable();
            // matched / orphan / underpaid / overpaid / duplicate
            $table->string('match_status', 20)->default('orphan')->index();

            // دورة المراجعة (نفس نمط عناصر الاستيراد): needs_review / approved / rejected
            $table->string('status', 20)->default('needs_review')->index();
            $table->unsignedBigInteger('created_rentpay_id')->nullable();   // الدفعة التي أُنشئت/رُبطت بعد الاعتماد
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('create_user')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_payment_match_item');
    }
};
