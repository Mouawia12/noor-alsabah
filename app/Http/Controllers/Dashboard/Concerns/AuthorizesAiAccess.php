<?php

namespace App\Http\Controllers\Dashboard\Concerns;

use Illuminate\Support\Facades\Auth;

/**
 * تحكم وصول لمستندات الاستيراد بالذكاء الاصطناعي (فواتير/عقود).
 * المستندات مالية حساسة: يُسمح فقط لمن رفع الدفعة أو لمدير النظام (emp_job==1).
 * يمنع IDOR: تخمين معرّف دفعة/عنصر لقراءة مستندات الآخرين أو التصرّف بها.
 */
trait AuthorizesAiAccess
{
    /** يتحقق من صلاحية الوصول إلى دفعة استيراد. */
    protected function guardAiBatch($batch): void
    {
        abort_if($batch === null, 404);

        $isOwner = $batch->create_user !== null && (int) $batch->create_user === (int) Auth::id();
        $isAdmin = (int) (optional(Auth::user())->emp_job) === 1;

        abort_unless($isOwner || $isAdmin, 403, 'لا تملك صلاحية الوصول إلى هذا المستند.');
    }

    /** يتحقق من صلاحية الوصول إلى عنصر (عبر الدفعة التي يتبعها). */
    protected function guardAiItem($item): void
    {
        abort_if($item === null, 404);
        $this->guardAiBatch($item->batch);
    }
}
