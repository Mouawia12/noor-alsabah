<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * عنصر مطابقة دفعة بالذكاء الاصطناعي: دفعة واردة + اقتراح ربطها بعقد/محل/دفعة مستحقة،
 * بانتظار مراجعة المستخدم وتأكيده.
 */
class RentPaymentMatchItem extends Model
{
    use HasFactory;

    protected $table = 'rent_payment_match_item';
    protected $guarded = ['id'];

    protected $casts = [
        'raw'         => 'array',
        'amount'      => 'float',
        'due_date'    => 'date',
        'confidence'  => 'float',
        'reviewed_at' => 'datetime',
    ];

    // دورة المراجعة
    public const STATUS_NEEDS_REVIEW = 'needs_review';
    public const STATUS_APPROVED     = 'approved';
    public const STATUS_REJECTED     = 'rejected';

    // نتيجة المطابقة
    public const MATCH_MATCHED   = 'matched';    // مطابقة واضحة لدفعة مستحقة
    public const MATCH_ORPHAN    = 'orphan';     // لا يوجد عقد/دفعة مطابقة
    public const MATCH_UNDERPAID = 'underpaid';  // المبلغ أقل من المستحق
    public const MATCH_OVERPAID  = 'overpaid';   // المبلغ أكثر من المستحق
    public const MATCH_DUPLICATE = 'duplicate';  // دفعة مسدَّدة مطابقة موجودة مسبقاً

    public const MATCH_LABELS = [
        self::MATCH_MATCHED   => 'مطابقة',
        self::MATCH_ORPHAN    => 'دفعة يتيمة (بلا عقد)',
        self::MATCH_UNDERPAID => 'مبلغ ناقص',
        self::MATCH_OVERPAID  => 'مبلغ زائد',
        self::MATCH_DUPLICATE => 'مكرَّرة',
    ];

    public function scopeNeedsReviewOrdered($query)
    {
        return $query->where('status', self::STATUS_NEEDS_REVIEW)
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    public function contract()
    {
        return $this->belongsTo(Shop_rent::class, 'matched_shop_rent_id', 'shop_rent_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'matched_shop_id', 'shop_id');
    }
}
