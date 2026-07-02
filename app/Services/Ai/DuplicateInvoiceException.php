<?php

namespace App\Services\Ai;

/**
 * تُرمى عند محاولة حفظ فاتورة برقم موجود مسبقاً (منع التكرار عند الاعتماد).
 * الرسالة موجّهة للمستخدم مباشرة.
 */
class DuplicateInvoiceException extends \RuntimeException
{
}
