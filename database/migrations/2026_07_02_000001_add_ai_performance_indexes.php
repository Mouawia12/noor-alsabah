<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * فهارس أداء لتسريع الاستعلامات المتكررة:
 * - قوائم المراجعة تُرشّح بـ status وتُرتّب بـ created_at → فهرس مركّب (status, created_at).
 * - النشاط الأخير يُرتّب بـ created_at → فهرس على العمود.
 * أسماء الفهارس قصيرة (≤30 محرفاً) لتوافق Oracle.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->addIndex('purchase_import_item', ['status', 'created_at'], 'pii_status_created_idx');
        $this->addIndex('rent_contract_import_item', ['status', 'created_at'], 'rcii_status_created_idx');
        $this->addIndex('ai_audit_log', ['created_at'], 'aal_created_idx');
    }

    public function down(): void
    {
        $this->dropIndex('purchase_import_item', 'pii_status_created_idx');
        $this->dropIndex('rent_contract_import_item', 'rcii_status_created_idx');
        $this->dropIndex('ai_audit_log', 'aal_created_idx');
    }

    private function addIndex(string $table, array $cols, string $name): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }
        try {
            Schema::table($table, fn (Blueprint $t) => $t->index($cols, $name));
        } catch (\Throwable $e) {
            // الفهرس موجود مسبقاً أو غير مدعوم — تجاهل بأمان.
        }
    }

    private function dropIndex(string $table, string $name): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }
        try {
            Schema::table($table, fn (Blueprint $t) => $t->dropIndex($name));
        } catch (\Throwable $e) {
            //
        }
    }
};
