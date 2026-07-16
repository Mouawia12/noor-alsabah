<?php

namespace App\Http\Controllers\Dashboard\Concerns;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * تصدير التقارير إلى PDF (TCPDF — يربط الحروف العربية صحيحاً) أو Excel (PhpSpreadsheet) بتصميم احترافي.
 */
trait ExportsReports
{
    protected string $exportBrand = 'شركة نور الصباح الاستثمارية';
    protected string $exportAccent = '#0b56a4';

    protected function exportData(string $title, array $summaryRows, $detailRows, array $detailHeader, string $format)
    {
        $clean = str_replace('_', ' ', $title);
        $detailRows = array_map(fn ($r) => array_values((array) $r), (array) $detailRows);

        return $format === 'pdf'
            ? $this->exportPdf($title, $clean, $summaryRows, $detailRows, $detailHeader)
            : $this->exportXlsx($title, $clean, $summaryRows, $detailRows, $detailHeader);
    }

    /** PDF احترافي عربي عبر TCPDF (ربط الحروف + RTL + ترويسة وجدول منسّق). */
    protected function exportPdf(string $title, string $clean, array $summaryRows, array $detailRows, array $detailHeader)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Noor Al-Sabah');
        $pdf->SetTitle($clean);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->setFooterFont(['dejavusans', '', 8]);
        $pdf->SetMargins(12, 14, 12);
        $pdf->SetAutoPageBreak(true, 16);
        $pdf->setRTL(true);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 10);

        $date = now()->format('Y-m-d H:i');
        $accent = $this->exportAccent;

        $html = '<style>
            h1{font-size:16px;color:#fff;font-weight:bold;margin:0}
            .sub{font-size:10px;color:#e6f1fb;margin:0}
            .meta{font-size:9px;color:#666}
            table{width:100%;border-collapse:collapse}
            .kv td{border:1px solid #dbe3ec;padding:5px 7px;font-size:10px}
            .kv td.k{background-color:#eef3f8;color:#181c32;font-weight:bold;width:45%}
            .dt th{background-color:' . $accent . ';color:#ffffff;font-weight:bold;font-size:9.5px;padding:6px;border:1px solid ' . $accent . '}
            .dt td{border:1px solid #cfd8e3;padding:5px;font-size:9px;color:#2b2f42}
            .zebra{background-color:#f6f9fc}
            h3{font-size:11px;color:' . $accent . ';margin:10px 0 4px}
        </style>';

        // ترويسة ملوّنة
        $html .= '<table cellpadding="8"><tr><td style="background-color:' . $accent . ';">'
            . '<h1>' . htmlspecialchars($this->exportBrand) . '</h1>'
            . '<p class="sub">' . htmlspecialchars($clean) . '</p></td></tr></table>';
        $html .= '<p class="meta">تاريخ الإصدار: ' . $date . '</p>';

        // ملخّص (مفتاح/قيمة) — نتجاوز صفّ العناوين الأول إن كان ["المؤشر","القيمة"]
        $sr = $summaryRows;
        if (! empty($sr) && isset($sr[0][0]) && in_array($sr[0][0], ['المؤشر', 'التقرير'], true)) {
            array_shift($sr);
        }
        if (! empty($sr)) {
            $html .= '<h3>الملخّص</h3><table class="kv">';
            foreach ($sr as $r) {
                $html .= '<tr><td class="k">' . htmlspecialchars((string) ($r[0] ?? '')) . '</td><td>' . htmlspecialchars((string) ($r[1] ?? '')) . '</td></tr>';
            }
            $html .= '</table>';
        }

        // التفصيل
        if (! empty($detailRows) && ! empty($detailHeader)) {
            $html .= '<h3>التفصيل</h3><table class="dt"><tr>';
            foreach ($detailHeader as $h) {
                $html .= '<th>' . htmlspecialchars((string) $h) . '</th>';
            }
            $html .= '</tr>';
            foreach ($detailRows as $i => $d) {
                $html .= '<tr class="' . ($i % 2 ? 'zebra' : '') . '">';
                foreach ($d as $v) {
                    $html .= '<td>' . htmlspecialchars((string) $v) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        }

        $pdf->writeHTML($html, true, false, true, false, '');

        return response($pdf->Output($clean . '.pdf', 'S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $clean . '.pdf"',
        ]);
    }

    /** Excel منسّق: ترويسة + ملخّص + جدول تفصيلي بحدود وألوان وعرض تلقائي (RTL). */
    protected function exportXlsx(string $title, string $clean, array $summaryRows, array $detailRows, array $detailHeader)
    {
        $ss = new Spreadsheet();
        $sheet = $ss->getActiveSheet();
        $sheet->setRightToLeft(true);
        $sheet->setTitle('التقرير');

        $cols = max(2, count($detailHeader));
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cols);
        $accent = ltrim($this->exportAccent, '#');
        $r = 1;

        // ترويسة
        $sheet->setCellValue("A{$r}", $this->exportBrand . ' — ' . $clean);
        $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
        $sheet->getStyle("A{$r}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $accent]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($r)->setRowHeight(26);
        $r++;
        $sheet->setCellValue("A{$r}", 'تاريخ الإصدار: ' . now()->format('Y-m-d H:i'));
        $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
        $sheet->getStyle("A{$r}")->getFont()->setItalic(true)->setSize(9);
        $r += 2;

        // ملخّص
        $sr = $summaryRows;
        if (! empty($sr) && isset($sr[0][0]) && in_array($sr[0][0], ['المؤشر', 'التقرير'], true)) {
            array_shift($sr);
        }
        foreach ($sr as $row) {
            $sheet->setCellValue("A{$r}", $row[0] ?? '');
            $sheet->setCellValue("B{$r}", $row[1] ?? '');
            $sheet->getStyle("A{$r}")->getFont()->setBold(true);
            $sheet->getStyle("A{$r}:B{$r}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DBE3EC']]],
            ]);
            $sheet->getStyle("A{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EEF3F8');
            $r++;
        }
        if (! empty($sr)) {
            $r++;
        }

        // جدول تفصيلي
        if (! empty($detailHeader)) {
            $sheet->fromArray($detailHeader, null, "A{$r}");
            $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $accent]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $accent]]],
            ]);
            $headerRow = $r;
            $r++;
            foreach ($detailRows as $d) {
                $sheet->fromArray($d, null, "A{$r}");
                $r++;
            }
            if (! empty($detailRows)) {
                $sheet->getStyle("A{$headerRow}:{$lastCol}" . ($r - 1))->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CFD8E3']]],
                ]);
            }
        }

        for ($c = 1; $c <= $cols; $c++) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c))->setAutoSize(true);
        }

        $writer = new Xlsx($ss);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $clean . '.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }
}
