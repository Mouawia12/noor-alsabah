<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * توسعة جدول الإيجارات الحالي ببيانات العقد المستخرجة.
     * محميّ ضد غياب الجدول/تكرار الأعمدة.
     */
    public function up(): void
    {
        if (! Schema::hasTable('shop_rent')) {
            return;
        }

        Schema::table('shop_rent', function (Blueprint $table) {
            $cols = [
                'contract_no'       => fn () => $table->string('contract_no')->nullable()->index(),
                'start_date'        => fn () => $table->date('start_date')->nullable(),
                'end_date'          => fn () => $table->date('end_date')->nullable(),
                'landlord'          => fn () => $table->string('landlord')->nullable(),
                'tenant'            => fn () => $table->string('tenant')->nullable(),
                'property_info'     => fn () => $table->text('property_info')->nullable(),
                'rent_value'        => fn () => $table->decimal('rent_value', 15, 2)->nullable(),
                'payments_count'    => fn () => $table->unsignedInteger('payments_count')->nullable(),
                'renewal_terms'     => fn () => $table->text('renewal_terms')->nullable(),
                'termination_terms' => fn () => $table->text('termination_terms')->nullable(),
                'import_item_id'    => fn () => $table->unsignedBigInteger('import_item_id')->nullable()->index(),
            ];

            foreach ($cols as $name => $add) {
                if (! Schema::hasColumn('shop_rent', $name)) {
                    $add();
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('shop_rent')) {
            return;
        }

        Schema::table('shop_rent', function (Blueprint $table) {
            foreach ([
                'contract_no', 'start_date', 'end_date', 'landlord', 'tenant',
                'property_info', 'rent_value', 'payments_count', 'renewal_terms',
                'termination_terms', 'import_item_id',
            ] as $col) {
                if (Schema::hasColumn('shop_rent', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
