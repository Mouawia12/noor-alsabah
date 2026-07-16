<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إثراء العقد (shop_rent) بحقول العقد الموحّد الناقصة: نوع العقد، بيانات الوحدة،
     * دورة السداد، القيمة السنوية/الأولى، هويات الطرفين. إضافات آمنة على جدول Oracle موروث.
     */
    public function up(): void
    {
        if (! Schema::hasTable('shop_rent')) {
            return;
        }

        Schema::table('shop_rent', function (Blueprint $table) {
            $cols = [
                'contract_type'  => fn () => $table->string('contract_type', 20)->nullable(),   // new / renewal
                'unit_no'        => fn () => $table->string('unit_no', 100)->nullable(),
                'unit_type'      => fn () => $table->string('unit_type', 100)->nullable(),
                'floor_no'       => fn () => $table->string('floor_no', 50)->nullable(),
                'area'           => fn () => $table->decimal('area', 10, 2)->nullable(),
                'payment_cycle'  => fn () => $table->string('payment_cycle', 20)->nullable(),    // monthly/quarterly/semi/annual
                'annual_value'   => fn () => $table->decimal('annual_value', 15, 2)->nullable(),
                'total_value'    => fn () => $table->decimal('total_value', 15, 2)->nullable(),
                'first_payment'  => fn () => $table->decimal('first_payment', 15, 2)->nullable(),
                'tenant_id_no'   => fn () => $table->string('tenant_id_no', 50)->nullable(),
                'tenant_mobile'  => fn () => $table->string('tenant_mobile', 50)->nullable(),
                'landlord_id_no' => fn () => $table->string('landlord_id_no', 50)->nullable(),
            ];
            foreach ($cols as $name => $make) {
                if (! Schema::hasColumn('shop_rent', $name)) {
                    $make();
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
                'contract_type', 'unit_no', 'unit_type', 'floor_no', 'area', 'payment_cycle',
                'annual_value', 'total_value', 'first_payment', 'tenant_id_no', 'tenant_mobile', 'landlord_id_no',
            ] as $col) {
                if (Schema::hasColumn('shop_rent', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
