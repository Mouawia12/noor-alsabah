<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار اكتمال معالجة دفعة استيراد (فواتير/عقود) — لمن رفعها.
 */
class BatchCompletedNotification extends Notification
{
    use Queueable;

    /**
     * @param string $kind 'purchase' أو 'rent'
     */
    public function __construct(
        public string $kind,
        public string $filename,
        public int $total,
        public int $processed,
        public int $failed,
        public ?string $reviewUrl = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = $this->kind === 'rent' ? 'عقود الإيجار' : 'الفواتير';

        $mail = (new MailMessage)
            ->subject('اكتملت معالجة ' . $label . ' — نور الصباح')
            ->greeting('تمت المعالجة')
            ->line('اكتملت معالجة الملف: ' . $this->filename)
            ->line('الإجمالي: ' . $this->total . ' | المعالَج: ' . $this->processed . ' | الفاشل: ' . $this->failed);

        if ($this->reviewUrl) {
            $mail->action('مراجعة واعتماد النتائج', $this->reviewUrl);
        }

        return $mail->line('شكراً لاستخدامك النظام.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'batch_completed',
            'kind'      => $this->kind,
            'filename'  => $this->filename,
            'total'     => $this->total,
            'processed' => $this->processed,
            'failed'    => $this->failed,
            'url'       => $this->reviewUrl,
        ];
    }
}
