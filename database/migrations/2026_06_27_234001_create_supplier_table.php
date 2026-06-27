<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('supplier')) {
            return;
        }

        Schema::create('supplier', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('name');
            $table->string('name_normalized')->nullable()->index(); // للمطابقة الضبابية
            $table->string('tax_number')->nullable()->index();
            $table->string('phone')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('create_user')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
