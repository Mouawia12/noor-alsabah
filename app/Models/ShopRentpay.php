<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * دفعة إيجار (بند في جدول السداد). ترتبط بالعقد (shop_rent_id) والمحل (shop_id).
 * الحالة المخزّنة: paid / partial / unpaid. «المتأخر» يُشتق من التاريخ الميلادي.
 */
class ShopRentpay extends Model
{
    use HasFactory;

    protected $table = 'shop_rentpay';
    protected $primaryKey = 'rentpay_id';
    protected $guarded = ['rentpay_id'];

    protected $casts = [
        'rentpay_dt'      => 'date',
        'issue_date'      => 'date',
        'rentpay_price'   => 'float',
        'rent_amount'     => 'float',
        'vat_amount'      => 'float',
        'services_amount' => 'float',
        'paid_amount'     => 'float',
        'is_paid'         => 'boolean',
        'paid_at'         => 'datetime',
    ];

    // حالات السداد المخزّنة
    public const STATUS_PAID    = 'paid';     // مسدَّد بالكامل
    public const STATUS_PARTIAL = 'partial';  // مسدَّد جزئياً
    public const STATUS_UNPAID  = 'unpaid';   // غير مسدَّد
    // حالة مشتقّة (لا تُخزَّن): متأخِّر = غير مسدَّد/جزئي وتجاوز تاريخ الاستحقاق
    public const STATUS_OVERDUE = 'overdue';

    public const STATUS_LABELS = [
        self::STATUS_PAID    => 'مسدَّد',
        self::STATUS_PARTIAL => 'مسدَّد جزئياً',
        self::STATUS_UNPAID  => 'غير مسدَّد',
        self::STATUS_OVERDUE => 'متأخِّر',
    ];

    public function contract()
    {
        return $this->belongsTo(Shop_rent::class, 'shop_rent_id', 'shop_rent_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    public function receipts()
    {
        return $this->hasMany(ShopReceipt::class, 'rentpay_id', 'rentpay_id');
    }

    /** المبلغ المتبقّي على الدفعة (لا يقل عن صفر). */
    public function getRemainingAttribute(): float
    {
        return max(0.0, round((float) $this->rentpay_price - (float) $this->paid_amount, 2));
    }

    /**
     * هل الدفعة متأخّرة؟ متأخّر = لم تُسدَّد بالكامل وتجاوز تاريخ الاستحقاق (بالميلادي).
     */
    public function isOverdue(?Carbon $asOf = null): bool
    {
        $asOf = $asOf ? $asOf->copy()->startOfDay() : Carbon::today();

        return $this->status !== self::STATUS_PAID
            && $this->rentpay_dt !== null
            && Carbon::parse($this->rentpay_dt)->startOfDay()->lt($asOf);
    }

    /** الحالة المعروضة للمستخدم (تُدخِل «متأخِّر» المشتقّ). */
    public function displayStatus(?Carbon $asOf = null): string
    {
        if ($this->status !== self::STATUS_PAID && $this->isOverdue($asOf)) {
            return self::STATUS_OVERDUE;
        }

        return (string) $this->status;
    }

    public function displayStatusLabel(?Carbon $asOf = null): string
    {
        return self::STATUS_LABELS[$this->displayStatus($asOf)] ?? $this->status;
    }

    /** يحسب حالة السداد المخزّنة من المبلغ المدفوع مقابل المستحق. */
    public static function deriveStatus(float $due, float $paid): string
    {
        if ($paid <= 0) {
            return self::STATUS_UNPAID;
        }
        if ($paid + 0.009 >= $due) {   // سماحية تقريب عشري
            return self::STATUS_PAID;
        }

        return self::STATUS_PARTIAL;
    }
}
