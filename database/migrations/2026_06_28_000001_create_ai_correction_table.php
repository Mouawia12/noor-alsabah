<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تصحيحات المستخدم على الحقول المستخرجة — أساس التعلّم المستمر.
     */
    public function up(): void
    {
        if (Schema::hasTable('ai_correction')) {
            return;
        }

        Schema::create('ai_correction', function (Blueprint $table) {
            $table->id();
            $table->string('kind')->index();            // purchase / rent
            $table->string('entity_key')->nullable()->index(); // مثل الرقم الضريبي للمورد
            $table->string('field');
            $table->text('extracted_value')->nullable();
            $table->text('corrected_value')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_correction');
    }
};
