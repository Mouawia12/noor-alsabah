<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * قيد في السجل المالي للمحل (دفتر عمليات الإيجار).
 */
class ShopFinancialLog extends Model
{
    use HasFactory;

    protected $table = 'shop_financial_log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;   // نستخدم created_at فقط
    protected $guarded = ['log_id'];

    protected $casts = [
        'amount'     => 'float',
        'created_at' => 'datetime',
    ];

    public const DIR_CREDIT = 'credit';   // قبض (دخل)
    public const DIR_DEBIT  = 'debit';    // استحقاق (التزام)

    public const EVENT_PAYMENT    = 'payment';
    public const EVENT_DUE        = 'due';
    public const EVENT_ADJUSTMENT = 'adjustment';
    public const EVENT_REVERSAL   = 'reversal';

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    public function contract()
    {
        return $this->belongsTo(Shop_rent::class, 'shop_rent_id', 'shop_rent_id');
    }
}
