<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * إعداد نظام (مفتاح/قيمة) يُخزَّن في قاعدة البيانات ويتجاوز قيم .env.
 * تُحمَّل الإعدادات إلى config('ai.*') عند الإقلاع (AppServiceProvider).
 */
class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key', 'value', 'user_id'];
    public $timestamps = false;

    /** تخزين مؤقّت داخل الطلب الواحد (بلا اعتماد على مخزن كاش خارجي أثناء الإقلاع). */
    protected static ?array $mapCache = null;

    /** خريطة كل الإعدادات [key => value] (تُحمَّل مرة واحدة لكل طلب). */
    public static function map(): array
    {
        if (static::$mapCache === null) {
            static::$mapCache = static::query()->pluck('value', 'key')->all();
        }

        return static::$mapCache;
    }

    /** قيمة إعداد (مع افتراضي عند الغياب أو الفراغ). */
    public static function get(string $key, $default = null)
    {
        $v = static::map()[$key] ?? null;

        return ($v === null || $v === '') ? $default : $v;
    }

    /** حفظ/تحديث إعداد وإبطال الكاش. */
    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$mapCache = null;
    }
}
