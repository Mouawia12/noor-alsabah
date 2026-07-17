<?php

namespace App\Services\Ai;

use RuntimeException;
use setasign\Fpdi\Fpdi;

/**
 * معالجة ملفات PDF: عدّ الصفحات وتحويلها إلى صور (للإرسال لمحرك الرؤية).
 * يدعم عدة أدوات للتحويل بترتيب الأفضلية (قابلية نقل عبر بيئات الاستضافة):
 *   1) pdftoppm (poppler)   2) magick/convert (ImageMagick CLI)
 *   3) امتداد PHP Imagick    4) gs (Ghostscript)
 */
class PdfService
{
    /** هل تتوفّر أي وسيلة لتحويل PDF إلى صور؟ */
    public static function canRasterize(): bool
    {
        // أدوات CLI بلا فائدة إن كانت exec معطّلة؛ يبقى امتداد Imagick صالحاً دائماً.
        $canExec = function_exists('exec');

        return ($canExec && (self::bin('pdftoppm') || self::bin('magick') || self::bin('convert') || self::bin('gs')))
            || extension_loaded('imagick');
    }

    /** عدد صفحات ملف PDF. */
    public function pageCount(string $pdfPath): int
    {
        $this->assertFile($pdfPath);

        // 1) FPDI
        try {
            $pdf = new Fpdi();
            return $pdf->setSourceFile($pdfPath);
        } catch (\Throwable $e) {
            // تجاهل وجرّب البدائل
        }

        // 2) pdfinfo (poppler) — فقط حيث shell_exec مفعّلة (CLI/العامل)
        if (function_exists('shell_exec') && ($bin = self::bin('pdfinfo'))) {
            $out = shell_exec(escapeshellarg($bin) . ' ' . escapeshellarg($pdfPath) . ' 2>/dev/null');
            if ($out && preg_match('/Pages:\s+(\d+)/', $out, $m)) {
                return (int) $m[1];
            }
        }

        // 3) امتداد Imagick
        if (extension_loaded('imagick')) {
            $im = new \Imagick();
            $im->pingImage($pdfPath);
            $n = $im->getNumberImages();
            $im->clear();
            return $n;
        }

        throw new RuntimeException('تعذّر تحديد عدد صفحات PDF.');
    }

    /**
     * تحويل كل صفحات PDF إلى صور PNG في مجلد، وإرجاع مساراتها مرتّبة.
     *
     * @return string[]
     */
    public function rasterizeAll(string $pdfPath, string $outDir, ?int $dpi = null): array
    {
        $this->assertFile($pdfPath);
        $dpi = $dpi ?? (int) config('ai.pdf_render_dpi', 200);

        if (! is_dir($outDir)) {
            mkdir($outDir, 0775, true);
        }

        // أدوات CLI تحتاج exec()؛ بعض الاستضافات المحصّنة (Hestia) تعطّلها → نتخطّاها
        // لامتداد Imagick الذي لا يحتاج أي shell. (بدون هذا الحارس ينهار الكود بـ
        // «Call to undefined function exec()» فور إيجاد pdftoppm في PATH.)
        $canExec = function_exists('exec');

        // 1) pdftoppm — الأفضل (لا يحتاج Ghostscript)
        if ($canExec && ($bin = self::bin('pdftoppm'))) {
            return $this->viaPdftoppm($bin, $pdfPath, $outDir, $dpi);
        }

        // 2) magick / convert (ImageMagick CLI)
        if ($canExec && (($bin = self::bin('magick')) || ($bin = self::bin('convert')))) {
            return $this->viaMagickCli($bin, $pdfPath, $outDir, $dpi);
        }

        // 3) امتداد Imagick (لا يحتاج exec) — المسار المفضّل على الاستضافات التي تعطّل exec
        if (extension_loaded('imagick')) {
            return $this->viaImagickExt($pdfPath, $outDir, $dpi);
        }

        // 4) Ghostscript
        if ($canExec && ($bin = self::bin('gs'))) {
            return $this->viaGhostscript($bin, $pdfPath, $outDir, $dpi);
        }

        throw new RuntimeException(
            $canExec
                ? 'لا تتوفّر أي أداة لتحويل PDF إلى صور (pdftoppm/magick/imagick/gs).'
                : 'دالة exec معطّلة على الخادم، ولا يوجد امتداد PHP Imagick. ثبّت امتداد Imagick + Ghostscript، أو فعّل exec في disable_functions.'
        );
    }

    protected function viaPdftoppm(string $bin, string $pdf, string $outDir, int $dpi): array
    {
        $prefix = rtrim($outDir, '/') . '/page';
        $cmd = sprintf('%s -png -r %d %s %s 2>&1', escapeshellarg($bin), $dpi, escapeshellarg($pdf), escapeshellarg($prefix));
        exec($cmd, $o, $code);
        if ($code !== 0) {
            throw new RuntimeException('فشل pdftoppm: ' . implode("\n", $o));
        }
        $files = glob($prefix . '-*.png') ?: [];
        natsort($files);
        return $this->normalizeNames(array_values($files), $outDir);
    }

    protected function viaMagickCli(string $bin, string $pdf, string $outDir, int $dpi): array
    {
        $count = $this->pageCount($pdf);
        $paths = [];
        for ($i = 0; $i < $count; $i++) {
            $out = rtrim($outDir, '/') . '/page_' . ($i + 1) . '.png';
            $cmd = sprintf(
                '%s -density %d %s[%d] -background white -flatten %s 2>&1',
                escapeshellarg($bin), $dpi, escapeshellarg($pdf), $i, escapeshellarg($out)
            );
            exec($cmd, $o, $code);
            if ($code !== 0 || ! is_file($out)) {
                throw new RuntimeException('فشل magick: ' . implode("\n", (array) $o));
            }
            $paths[] = $out;
        }
        return $paths;
    }

    protected function viaImagickExt(string $pdf, string $outDir, int $dpi): array
    {
        $count = $this->pageCount($pdf);
        $paths = [];
        for ($i = 0; $i < $count; $i++) {
            $out = rtrim($outDir, '/') . '/page_' . ($i + 1) . '.png';
            $im = new \Imagick();
            $im->setResolution($dpi, $dpi);
            $im->readImage($pdf . '[' . $i . ']');
            $im->setImageFormat('png');
            $im->setImageBackgroundColor('white');
            $im = $im->flattenImages();
            $im->writeImage($out);
            $im->clear();
            $im->destroy();
            $paths[] = $out;
        }
        return $paths;
    }

    protected function viaGhostscript(string $bin, string $pdf, string $outDir, int $dpi): array
    {
        $pattern = rtrim($outDir, '/') . '/page_%d.png';
        $cmd = sprintf(
            '%s -dNOPAUSE -dBATCH -sDEVICE=png16m -r%d -sOutputFile=%s %s 2>&1',
            escapeshellarg($bin), $dpi, escapeshellarg($pattern), escapeshellarg($pdf)
        );
        exec($cmd, $o, $code);
        if ($code !== 0) {
            throw new RuntimeException('فشل gs: ' . implode("\n", $o));
        }
        $files = glob(rtrim($outDir, '/') . '/page_*.png') ?: [];
        natsort($files);
        return array_values($files);
    }

    /** توحيد أسماء الصور إلى page_1.png, page_2.png ... */
    protected function normalizeNames(array $files, string $outDir): array
    {
        $paths = [];
        foreach (array_values($files) as $idx => $f) {
            $target = rtrim($outDir, '/') . '/page_' . ($idx + 1) . '.png';
            if ($f !== $target) {
                @rename($f, $target);
            }
            $paths[] = $target;
        }
        return $paths;
    }

    /** إيجاد مسار أداة سطر أوامر (مع مسارات شائعة احتياطية). */
    protected static function bin(string $name): ?string
    {
        // shell_exec معطّلة في FPM على هذا السيرفر (تحصين Hestia) → نطوّقها بـ function_exists
        // ونعتمد مسحاً Pure-PHP لـ PATH كي يعمل canRasterize() في سياق الويب أيضاً.
        if (function_exists('shell_exec')) {
            $path = trim((string) @shell_exec('command -v ' . escapeshellarg($name) . ' 2>/dev/null'));
            if ($path !== '' && @is_executable($path)) {
                return $path;
            }
        }
        $dirs = array_filter(explode(PATH_SEPARATOR, (string) getenv('PATH')));
        foreach (array_merge($dirs, ['/usr/bin', '/usr/local/bin', '/opt/homebrew/bin']) as $dir) {
            $candidate = rtrim($dir, '/') . '/' . $name;
            // @ يكتم تحذير open_basedir لمجلدات $PATH خارج القائمة المسموحة (مثل /usr/local/sbin)
            // كي لا يحوّله Laravel إلى استثناء؛ is_executable يُرجع false بأمان للمسارات غير المتاحة.
            if (@is_executable($candidate)) {
                return $candidate;
            }
        }
        return null;
    }

    protected function assertFile(string $path): void
    {
        if (! is_file($path)) {
            throw new RuntimeException("ملف PDF غير موجود: {$path}");
        }
    }
}
