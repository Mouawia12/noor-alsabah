<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * سند قبض إلكتروني محفوظ، مرتبط بالمحل + العقد + الدفعة.
 */
class ShopReceipt extends Model
{
    use HasFactory;

    protected $table = 'shop_receipt';
    protected $primaryKey = 'receipt_id';
    protected $guarded = ['receipt_id'];

    protected $casts = [
        'amount'  => 'float',
        'paid_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(ShopRentpay::class, 'rentpay_id', 'rentpay_id');
    }

    public function contract()
    {
        return $this->belongsTo(Shop_rent::class, 'shop_rent_id', 'shop_rent_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }
}
