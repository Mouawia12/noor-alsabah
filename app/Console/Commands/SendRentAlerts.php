<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\RentAlertNotification;
use App\Services\Ai\RentAlertService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

/**
 * يفحص الإيجارات يومياً ويرسل ملخّص التنبيهات للمدراء (داخل النظام + بريد).
 */
class SendRentAlerts extends Command
{
    protected $signature = 'rent:alerts {--days=10 : أيام التنبيه قبل الاستحقاق} {--expiry=30 : أيام التنبيه قبل انتهاء العقد}';
    protected $description = 'إرسال تنبيهات دفعات الإيجار المستحقة/المتأخرة والعقود قاربت الانتهاء';

    public function handle(RentAlertService $alerts): int
    {
        $dueDays = (int) $this->option('days');
        $expiryDays = (int) $this->option('expiry');

        $data = $alerts->summary($dueDays, $expiryDays);
        $counts = [
            'upcoming' => $data['upcoming']->count(),
            'overdue'  => $data['overdue']->count(),
            'expiring' => $data['expiring']->count(),
        ];

        $total = array_sum($counts);
        $this->info("مستحقة: {$counts['upcoming']} | متأخرة: {$counts['overdue']} | قاربت الانتهاء: {$counts['expiring']}");

        if ($total === 0) {
            $this->info('لا توجد تنبيهات اليوم.');
            return self::SUCCESS;
        }

        // عيّنات مفصّلة (عقد/أطراف/رقم الدفعة/المبلغ/الاستحقاق/الحالة/أيام التأخير)
        $samples = [];
        foreach ($data['overdue']->take(6) as $p) {
            $samples[] = \App\Services\Ai\RentAlertService::describePayment($p);
        }
        foreach ($data['upcoming']->take(6) as $p) {
            $samples[] = \App\Services\Ai\RentAlertService::describePayment($p);
        }

        $url = route('dashboard.rent.alerts.index');

        // المستلمون: المدراء (emp_job = 1) النشطون ولديهم بريد
        $admins = User::where('emp_job', 1)->whereNotNull('email')->get();
        if ($admins->isEmpty()) {
            $this->warn('لا يوجد مدراء (emp_job=1) لإرسال التنبيهات إليهم.');
            return self::SUCCESS;
        }

        Notification::send($admins, new RentAlertNotification($counts, $samples, $url));
        $this->info("أُرسلت التنبيهات إلى {$admins->count()} مدير.");

        return self::SUCCESS;
    }
}
