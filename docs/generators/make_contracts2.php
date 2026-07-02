<?php
require __DIR__ . '/../../vendor/autoload.php';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Noor Al-Sabah');
$pdf->SetTitle('عقود إيجار تجريبية (مجموعة 2)');
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
    // 1) أرض زراعية — بداية 31 يناير، شهري دون تواريخ (يختبر عدم تجاوز نهاية الشهر)
    [
        'title' => 'عقد إيجار أرض زراعية', 'no' => 'ز-2026/77', 'date' => '2026-01-15',
        'landlord' => 'ورثة سالم الدوسري', 'tenant' => 'شركة الواحة الزراعية',
        'property' => 'أرض زراعية مساحتها 5000 م²، على طريق القصيم، مدينة بريدة، مزوّدة ببئر ارتوازي.',
        'start' => '2026-01-31', 'end' => '2026-07-31',
        'rent_value' => '30000', 'payments_count' => '6 دفعات (شهرية)', 'payment_amount' => '5000',
        'due' => [],
        'renewal' => 'يُجدَّد العقد لموسم زراعي آخر باتفاق الطرفين.',
        'termination' => 'يُفسخ العقد عند إهمال الأرض أو تغيير نشاطها دون إذن المالك.',
    ],
    // 2) مكتب — تواريخ بصيغة يوم/شهر/سنة (يختبر تطبيع DateNormalizer)
    [
        'title' => 'عقد إيجار مكتب', 'no' => '2026/445', 'date' => '10/02/2026',
        'landlord' => 'مؤسسة البيان للعقارات', 'tenant' => 'مكتب النخبة للمحاماة',
        'property' => 'مكتب رقم 305، الدور الثالث، برج الأعمال، شارع التحلية، الخبر، مساحة 120 م².',
        'start' => '01/03/2026', 'end' => '28/02/2027',
        'rent_value' => '48000', 'payments_count' => '2 دفعتان (نصف سنوية)', 'payment_amount' => '24000',
        'due' => [
            ['dt' => '01/03/2026', 'amt' => '24000'], ['dt' => '01/09/2026', 'amt' => '24000'],
        ],
        'renewal' => 'يُجدَّد العقد تلقائياً لسنة واحدة ما لم يُخطر أحد الطرفين الآخر قبل 90 يوماً.',
        'termination' => 'يحق للمستأجر الإنهاء المبكر بدفع غرامة تعادل دفعة واحدة.',
    ],
    // 3) صالة عرض — دفعة سنوية واحدة
    [
        'title' => 'عقد إيجار صالة عرض', 'no' => 'SH-2026-09', 'date' => '2026-09-10',
        'landlord' => 'شركة الديار المتحدة', 'tenant' => 'معرض الفخامة للسيارات',
        'property' => 'صالة عرض رقم 2، طريق الملك عبدالعزيز، الرياض، مساحة 350 م² بواجهة زجاجية.',
        'start' => '2026-10-01', 'end' => '2027-09-30',
        'rent_value' => '200000', 'payments_count' => 'دفعة واحدة (سنوية)', 'payment_amount' => '200000',
        'due' => [
            ['dt' => '2026-10-01', 'amt' => '200000'],
        ],
        'renewal' => 'يُجدَّد العقد بزيادة 5% على قيمة الإيجار السنوية.',
        'termination' => 'لا يجوز الفسخ قبل انتهاء المدة إلا باتفاق كتابي بين الطرفين.',
    ],
    // 4) فيلا — عقد ثنائي اللغة عربي/إنجليزي
    [
        'title' => 'عقد إيجار فيلا / Villa Lease Agreement', 'no' => 'VL-2026-21', 'date' => '2026-10-25',
        'landlord' => 'شركة خالد العقارية (Khalid Real Estate Co.)', 'tenant' => 'جون سميث (John Smith)',
        'property' => 'فيلا رقم 9، حي الياسمين، الرياض، مساحة 600 م² — Villa No. 9, Al Yasmin District, Riyadh, 600 sqm.',
        'start' => '2026-11-01', 'end' => '2027-10-31',
        'rent_value' => '150000', 'payments_count' => '3 دفعات (كل 4 أشهر)', 'payment_amount' => '50000',
        'due' => [
            ['dt' => '2026-11-01', 'amt' => '50000'], ['dt' => '2027-03-01', 'amt' => '50000'],
            ['dt' => '2027-07-01', 'amt' => '50000'],
        ],
        'renewal' => 'Renewable for one year by mutual written agreement / يُجدَّد لسنة باتفاق كتابي متبادل.',
        'termination' => 'Either party may terminate with 60 days notice / يحق لأي طرف الإنهاء بإشعار 60 يوماً.',
    ],
];

foreach ($contracts as $c) {
    contract($pdf, $font, $style, $c);
}

$out = __DIR__ . '/../samples/rent_contracts_sample_2.pdf';
$pdf->Output($out, 'F');
echo 'WROTE: ' . $out . ' (' . filesize($out) . " bytes)\n";
