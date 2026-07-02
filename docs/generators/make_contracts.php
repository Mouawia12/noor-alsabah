<?php
require __DIR__ . '/../../vendor/autoload.php';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Noor Al-Sabah');
$pdf->SetTitle('عقود إيجار تجريبية');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(18, 16, 18);
$pdf->SetAutoPageBreak(true, 16);
$pdf->setRTL(true);
$font = 'dejavusans';

$style = <<<'CSS'
<style>
  h1 { text-align:center; font-size:17px; }
  .sub { text-align:center; font-size:10px; color:#444; }
  table { width:100%; border-collapse:collapse; }
  td { border:1px solid #999; padding:4px; font-size:10px; }
  .head td { background-color:#eee; font-weight:bold; text-align:center; }
  .lbl { font-weight:bold; width:32%; }
  .sec { font-weight:bold; font-size:12px; }
  p { font-size:10px; line-height:1.5; }
</style>
CSS;

/** يبني عقداً واحداً ويضعه في صفحة مستقلة. */
function contract(TCPDF $pdf, string $font, string $style, array $c): void
{
    $pdf->AddPage();
    $pdf->SetFont($font, '', 11);

    $rowsDue = '';
    foreach ($c['due'] as $i => $d) {
        $n = $i + 1;
        $rowsDue .= "<tr><td>{$n}</td><td>{$d['dt']}</td><td>{$d['amt']}</td></tr>";
    }
    $dueSection = $c['due']
        ? "<p class=\"sec\">مواعيد استحقاق الدفعات</p><table><tr class=\"head\"><td>الدفعة</td><td>تاريخ الاستحقاق</td><td>المبلغ</td></tr>{$rowsDue}</table>"
        : "<p class=\"sec\">جدول الدفعات</p><p>تُسدَّد الدفعات شهرياً بقيمة {$c['payment_amount']} ريال اعتباراً من تاريخ بداية العقد ولمدة {$c['payments_count']} دفعة.</p>";

    $html = $style . <<<HTML
<h1>{$c['title']}</h1>
<p class="sub">رقم العقد: {$c['no']} &nbsp;|&nbsp; تاريخ التحرير: {$c['date']}</p>
<hr>
<table>
  <tr><td class="lbl">الطرف الأول (المؤجِّر)</td><td>{$c['landlord']}</td></tr>
  <tr><td class="lbl">الطرف الثاني (المستأجِر)</td><td>{$c['tenant']}</td></tr>
</table>
<p class="sec">وصف العقار</p>
<p>{$c['property']}</p>
<table>
  <tr><td class="lbl">تاريخ بداية الإيجار</td><td>{$c['start']}</td></tr>
  <tr><td class="lbl">تاريخ نهاية الإيجار</td><td>{$c['end']}</td></tr>
  <tr><td class="lbl">قيمة الإيجار</td><td>{$c['rent_value']} ريال سعودي</td></tr>
  <tr><td class="lbl">عدد الدفعات</td><td>{$c['payments_count']}</td></tr>
  <tr><td class="lbl">قيمة الدفعة الواحدة</td><td>{$c['payment_amount']} ريال سعودي</td></tr>
</table>
{$dueSection}
<p class="sec">شروط التجديد والإنهاء</p>
<p>{$c['renewal']}</p>
<p>{$c['termination']}</p>
<br>
<table style="border:none;">
  <tr>
    <td style="border:none; text-align:center;">توقيع المؤجِّر<br><br>_______________</td>
    <td style="border:none; text-align:center;">توقيع المستأجِر<br><br>_______________</td>
  </tr>
</table>
HTML;

    $pdf->writeHTML($html, true, false, true, false, '');
}

$contracts = [
    // 1) محل تجاري — ربع سنوي بتواريخ صريحة
    [
        'title' => 'عقد إيجار محل تجاري', 'no' => '1203/2026', 'date' => '2026-06-01',
        'landlord' => 'شركة نور الصباح العقارية', 'tenant' => 'مؤسسة الأفق للتجارة',
        'property' => 'محل تجاري رقم 12، الدور الأرضي، شارع الملك فهد، حي العليا، الرياض، مساحة 80 م².',
        'start' => '2026-07-01', 'end' => '2027-06-30',
        'rent_value' => '60000', 'payments_count' => '4 دفعات (ربع سنوية)', 'payment_amount' => '15000',
        'due' => [
            ['dt' => '2026-07-01', 'amt' => '15000'], ['dt' => '2026-10-01', 'amt' => '15000'],
            ['dt' => '2027-01-01', 'amt' => '15000'], ['dt' => '2027-04-01', 'amt' => '15000'],
        ],
        'renewal' => 'يُجدَّد العقد تلقائياً لمدة سنة ما لم يُخطر أحد الطرفين الآخر كتابياً قبل 60 يوماً.',
        'termination' => 'يحق للمؤجِّر الفسخ عند التأخر عن سداد دفعتين متتاليتين.',
    ],
    // 2) شقة سكنية — شهري دون تواريخ صريحة (يُحتسب آلياً)
    [
        'title' => 'عقد إيجار شقة سكنية', 'no' => '884/2026', 'date' => '2026-08-01',
        'landlord' => 'عبدالعزيز محمد القحطاني', 'tenant' => 'سارة أحمد العتيبي',
        'property' => 'شقة سكنية رقم 7، الدور الثالث، حي النرجس، جدة، تتكوّن من 3 غرف وصالة.',
        'start' => '2026-08-15', 'end' => '2027-08-14',
        'rent_value' => '36000', 'payments_count' => '12 دفعة (شهرية)', 'payment_amount' => '3000',
        'due' => [],
        'renewal' => 'يُجدَّد العقد لمدة سنة بنفس القيمة عند رغبة الطرفين.',
        'termination' => 'يحق لأي طرف الإنهاء بإشعار مسبق مدته 30 يوماً.',
    ],
    // 3) مستودع — عقد سنتين، نصف سنوي بتواريخ صريحة
    [
        'title' => 'عقد إيجار مستودع', 'no' => '2025-553', 'date' => '2026-08-20',
        'landlord' => 'مؤسسة الخليج للمستودعات', 'tenant' => 'شركة البحر الأحمر للشحن',
        'property' => 'مستودع رقم 4، المنطقة الصناعية الثانية، الدمام، مساحة 500 م² مع ساحة تحميل.',
        'start' => '2026-09-01', 'end' => '2028-08-31',
        'rent_value' => '240000', 'payments_count' => '4 دفعات (نصف سنوية)', 'payment_amount' => '60000',
        'due' => [
            ['dt' => '2026-09-01', 'amt' => '60000'], ['dt' => '2027-03-01', 'amt' => '60000'],
            ['dt' => '2027-09-01', 'amt' => '60000'], ['dt' => '2028-03-01', 'amt' => '60000'],
        ],
        'renewal' => 'يُجدَّد العقد لمدة سنتين إضافيتين باتفاق مكتوب بين الطرفين.',
        'termination' => 'يُفسخ العقد عند الإخلال بشروط السلامة أو التأخر عن السداد أكثر من 45 يوماً.',
    ],
];

foreach ($contracts as $c) {
    contract($pdf, $font, $style, $c);
}

$out = __DIR__ . '/../samples/rent_contracts_sample_1.pdf';
$pdf->Output($out, 'F');
echo "WROTE: $out (" . filesize($out) . " bytes, pages=" . $pdf->getNumPages() . ")" . PHP_EOL;
