<?php

namespace App\Http\Controllers\Dashboard\Concerns;

/**
 * تصدير التقارير إلى Excel (PhpSpreadsheet) أو PDF (dompdf).
 */
trait ExportsReports
{
    protected function exportData(string $title, array $summaryRows, $detailRows, array $detailHeader, string $format)
    {
        if ($format === 'pdf') {
            $html = '<html dir="rtl"><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans;}table{width:100%;border-collapse:collapse;margin-bottom:16px}td,th{border:1px solid #999;padding:6px;text-align:right}h2,h3{margin:8px 0}</style></head><body>';
            $html .= '<h2>' . e($title) . '</h2><table>';
            foreach ($summaryRows as $r) {
                $html .= '<tr><td>' . e($r[0]) . '</td><td>' . e($r[1]) . '</td></tr>';
            }
            $html .= '</table>';
            if (count($detailRows)) {
                $html .= '<h3>التفصيل</h3><table><tr>';
                foreach ($detailHeader as $h) {
                    $html .= '<th>' . e($h) . '</th>';
                }
                $html .= '</tr>';
                foreach ($detailRows as $d) {
                    $html .= '<tr>';
                    foreach ((array) $d as $v) {
                        $html .= '<td>' . e($v) . '</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</table>';
            }
            $html .= '</body></html>';

            return \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->download($title . '.pdf');
        }

        $ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $ss->getActiveSheet();
        $sheet->setRightToLeft(true);
        $r = 1;
        foreach ($summaryRows as $row) {
            $sheet->setCellValue("A{$r}", $row[0]);
            $sheet->setCellValue("B{$r}", $row[1]);
            $r++;
        }
        $r++;
        if (! empty($detailHeader)) {
            $sheet->fromArray($detailHeader, null, "A{$r}");
            $r++;
            foreach ($detailRows as $d) {
                $sheet->fromArray(array_values((array) $d), null, "A{$r}");
                $r++;
            }
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ss);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $title . '.xlsx');
    }
}
