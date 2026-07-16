<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop_rent extends Model
{
    use HasFactory;
    protected $table = "shop_rent";
    protected $primaryKey = 'shop_rent_id';
    // أمن: حماية المفتاح الأساسي من الإسناد الجماعي (كان $guarded = [] يفتح كل الأعمدة)
    protected $guarded = ['shop_rent_id'];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'rent_value'    => 'float',
        'annual_value'  => 'float',
        'total_value'   => 'float',
        'first_payment' => 'float',
    ];

    // نوع العقد
    public const TYPE_NEW     = 'new';
    public const TYPE_RENEWAL = 'renewal';

    // دورات السداد المدعومة
    public const CYCLE_MONTHLY   = 'monthly';
    public const CYCLE_QUARTERLY = 'quarterly';
    public const CYCLE_SEMI      = 'semi';
    public const CYCLE_ANNUAL    = 'annual';

    /** المحل الذي يخص العقد (المرجع الأساسي). */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    /** دفعات هذا العقد (جدول السداد). */
    public function payments()
    {
        return $this->hasMany(ShopRentpay::class, 'shop_rent_id', 'shop_rent_id');
    }

    /** سندات القبض المرتبطة بالعقد. */
    public function receipts()
    {
        return $this->hasMany(ShopReceipt::class, 'shop_rent_id', 'shop_rent_id');
    }
}
