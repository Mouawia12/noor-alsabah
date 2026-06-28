<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('purchase_import_item')) {
            return;
        }

        Schema::create('purchase_import_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('purchase_import_batch')->cascadeOnDelete();
            $table->unsignedInteger('page_from')->nullable();
            $table->unsignedInteger('page_to')->nullable();
            $table->text('source_file_path')->nullable(); // مسارات صفحات الفاتورة (قد تتجاوز 255 محرفاً)
            $table->string('page_hash')->nullable()->index(); // منع تكرار الصفحة
            $table->json('extracted_json')->nullable();        // الاستخراج الخام (مرونة مستقبلية)
            $table->decimal('confidence', 5, 4)->nullable();   // ثقة عامة
            $table->json('field_confidence')->nullable();      // ثقة لكل حقل
            // pending/processing/needs_review/approved/rejected/failed
            $table->string('status')->default('pending')->index();
            $table->text('error_reason')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->unsignedBigInteger('duplicate_of_purchase_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable()->index(); // بعد الاعتماد
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_import_item');
    }
};
