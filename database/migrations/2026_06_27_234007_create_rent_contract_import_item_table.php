<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rent_contract_import_item')) {
            return;
        }

        Schema::create('rent_contract_import_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('rent_contract_import_batch')->cascadeOnDelete();
            $table->unsignedInteger('page_from')->nullable();
            $table->unsignedInteger('page_to')->nullable();
            $table->string('source_file_path')->nullable();
            $table->string('page_hash')->nullable()->index();
            $table->json('extracted_json')->nullable();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->json('field_confidence')->nullable();
            $table->string('status')->default('pending')->index();
            $table->text('error_reason')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->unsignedBigInteger('shop_id')->nullable()->index();      // العقار المرتبط
            $table->unsignedBigInteger('shop_rent_id')->nullable()->index(); // العقد بعد الاعتماد
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_contract_import_item');
    }
};
