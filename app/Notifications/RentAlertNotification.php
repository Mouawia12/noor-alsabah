<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار ملخّص تنبيهات الإيجارات (قناتان: داخل النظام database + بريد mail).
 */
class RentAlertNotification extends Notification
{
    use Queueable;

    /**
     * @param array $summary ['upcoming'=>int,'overdue'=>int,'expiring'=>int]
     * @param array $samples عيّنات نصية للعرض
     */
    public function __construct(public array $summary, public array $samples = [], public ?string $url = null) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('تنبيهات الإيجارات — نور الصباح')
            ->greeting('تنبيهات الإيجارات اليومية');

        $mail->line('ملخّص حالة الإيجارات:');
        $mail->line('• دفعات مستحقة قريباً: ' . ($this->summary['upcoming'] ?? 0));
        $mail->line('• دفعات متأخرة: ' . ($this->summary['overdue'] ?? 0));
        $mail->line('• عقود قاربت الانتهاء: ' . ($this->summary['expiring'] ?? 0));

        foreach ($this->samples as $s) {
            $mail->line('— ' . $s);
        }

        if ($this->url) {
            $mail->action('فتح لوحة متابعة الإيجارات', $this->url);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'rent_alert',
            'summary' => $this->summary,
            'samples' => $this->samples,
            'url'     => $this->url,
        ];
    }
}
