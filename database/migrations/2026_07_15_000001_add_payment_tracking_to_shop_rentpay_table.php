<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * متابعة سداد العقود: ربط الدفعة بالعقد (لا بالمحل فقط)، وحقول تتبّع السداد.
     * الجدول shop_rentpay موروث من Oracle — إضافات آمنة محميّة ضد غياب الجدول/تكرار الأعمدة،
     * ولا تكسر المخطط الحالي (is_paid/paid_at تبقى وتُزامَن مع status/paid_amount).
     */
    public function up(): void
    {
        if (! Schema::hasTable('shop_rentpay')) {
            return;
        }

        Schema::table('shop_rentpay', function (Blueprint $table) {
            // ربط الدفعة بعقدها المحدَّد (الفجوة الجوهرية: كانت مرتبطة بالمحل فقط)
            if (! Schema::hasColumn('shop_rentpay', 'shop_rent_id')) {
                $table->unsignedBigInteger('shop_rent_id')->nullable()->index();
            }
            // رقم الدفعة المتسلسل داخل العقد
            if (! Schema::hasColumn('shop_rentpay', 'seq_no')) {
                $table->unsignedInteger('seq_no')->nullable();
            }
            // تاريخ إصدار الدفعة وتاريخ الاستحقاق الهجري (الميلادي = rentpay_dt)
            if (! Schema::hasColumn('shop_rentpay', 'issue_date')) {
                $table->date('issue_date')->nullable();
            }
            if (! Schema::hasColumn('shop_rentpay', 'due_date_hijri')) {
                $table->string('due_date_hijri', 20)->nullable();
            }
            // مكوّنات المبلغ: rentpay_price = الإجمالي المستحق؛ هذه تفصيلية للعرض/السند
            if (! Schema::hasColumn('shop_rentpay', 'rent_amount')) {
                $table->decimal('rent_amount', 15, 2)->nullable();
            }
            if (! Schema::hasColumn('shop_rentpay', 'vat_amount')) {
                $table->decimal('vat_amount', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('shop_rentpay', 'services_amount')) {
                $table->decimal('services_amount', 15, 2)->default(0);
            }
            // المبلغ المدفوع فعلياً (يدعم السداد الجزئي)
            if (! Schema::hasColumn('shop_rentpay', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0);
            }
            // حالة السداد المخزّنة: paid / partial / unpaid (المتأخر يُشتق من التاريخ ميلادياً)
            if (! Schema::hasColumn('shop_rentpay', 'status')) {
                $table->string('status', 20)->default('unpaid')->index();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('shop_rentpay')) {
            return;
        }

        Schema::table('shop_rentpay', function (Blueprint $table) {
            foreach ([
                'shop_rent_id', 'seq_no', 'issue_date', 'due_date_hijri',
                'rent_amount', 'vat_amount', 'services_amount', 'paid_amount', 'status',
            ] as $col) {
                if (Schema::hasColumn('shop_rentpay', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
