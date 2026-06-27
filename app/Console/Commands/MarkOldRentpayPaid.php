<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * أداة لمرة واحدة: تعليم دفعات إيجار قديمة (قبل تاريخ معيّن) كمسدَّدة،
 * لتنظيف التنبيهات/التحليلات من بيانات تاريخية أُضيف لها عمود is_paid حديثاً.
 * تتطلب --before صراحةً (لا تعمل افتراضياً على كل البيانات).
 */
class MarkOldRentpayPaid extends Command
{
    protected $signature = 'rent:mark-old-paid {--before= : تاريخ (YYYY-MM-DD) — تُعلَّم الدفعات قبله كمسدَّدة} {--force : تنفيذ دون تأكيد}';
    protected $description = 'تعليم دفعات الإيجار القديمة كمسدَّدة (تنظيف بيانات لمرة واحدة)';

    public function handle(): int
    {
        $before = $this->option('before');
        if (! $before || ! strtotime($before)) {
            $this->error('يجب تحديد تاريخ صالح: --before=YYYY-MM-DD');
            return self::FAILURE;
        }
        $before = date('Y-m-d', strtotime($before));

        $count = DB::table('shop_rentpay')->where('is_paid', 0)->where('rentpay_dt', '<', $before)->count();
        if ($count === 0) {
            $this->info('لا توجد دفعات غير مسدَّدة قبل ' . $before);
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("سيتم تعليم {$count} دفعة قبل {$before} كمسدَّدة. متابعة؟")) {
            $this->info('أُلغي.');
            return self::SUCCESS;
        }

        $updated = DB::table('shop_rentpay')->where('is_paid', 0)->where('rentpay_dt', '<', $before)
            ->update(['is_paid' => 1, 'paid_at' => now(), 'updated_at' => now()]);

        $this->info("تم تعليم {$updated} دفعة كمسدَّدة.");
        return self::SUCCESS;
    }
}
