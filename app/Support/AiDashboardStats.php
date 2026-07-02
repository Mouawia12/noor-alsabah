<?php

namespace App\Support;

use App\Models\AiAuditLog;
use App\Models\PurchaseImportBatch;
use App\Models\PurchaseImportItem;
use App\Models\RentContractImportBatch;
use App\Models\RentContractImportItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * مؤشّرات لوحة معلومات الذكاء الاصطناعي (مشتريات + إيجارات + نشاط حيّ).
 * مخزّنة مؤقتاً (Cache) لمدة قصيرة لتخفيف الحِمل على القاعدة عند كل زيارة.
 */
class AiDashboardStats
{
    public const CACHE_KEY = 'ai.dashboard.stats';
    public const TTL = 60; // ثانية

    /** يُرجِع كل المؤشّرات (من الكاش إن وُجدت). */
    public static function get(): array
    {
        return Cache::remember(self::CACHE_KEY, self::TTL, fn () => self::compute());
    }

    /** يُبطِل الكاش فوراً (يُستدعى بعد ترحيل/اعتماد لتحديث الأرقام). */
    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function compute(): array
    {
        $pur  = self::countsByStatus(PurchaseImportItem::query());
        $rent = self::countsByStatus(RentContractImportItem::query());

        $purAccepted = ($pur['needs_review'] ?? 0) + ($pur['approved'] ?? 0);
        $purRejected = ($pur['failed'] ?? 0) + ($pur['rejected'] ?? 0);
        $purProcessed = array_sum($pur);

        $rentAccepted = ($rent['needs_review'] ?? 0) + ($rent['approved'] ?? 0);
        $rentRejected = ($rent['failed'] ?? 0) + ($rent['rejected'] ?? 0);
        $rentProcessed = array_sum($rent);

        return [
            'purchase' => [
                'batches'       => PurchaseImportBatch::count(),
                'processed'     => $purProcessed,
                'accepted'      => $purAccepted,
                'transferred'   => $pur['approved'] ?? 0,      // مُرحّلة للفرع
                'pending'       => $pur['needs_review'] ?? 0,  // بانتظار الترحيل
                'rejected'      => $purRejected,
                'success_rate'  => $purProcessed > 0 ? (int) round($purAccepted / $purProcessed * 100) : 0,
                'total_amount'  => self::guardedSum('purchase', 'purchase_price', 'import_item_id'),
            ],
            'rent' => [
                'batches'       => RentContractImportBatch::count(),
                'processed'     => $rentProcessed,
                'accepted'      => $rentAccepted,
                'approved'      => $rent['approved'] ?? 0,
                'pending'       => $rent['needs_review'] ?? 0,
                'rejected'      => $rentRejected,
                'success_rate'  => $rentProcessed > 0 ? (int) round($rentAccepted / $rentProcessed * 100) : 0,
                'payments'      => self::guardedCount('shop_rentpay', 'rentpay_note', 'مولّدة آلياً من العقد المستورد'),
            ],
            'activity' => self::recentActivity(),
        ];
    }

    /** توزيع الحالات كمصفوفة status=>count. */
    protected static function countsByStatus($query): array
    {
        return $query->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status')->toArray();
    }

    /** مجموع عمود محمي (الجدول قد لا يوجد في بعض البيئات/الاختبارات). */
    protected static function guardedSum(string $table, string $col, string $whereNotNull): float
    {
        try {
            return (float) DB::table($table)->whereNotNull($whereNotNull)->sum($col);
        } catch (\Throwable $e) {
            return 0.0;
        }
    }

    protected static function guardedCount(string $table, string $col, string $value): int
    {
        try {
            return (int) DB::table($table)->where($col, $value)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** آخر 12 حدثاً من سجل التدقيق، مُقدَّمة بعنوان وأيقونة ولون. */
    protected static function recentActivity(): array
    {
        return AiAuditLog::query()->latest('created_at')->limit(12)->get()
            ->map(fn ($log) => self::presentActivity($log))->all();
    }

    protected static function presentActivity(AiAuditLog $log): array
    {
        $isRent = str_contains($log->auditable_type, 'rent');
        $subject = $isRent ? 'عقد' : 'فاتورة';
        if (str_contains($log->auditable_type, 'batch')) {
            $subject = $isRent ? 'دفعة عقود' : 'دفعة فواتير';
        }

        [$label, $icon, $color] = match ($log->action) {
            'created'   => ['إنشاء ' . $subject, 'fa-plus', 'primary'],
            'pages'     => ['تجزئة صفحات ' . $subject, 'fa-copy', 'info'],
            'extracted' => ['استخراج ناجح — ' . $subject, 'fa-check', 'success'],
            'approved'  => ['ترحيل ' . $subject . ' واعتماده', 'fa-check-double', 'success'],
            'rejected'  => ['رفض ' . $subject, 'fa-times', 'danger'],
            'failed'    => ['فشل معالجة ' . $subject, 'fa-exclamation-triangle', 'danger'],
            'completed' => ['اكتملت معالجة ' . $subject, 'fa-flag-checkered', 'success'],
            'merged'    => ['دمج صفحات ' . $subject, 'fa-object-group', 'info'],
            default     => [$log->action . ' — ' . $subject, 'fa-info-circle', 'secondary'],
        };

        return [
            'label' => $label,
            'icon'  => $icon,
            'color' => $color,
            'time'  => $log->created_at?->diffForHumans() ?? '',
        ];
    }
}
