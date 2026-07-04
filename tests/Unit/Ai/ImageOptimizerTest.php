<?php

use App\Services\Ai\ImageOptimizer;

/**
 * تحسين الصور الكبيرة قبل إرسالها للمحرك: تصغير + ضغط JPEG لتخفيف الحمولة،
 * مع تجاوز الصور الصغيرة (صفحات PDF النظيفة) دون أثر عكسي.
 */
beforeEach(function () {
    if (! function_exists('imagejpeg') || ! function_exists('imagecreatetruecolor')) {
        $this->markTestSkipped('امتداد GD غير متوفّر في هذه البيئة.');
    }
});

/** صورة "فوتوغرافية" عالية التباين (سيّئة الانضغاط بـPNG ⇒ كبيرة الحجم). */
function makePhoto(int $w, int $h): string
{
    $im = imagecreatetruecolor($w, $h);
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            imagesetpixel($im, $x, $y, (($x * 131 + $y * 17) * 2654435761) & 0xFFFFFF);
        }
    }
    $path = tempnam(sys_get_temp_dir(), 'photo') . '.png';
    imagepng($im, $path);
    imagedestroy($im);

    return $path;
}

/** صورة مسطّحة صغيرة (تحاكي صفحة PDF نظيفة). */
function makeFlat(int $w, int $h): string
{
    $im = imagecreatetruecolor($w, $h);
    imagefilledrectangle($im, 0, 0, $w, $h, imagecolorallocate($im, 255, 255, 255));
    imagefilledrectangle($im, 20, 20, 200, 60, imagecolorallocate($im, 0, 0, 0));
    $path = tempnam(sys_get_temp_dir(), 'flat') . '.png';
    imagepng($im, $path);
    imagedestroy($im);

    return $path;
}

it('downscales and compresses a large photographic image', function () {
    $path = makePhoto(1200, 900);
    $orig = filesize($path);
    // اجعل الحد الأدنى نصف حجم الصورة لضمان تصنيفها "كبيرة" بصرف النظر عن انضغاط PNG
    $min = max(1, intdiv($orig, 2));

    [$bytes, $mime] = ImageOptimizer::optimize($path, 400, 85, $min);

    expect($mime)->toBe('image/jpeg');
    $info = getimagesizefromstring($bytes);
    expect(max($info[0], $info[1]))->toBeLessThanOrEqual(400); // صُغّرت الأبعاد
    expect($info[0] / $info[1])->toEqualWithDelta(1200 / 900, 0.03); // النسبة محفوظة
    expect(strlen($bytes))->toBeLessThan($orig); // حمولة أخفّ فعلاً

    @unlink($path);
});

it('leaves a small clean page unchanged (no adverse effect)', function () {
    $path = makeFlat(800, 600);
    expect(filesize($path))->toBeLessThan(300000);

    [$bytes, $mime] = ImageOptimizer::optimize($path, 400, 85, 300000);

    // أصغر من الحد → يُعاد كما هو دون تصغير أو تحويل
    $info = getimagesizefromstring($bytes);
    expect($info[0])->toBe(800);
    expect($info[1])->toBe(600);
    expect($mime)->toBe('image/png');

    @unlink($path);
});

it('throws a clear error for a missing image path', function () {
    expect(fn () => ImageOptimizer::optimize('/no/such/file.png', 2200, 85))
        ->toThrow(RuntimeException::class);
});
