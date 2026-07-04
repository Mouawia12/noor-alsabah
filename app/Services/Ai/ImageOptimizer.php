<?php

namespace App\Services\Ai;

use RuntimeException;

/**
 * يُصغّر ويضغط الصور الكبيرة قبل إرسالها لمحرك الرؤية.
 * الهدف الأساسي: صور الفواتير الملتقطة بالجوال (عدة ميغابايت) — تصغيرها إلى حدّ معقول
 * يقلّل زمن الرفع لـOpenAI والذاكرة ويقلّل احتمال انتهاء المهلة عند الملفات الكبيرة.
 *
 * ذكي وآمن: يتجاوز الصور الصغيرة أصلاً (صفحات PDF النظيفة الناتجة عن pdftoppm)،
 * ولا يعتمد النسخة الجديدة إلا إن كانت أخفّ حجماً فعلاً — فلا أثر عكسي أبداً.
 * يعمل عبر امتداد GD؛ وإن غاب يُعيد الأصل بأمان.
 */
class ImageOptimizer
{
    /**
     * @return array{0: string, 1: string} [البايتات، نوع MIME]
     */
    public static function optimize(string $path, int $maxEdge, int $quality, int $minBytes = 307200): array
    {
        $raw = @file_get_contents($path);
        if ($raw === false) {
            throw new RuntimeException("تعذّر قراءة ملف الصورة: {$path}");
        }

        // صورة صغيرة أصلاً (غالباً صفحة PDF نظيفة) → لا داعي للتحسين
        if (strlen($raw) < $minBytes) {
            return [$raw, self::mimeOf($path)];
        }

        // GD غير متوفّرة → أعِد الأصل دون تعطيل المعالجة
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagejpeg')) {
            return [$raw, self::mimeOf($path)];
        }

        $img = @imagecreatefromstring($raw);
        if ($img === false) {
            return [$raw, self::mimeOf($path)];
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $long = max($w, $h);

        // مقاس الوجهة (تصغير محافظ على النسبة إن تجاوز الحد)
        if ($maxEdge > 0 && $long > $maxEdge) {
            $scale = $maxEdge / $long;
            $nw = max(1, (int) round($w * $scale));
            $nh = max(1, (int) round($h * $scale));
        } else {
            $nw = $w;
            $nh = $h;
        }

        // خلفية بيضاء (تسطيح شفافية PNG قبل JPEG)
        $dst = imagecreatetruecolor($nw, $nh);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, $white);
        imagecopyresampled($dst, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagedestroy($img);

        ob_start();
        $ok = imagejpeg($dst, null, max(40, min(95, $quality)));
        $out = ob_get_clean();
        imagedestroy($dst);

        if (! $ok || $out === '' || $out === false) {
            return [$raw, self::mimeOf($path)];
        }

        // اعتمد النسخة الجديدة فقط إن كانت أخفّ حجماً فعلاً (لا أثر عكسي)
        return strlen($out) < strlen($raw) ? [$out, 'image/jpeg'] : [$raw, self::mimeOf($path)];
    }

    protected static function mimeOf(string $path): string
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'webp'        => 'image/webp',
            'gif'         => 'image/gif',
            default       => 'image/png',
        };
    }
}
