<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rent_contract_import_batch')) {
            return;
        }

        Schema::create('rent_contract_import_batch', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('file_hash')->index();
            $table->string('status')->default('pending');
            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedInteger('processed_items')->default(0);
            $table->unsignedInteger('failed_items')->default(0);
            $table->string('engine')->nullable();
            $table->string('model')->nullable();
            $table->unsignedInteger('ai_calls')->default(0);
            $table->text('error_reason')->nullable();
            $table->unsignedBigInteger('create_user')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_contract_import_batch');
    }
};
