<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * مسار ملف العقد (PDF) المستورد بالذكاء الاصطناعي — يُحفَظ على قرص ai_private
 * ويُربَط بالعقد ليظهر «تحميل العقد» في صفحة سداد المحل (طلب العميل).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shop_rent') || Schema::hasColumn('shop_rent', 'ai_contract_file')) {
            return;
        }
        Schema::table('shop_rent', function (Blueprint $table) {
            $table->string('ai_contract_file', 500)->nullable();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('shop_rent') && Schema::hasColumn('shop_rent', 'ai_contract_file')) {
            Schema::table('shop_rent', function (Blueprint $table) {
                $table->dropColumn('ai_contract_file');
            });
        }
    }
};
