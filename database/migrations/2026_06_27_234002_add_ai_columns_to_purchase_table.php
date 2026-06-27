<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * توسعة جدول المشتريات الحالي بحقول الاستخراج الذكي.
     * محميّ: لا يفعل شيئاً إن لم يوجد الجدول، ولا يكرّر عموداً موجوداً.
     */
    public function up(): void
    {
        if (! Schema::hasTable('purchase')) {
            return;
        }

        Schema::table('purchase', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable()->index();
            }
            if (! Schema::hasColumn('purchase', 'currency')) {
                $table->string('currency', 10)->nullable();
            }
            if (! Schema::hasColumn('purchase', 'amount_before_tax')) {
                $table->decimal('amount_before_tax', 15, 2)->nullable();
            }
            if (! Schema::hasColumn('purchase', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->nullable();
            }
            if (! Schema::hasColumn('purchase', 'import_item_id')) {
                $table->unsignedBigInteger('import_item_id')->nullable()->index(); // مصدر الاستخراج
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('purchase')) {
            return;
        }

        Schema::table('purchase', function (Blueprint $table) {
            foreach (['supplier_id', 'currency', 'amount_before_tax', 'tax_amount', 'import_item_id'] as $col) {
                if (Schema::hasColumn('purchase', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
