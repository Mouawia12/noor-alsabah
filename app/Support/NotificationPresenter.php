<?php

namespace App\Support;

use Illuminate\Notifications\DatabaseNotification;

/**
 * يحوّل أي إشعار قاعدة بيانات إلى عناصر عرض موحّدة (أيقونة/لون/عنوان/نص/رابط)
 * بصرف النظر عن نوعه — كي تعرضه القائمة المنسدلة وصفحة الإشعارات بشكل احترافي متّسق.
 * يدعم الإشعارات القديمة التي لا تحمل title/message (يشتقّها من الحقول).
 */
class NotificationPresenter
{
    /**
     * @return array{icon:string,color:string,title:string,message:string,url:?string,samples:array,time:string,is_read:bool,id:string}
     */
    public static function present(DatabaseNotification $n): array
    {
        $d = (array) $n->data;
        $type = $d['type'] ?? 'generic';

        // إن كان الإشعار يحمل عنواناً/نصاً صريحين (الإصدارات الجديدة) نستخدمهما مباشرة.
        $title   = $d['title'] ?? null;
        $message = $d['message'] ?? null;
        $samples = [];
        $icon    = 'fa-bell';
        $color   = 'primary';

        switch ($type) {
            case 'batch_completed':
                $label = ($d['kind'] ?? '') === 'rent' ? 'عقود الإيجار' : 'الفواتير';
                $icon  = ($d['kind'] ?? '') === 'rent' ? 'fa-file-contract' : 'fa-file-invoice';
                $color = ($d['failed'] ?? 0) > 0 ? 'warning' : 'success';
                $title = $title ?? ('اكتملت معالجة ' . $label);
                $message = $message ?? sprintf(
                    'الملف: %s — الإجمالي %d، المقبول %d، المرفوض %d',
                    $d['filename'] ?? '—',
                    (int) ($d['total'] ?? 0),
                    (int) ($d['processed'] ?? 0),
                    (int) ($d['failed'] ?? 0)
                );
                break;

            case 'rent_alert':
                $icon  = 'fa-calendar-day';
                $color = ($d['summary']['overdue'] ?? 0) > 0 ? 'danger' : 'primary';
                $title = $title ?? 'تنبيهات الإيجارات';
                $message = $message ?? sprintf(
                    'مستحقة قريباً: %d — متأخرة: %d — عقود قاربت الانتهاء: %d',
                    (int) ($d['summary']['upcoming'] ?? 0),
                    (int) ($d['summary']['overdue'] ?? 0),
                    (int) ($d['summary']['expiring'] ?? 0)
                );
                $samples = array_slice((array) ($d['samples'] ?? []), 0, 5);
                break;

            default:
                $title = $title ?? 'إشعار';
                $message = $message ?? '';
        }

        return [
            'id'      => $n->id,
            'icon'    => $icon,
            'color'   => $color,
            'title'   => $title,
            'message' => $message,
            'url'     => $d['url'] ?? null,
            'samples' => $samples,
            'time'    => $n->created_at?->diffForHumans() ?? '',
            'is_read' => $n->read_at !== null,
        ];
    }
}
