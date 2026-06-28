<?php

namespace App\Services\Ai;

use Carbon\Carbon;

/**
 * تطبيع التواريخ المستخرجة إلى صيغة Y-m-d.
 * يتعامل مع الصيغ الشائعة في الفواتير/العقود (YYYY-MM-DD، DD/MM/YYYY، DD-MM-YYYY ...)
 * ويحوّل الأرقام العربية، ويتجنّب سوء قراءة strtotime لصيغة DD/MM على أنها US.
 */
class DateNormalizer
{
    /** الصيغ المقبولة صراحةً (الأكثر شيوعاً في الوثائق المحلية أولاً). */
    protected const FORMATS = [
        'Y-m-d', 'Y/m/d',
        'd-m-Y', 'd/m/Y', 'd.m.Y',
        'd-m-y', 'd/m/y',
        'm/d/Y', 'm-d-Y',
        'd M Y', 'd F Y', 'j M Y', 'j F Y',
    ];

    public static function toYmd($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $s = self::normalizeDigits(trim((string) $value));
        if ($s === '') {
            return null;
        }

        // صيغ صريحة (آمنة، تتجنّب التخمين الخاطئ لـ strtotime)
        foreach (self::FORMATS as $fmt) {
            $dt = self::tryFormat($fmt, $s);
            if ($dt !== null) {
                return $dt->format('Y-m-d');
            }
        }

        // احتياط أخير: تحليل مرن، لكن فقط إن بدأ بتاريخ معقول (لا كلمات مثل "next")
        if (preg_match('/\d{1,4}[\/\-.]\d{1,2}([\/\-.]\d{1,4})?/', $s) || preg_match('/^\d{4}-\d{2}-\d{2}/', $s)) {
            try {
                return Carbon::parse($s)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    /** هل القيمة تشبه تاريخاً صالحاً؟ */
    public static function looksLikeDate($value): bool
    {
        return self::toYmd($value) !== null;
    }

    protected static function tryFormat(string $fmt, string $s): ?Carbon
    {
        try {
            $dt = Carbon::createFromFormat($fmt, $s);
        } catch (\Throwable $e) {
            return null;
        }

        // createFromFormat قد يقبل قيماً فائضة (مثل اليوم 32) ويعيد ترحيلها — نرفضها
        if ($dt === false || Carbon::getLastErrors()['warning_count'] > 0 || Carbon::getLastErrors()['error_count'] > 0) {
            return null;
        }

        return $dt;
    }

    /** تحويل الأرقام العربية/الهندية إلى لاتينية. */
    protected static function normalizeDigits(string $s): string
    {
        $map = [
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        ];

        return strtr($s, $map);
    }
}
