<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * توسيع عمود source_file_path في جداول الاستيراد القائمة (Oracle)
 * من VARCHAR2(255) إلى CLOB، لأن المسارات المجمّعة لصفحات متعددة تتجاوز 255 محرفاً.
 * على القواعد الأخرى يكون العمود text أصلاً (هجرة الإنشاء)، فتُتجاوز هذه الهجرة.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'oracle') {
            return; // text بالفعل عبر هجرة الإنشاء
        }

        foreach (['purchase_import_item', 'rent_contract_import_item'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'source_file_path')) {
                continue;
            }
            try {
                // Oracle لا يسمح بـ MODIFY مباشرة من VARCHAR2 إلى CLOB → عمود مؤقت
                DB::statement("ALTER TABLE {$table} ADD (source_file_path_tmp CLOB)");
                DB::statement("UPDATE {$table} SET source_file_path_tmp = source_file_path");
                DB::statement("ALTER TABLE {$table} DROP COLUMN source_file_path");
                DB::statement("ALTER TABLE {$table} RENAME COLUMN source_file_path_tmp TO source_file_path");
            } catch (\Throwable $e) {
                // إن سبق تطبيقها أو اختلفت البنية، لا نوقف بقية الهجرات
                logger()->warning('widen source_file_path skipped for ' . $table . ': ' . $e->getMessage());
            }
        }
    }

    public function down(): void
    {
        // لا تراجع آمن لتغيير نوع العمود.
    }
};
