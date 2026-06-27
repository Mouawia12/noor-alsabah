<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * سجل تدقيق كامل لكل عمليات الذكاء الاصطناعي والمراجعة/الاعتماد.
     * مطلب صريح من العميل: الاحتفاظ بسجل كامل للتعديلات ونتائج المعالجة.
     */
    public function up(): void
    {
        if (Schema::hasTable('ai_audit_log')) {
            return;
        }

        Schema::create('ai_audit_log', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type');   // نوع الكيان (item/purchase/contract...)
            $table->string('auditable_id');     // معرّفه
            // extracted/edited/approved/rejected/reprocessed/created/failed
            $table->string('action')->index();
            $table->json('changes')->nullable(); // before/after
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_audit_log');
    }
};
