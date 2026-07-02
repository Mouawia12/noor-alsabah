<?php
require __DIR__ . '/../../vendor/autoload.php';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Noor Al-Sabah');
$pdf->SetTitle('عقود إيجار تجريبية (مجموعة 3)');
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
        : "<p class=\"sec\">جدول الدفعات</p><p>{$c['pay_note']}</p>";

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
  <tr><td class="lbl">قيمة الإيجار</td><td>{$c['rent_value']}</td></tr>
  <tr><td class="lbl">عدد الدفعات</td><td>{$c['payments_count']}</td></tr>
  <tr><td class="lbl">قيمة الدفعة الواحدة</td><td>{$c['payment_amount']}</td></tr>
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
    // 1) مزرعة دواجن — أرقام عربية-هندية (يختبر OCR للأرقام + تطبيعها)
    [
        'title' => 'عقد إيجار مزرعة دواجن', 'no' => '٢٠٢٦/٩٩', 'date' => '٢٠٢٦-٠٤-١٠',
        'landlord' => 'مزارع الريف', 'tenant' => 'شركة الإنتاج الداجني',
        'property' => 'مزرعة دواجن على طريق المدينة المنوّرة، مساحة ١٠٠٠٠ م²، تضم ٤ عنابر.',
        'start' => '٢٠٢٦-٠٥-٠١', 'end' => '٢٠٢٧-٠٤-٣٠',
        'rent_value' => '٧٢٠٠٠ ريال سعودي', 'payments_count' => '١٢ دفعة (شهرية)', 'payment_amount' => '٦٠٠٠ ريال',
        'due' => [],
        'pay_note' => 'تُسدَّد الدفعات شهرياً بقيمة ٦٠٠٠ ريال اعتباراً من تاريخ بداية العقد ولمدة ١٢ شهراً.',
        'renewal' => 'يُجدَّد العقد لسنة أخرى بنفس القيمة عند رغبة الطرفين.',
        'termination' => 'يُفسخ العقد عند الإخلال بالشروط الصحية للمزرعة.',
    ],
    // 2) غرفة مكتبية — عقد ناقص: بلا رقم وبلا تاريخ نهاية (يختبر القيم المفقودة)
    [
        'title' => 'عقد إيجار غرفة مكتبية', 'no' => 'غير مذكور', 'date' => '2026-05-28',
        'landlord' => 'أحمد الزهراني', 'tenant' => 'شركة ناشئة للتقنية',
        'property' => 'غرفة مكتبية ضمن مساحة عمل مشتركة، مركز ريادة الأعمال، الرياض.',
        'start' => '2026-06-01', 'end' => 'غير محدد (عقد مفتوح)',
        'rent_value' => '12000 ريال سنوياً', 'payments_count' => 'غير محدد', 'payment_amount' => '1000 ريال شهرياً',
        'due' => [],
        'pay_note' => 'تُدفع الأجرة شهرياً بقيمة 1000 ريال، ويُجدَّد الاتفاق تلقائياً ما لم يُنهَ بإشعار.',
        'renewal' => 'عقد مفتوح يتجدّد شهرياً.',
        'termination' => 'يحق لأي طرف الإنهاء بإشعار مسبق مدته 15 يوماً.',
    ],
    // 3) مبنى إداري — 3 سنوات، دفعات سنوية متصاعدة (مبالغ مختلفة)
    [
        'title' => 'عقد إيجار مبنى إداري', 'no' => 'LT-2026-300', 'date' => '2026-12-15',
        'landlord' => 'الشركة الوطنية للعقارات', 'tenant' => 'بنك التنمية',
        'property' => 'مبنى إداري كامل من 4 أدوار، حي الروضة، جدة، مساحة إجمالية 2000 م² مع موقف خاص.',
        'start' => '2027-01-01', 'end' => '2029-12-31',
        'rent_value' => '900000 ريال (إجمالي 3 سنوات)', 'payments_count' => '3 دفعات سنوية متصاعدة', 'payment_amount' => 'متغيّرة',
        'due' => [
            ['dt' => '2027-01-01', 'amt' => '280000'], ['dt' => '2028-01-01', 'amt' => '300000'],
            ['dt' => '2029-01-01', 'amt' => '320000'],
        ],
        'renewal' => 'يُجدَّد العقد لثلاث سنوات أخرى بزيادة سنوية متفق عليها.',
        'termination' => 'لا يُفسخ العقد إلا بالتراضي أو بحكم قضائي.',
    ],
    // 4) كشك سوق شعبي — عقد قصير 5 أشهر
    [
        'title' => 'عقد إيجار كشك', 'no' => 'KSK-77', 'date' => '2026-07-01',
        'landlord' => 'إدارة السوق الشعبي', 'tenant' => 'مطعم الذوّاقة',
        'property' => 'كشك رقم 5، السوق الشعبي المركزي، أبها، مساحة 12 م².',
        'start' => '2026-07-10', 'end' => '2026-12-09',
        'rent_value' => '7500 ريال', 'payments_count' => '5 دفعات (شهرية)', 'payment_amount' => '1500 ريال',
        'due' => [
            ['dt' => '2026-07-10', 'amt' => '1500'], ['dt' => '2026-08-10', 'amt' => '1500'],
            ['dt' => '2026-09-10', 'amt' => '1500'], ['dt' => '2026-10-10', 'amt' => '1500'],
            ['dt' => '2026-11-10', 'amt' => '1500'],
        ],
        'renewal' => 'يُجدَّد العقد موسمياً حسب توفّر الكشك.',
        'termination' => 'يُلغى الحجز عند التأخر عن السداد أكثر من 10 أيام.',
    ],
];

foreach ($contracts as $c) {
    contract($pdf, $font, $style, $c);
}

$out = __DIR__ . '/../samples/rent_contracts_sample_3.pdf';
$pdf->Output($out, 'F');
echo 'WROTE: ' . $out . ' (' . filesize($out) . " bytes)\n";
