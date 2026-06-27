<?php

namespace App\Services\Ai;

use RuntimeException;
use setasign\Fpdi\Fpdi;

/**
 * معالجة ملفات PDF: عدّ الصفحات وتحويلها إلى صور (للإرسال لمحرك الرؤية).
 * يتطلب امتداد Imagick + Ghostscript على السيرفر لتحويل PDF إلى صور.
 */
class PdfService
{
    /** هل بيئة تحويل الصور متاحة؟ */
    public static function canRasterize(): bool
    {
        return extension_loaded('imagick');
    }

    /** عدد صفحات ملف PDF. */
    public function pageCount(string $pdfPath): int
    {
        $this->assertFile($pdfPath);

        try {
            $pdf = new Fpdi();
            return $pdf->setSourceFile($pdfPath);
        } catch (\Throwable $e) {
            // ملفات بعض الفواتير قد تكون صوراً مدمجة لا يقرأها FPDI — جرّب Imagick
            if (self::canRasterize()) {
                $im = new \Imagick();
                $im->pingImage($pdfPath);
                $n = $im->getNumberImages();
                $im->clear();
                return $n;
            }
            throw new RuntimeException('تعذّر تحديد عدد صفحات PDF: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * تحويل كل صفحات PDF إلى صور PNG في مجلد، وإرجاع مسارات الصور مرتّبة.
     *
     * @return string[] مسارات الصور (واحدة لكل صفحة)
     */
    public function rasterizeAll(string $pdfPath, string $outDir, ?int $dpi = null): array
    {
        $this->assertFile($pdfPath);

        if (! self::canRasterize()) {
            throw new RuntimeException('امتداد Imagick غير متوفّر — مطلوب لتحويل صفحات PDF إلى صور.');
        }

        $dpi = $dpi ?? (int) config('ai.pdf_render_dpi', 200);
        if (! is_dir($outDir)) {
            mkdir($outDir, 0775, true);
        }

        $count = $this->pageCount($pdfPath);
        $paths = [];

        for ($i = 0; $i < $count; $i++) {
            $out = rtrim($outDir, '/') . '/page_' . ($i + 1) . '.png';
            $this->rasterizePage($pdfPath, $i, $out, $dpi);
            $paths[] = $out;
        }

        return $paths;
    }

    /** تحويل صفحة واحدة (0-based) إلى صورة PNG. */
    public function rasterizePage(string $pdfPath, int $pageIndex, string $outPath, ?int $dpi = null): string
    {
        if (! self::canRasterize()) {
            throw new RuntimeException('امتداد Imagick غير متوفّر.');
        }

        $dpi = $dpi ?? (int) config('ai.pdf_render_dpi', 200);

        $im = new \Imagick();
        $im->setResolution($dpi, $dpi);
        $im->readImage($pdfPath . '[' . $pageIndex . ']'); // صفحة محددة
        $im->setImageFormat('png');
        $im->setImageBackgroundColor('white');
        $im = $im->flattenImages(); // إزالة الشفافية
        $im->writeImage($outPath);
        $im->clear();
        $im->destroy();

        return $outPath;
    }

    protected function assertFile(string $path): void
    {
        if (! is_file($path)) {
            throw new RuntimeException("ملف PDF غير موجود: {$path}");
        }
    }
}
