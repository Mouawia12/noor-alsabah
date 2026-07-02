<?php
require __DIR__ . '/../../vendor/autoload.php';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Noor Al-Sabah');
$pdf->SetTitle('دليل استخدام وحدات الذكاء الاصطناعي — نور الصباح');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);
$pdf->setFooterFont(['dejavusans', '', 8]);
$pdf->SetMargins(18, 16, 18);
$pdf->SetAutoPageBreak(true, 18);
$pdf->setRTL(true);
$font = 'dejavusans';

$css = <<<'CSS'
<style>
  h1 { text-align:center; font-size:20px; color:#1a3c6e; }
  h2 { font-size:15px; color:#1a3c6e; border-bottom:2px solid #1a3c6e; padding-bottom:3px; }
  h3 { font-size:12px; color:#333; }
  p, li, td { font-size:10.5px; line-height:1.6; }
  .sub { text-align:center; font-size:11px; color:#666; }
  table { width:100%; border-collapse:collapse; }
  td, th { border:1px solid #aaa; padding:5px; font-size:9.5px; }
  th { background-color:#1a3c6e; color:#fff; }
  .path { font-family:courier; direction:ltr; text-align:left; color:#0a5; }
  .note { background-color:#fff6e6; border:1px solid #e0b060; padding:8px; font-size:10px; }
  .ok { color:#0a7d2c; font-weight:bold; }
</style>
CSS;

// ---------- الغلاف ----------
$pdf->AddPage();
$pdf->SetFont($font, '', 11);
$cover = $css . <<<'HTML'
<br><br><br>
<h1>دليل الاستخدام</h1>
<h1>وحدات الذكاء الاصطناعي — نظام نور الصباح</h1>
<p class="sub">معالجة فواتير المشتريات وعقود الإيجار آلياً بالذكاء الاصطناعي</p>
<br><br>
<table>
  <tr><th>المحتوى</th></tr>
  <tr><td>1. نظرة عامة ومتطلبات التشغيل</td></tr>
  <tr><td>2. وحدة المشتريات بالذكاء الاصطناعي</td></tr>
  <tr><td>3. وحدة الإيجارات بالذكاء الاصطناعي</td></tr>
  <tr><td>4. التنبيهات وسندات الاستلام</td></tr>
  <tr><td>5. الميزات المحدَّثة (دقة الاستخراج، تقرير الحالة، منع التكرار)</td></tr>
  <tr><td>6. جدول المسارات الكامل (URLs)</td></tr>
</table>
<br>
<p class="sub">الإصدار: يوليو 2026 — تُقرأ المسارات نسبةً إلى نطاق النظام، وجميعها تبدأ بـ /dashboard</p>
HTML;
$pdf->writeHTML($cover, true, false, true, false, '');

// ---------- 1. نظرة عامة ----------
$pdf->AddPage();
$s1 = $css . <<<'HTML'
<h2>1. نظرة عامة ومتطلبات التشغيل</h2>
<p>تتيح الوحدتان رفع ملفات PDF أو صور تحتوي على فواتير أو عقود، فيقوم النظام تلقائياً بـ:
فصل المستندات، واستخراج البيانات بالذكاء الاصطناعي، والتحقق منها، ثم عرضها للمراجعة والاعتماد.</p>

<h3>متطلبات لازمة ليعمل النظام فعلياً (مهمّة)</h3>
<div class="note">
إذا لم تظهر أي نتيجة بعد الرفع، فالسبب غالباً أحد الآتي على الخادم — وليس في الكود:
<ul>
  <li><b>عامل الطابور</b> يجب أن يعمل باستمرار: <span class="path">php artisan queue:work --tries=3</span><br>
      بدونه لا تُعالَج الفواتير/العقود إطلاقاً (تبقى «قيد الانتظار»).</li>
  <li><b>مفتاح الذكاء الاصطناعي</b> مضبوط وصالح في ملف <span class="path">.env</span> (OPENAI_API_KEY) والنموذج متاح للمفتاح.</li>
  <li><b>أدوات تحويل PDF</b> مثبّتة على الخادم: <span class="path">poppler-utils</span> و <span class="path">ghostscript</span>.</li>
  <li><b>المهام المجدولة</b> للتنبيهات: <span class="path">php artisan schedule:run</span> عبر cron كل دقيقة.</li>
  <li>بعد أي تحديث: سحب الكود على الخادم + <span class="path">php artisan migrate</span> + مسح الكاش.</li>
</ul>
</div>

<h3>الحالات التي تمرّ بها الفاتورة/العقد</h3>
<table>
  <tr><th>الحالة</th><th>المعنى</th></tr>
  <tr><td>قيد الانتظار / قيد المعالجة</td><td>لم تُعالَج بعد (تأكد أن عامل الطابور يعمل).</td></tr>
  <tr><td>بانتظار المراجعة</td><td>نجح الاستخراج — جاهزة لمراجعتك واعتمادها.</td></tr>
  <tr><td>فشلت</td><td>تعذّرت القراءة (صورة غير واضحة/لا تحتوي فاتورة) مع بيان السبب.</td></tr>
  <tr><td>معتمدة / مرحّلة</td><td>حُفظت في سجلات النظام (مشتريات/عقد + دفعات).</td></tr>
</table>
HTML;
$pdf->writeHTML($s1, true, false, true, false, '');

// ---------- 2. المشتريات ----------
$pdf->AddPage();
$s2 = $css . <<<'HTML'
<h2>2. وحدة المشتريات بالذكاء الاصطناعي</h2>
<h3>خطوات الاستخدام</h3>
<ol>
  <li>افتح صفحة الرفع: <span class="path">/dashboard/purchase/ai</span></li>
  <li>ارفع ملف PDF (قد يحوي عشرات الفواتير) أو صورة (JPG/PNG) واضغط رفع.</li>
  <li>تنتقل لصفحة المتابعة مع شريط تقدّم حيّ: <span class="path">/dashboard/purchase/ai/batch/{رقم}</span></li>
  <li>بعد الاكتمال يفرز النظام الفواتير إلى: <b>بانتظار المراجعة</b> و<b>مرفوضة</b>.</li>
  <li>راجع واعتمد من: <span class="path">/dashboard/purchase/ai/review</span>
      — يمكنك تعديل الحقول، اختيار المورد، والاعتماد الفردي أو الجماعي.</li>
  <li>الفواتير المرفوضة مع أسبابها: <span class="path">/dashboard/purchase/ai/failed</span> (مع إعادة المعالجة).</li>
  <li><b>تقرير حالة كل فاتورة</b> في الملف: <span class="path">/dashboard/purchase/ai/batch/{رقم}/report</span>
      (نجح/فشل/السبب + تصدير PDF/Excel) — رابطه أيضاً في صفحة المتابعة.</li>
  <li>التقارير والإحصائيات: <span class="path">/dashboard/purchase/ai/reports</span></li>
</ol>

<h3>البيانات المستخرجة لكل فاتورة</h3>
<p>اسم المورد (يُنقل حرفياً عربي/إنجليزي دون ترجمة)، الرقم الضريبي، رقم الفاتورة، التاريخ،
العملة، المبلغ قبل الضريبة، قيمة الضريبة، الإجمالي، الملاحظات.</p>

<div class="note">
<b>منع التكرار:</b> عند الاعتماد، إذا كان رقم الفاتورة مسجَّلاً مسبقاً يرفض النظام الحفظ
ويظهر: «فاتورة مكررة: رقم الفاتورة … مسجَّل مسبقاً» — لتفادي الأخطاء المحاسبية والضريبية.
</div>
HTML;
$pdf->writeHTML($s2, true, false, true, false, '');

// ---------- 3. الإيجارات ----------
$pdf->AddPage();
$s3 = $css . <<<'HTML'
<h2>3. وحدة الإيجارات بالذكاء الاصطناعي</h2>
<h3>خطوات الاستخدام</h3>
<ol>
  <li>صفحة الرفع: <span class="path">/dashboard/rent/ai</span> — ارفع عقد إيجار (PDF/صورة).</li>
  <li>المتابعة: <span class="path">/dashboard/rent/ai/batch/{رقم}</span></li>
  <li>المراجعة والاعتماد: <span class="path">/dashboard/rent/ai/review</span>
      — اختر المحل/العقار المرتبط بالعقد ثم اعتمد.</li>
  <li>عند الاعتماد يُنشئ النظام العقد <b>ويولّد جدول الدفعات تلقائياً</b> بتواريخ الاستحقاق.</li>
  <li>العقود المتعذّرة: <span class="path">/dashboard/rent/ai/failed</span></li>
  <li>تقارير الإيجارات: <span class="path">/dashboard/rent/ai/reports</span></li>
</ol>

<h3>البيانات المستخرجة من العقد</h3>
<p>رقم العقد، تاريخ العقد، بداية/نهاية العقد، المؤجر، المستأجر، بيانات العقار، قيمة الإيجار،
عدد الدفعات، مبلغ الدفعة، تواريخ الاستحقاق، شروط التجديد والإنهاء.</p>

<h3>توليد الدفعات — الحالات المدعومة</h3>
<table>
  <tr><th>الحالة في العقد</th><th>كيف يولّد النظام الجدول</th></tr>
  <tr><td>تواريخ استحقاق مذكورة صراحةً</td><td>يستخدمها كما هي.</td></tr>
  <tr><td>تاريخ بداية + عدد دفعات (بلا تواريخ)</td><td>يحسب شهرياً من البداية (يراعي نهايات الأشهر).</td></tr>
  <tr><td>قيمة إجمالية + عدد دفعات</td><td>يوزّع بالتساوي ويضبط الباقي على آخر دفعة ليطابق الإجمالي.</td></tr>
</table>
HTML;
$pdf->writeHTML($s3, true, false, true, false, '');

// ---------- 4. التنبيهات وسندات الاستلام ----------
$pdf->AddPage();
$s4 = $css . <<<'HTML'
<h2>4. التنبيهات وسندات الاستلام</h2>
<h3>لوحة متابعة الإيجارات والتنبيهات</h3>
<p>المسار: <span class="path">/dashboard/rent/alerts</span></p>
<p>يرسل النظام تنبيهات (داخل النظام + بريد) قبل الاستحقاق وعند التأخر وقرب انتهاء العقد،
وتتضمّن: رقم العقد، المؤجر، المستأجر، العقار، رقم الدفعة (مثال: 3 من 12)، قيمة الدفعة،
تاريخ الاستحقاق، الحالة (متأخرة/مستحقة قريباً)، وعدد أيام التأخير.</p>

<h3>تأكيد استلام دفعة + سند رسمي</h3>
<ul>
  <li>تعليم الدفعة كمستلمة: <span class="path">/dashboard/rent/alerts/pay/{رقم الدفعة}</span></li>
  <li>سند استلام رسمي (PDF بترويسة المنشأة + اسم الموظف المستلِم + المبلغ رقماً وكتابةً + خانة التوقيع):
      <span class="path">/dashboard/rent/alerts/receipt/{رقم الدفعة}</span></li>
</ul>

<h3>الجدولة اليومية</h3>
<p>أمر التنبيهات اليومي: <span class="path">php artisan rent:alerts</span> (يُشغَّل تلقائياً عبر المجدول 08:00).</p>
HTML;
$pdf->writeHTML($s4, true, false, true, false, '');

// ---------- 5. الميزات المحدَّثة ----------
$pdf->AddPage();
$s5 = $css . <<<'HTML'
<h2>5. الميزات المحدَّثة (يوليو 2026)</h2>
<table>
  <tr><th>الميزة</th><th>الوصف</th></tr>
  <tr><td>دقة الرقم الضريبي</td>
      <td>تعليمات دقيقة للنموذج لالتقاط الرقم الضريبي (15 خانة، VAT/TRN) وعدم خلطه بالسجل التجاري أو رقم الفاتورة.</td></tr>
  <tr><td>نقل الاسم حرفياً</td>
      <td>اسم المورد/الشركة يُنقل كما هو تماماً (عربي أو إنجليزي) دون ترجمة أو تعديل.</td></tr>
  <tr><td>تقرير حالة الفواتير</td>
      <td>لكل ملف: جدول بكل فاتورة وحالتها (نجح/فشل) وسببها والحقول الأساسية والثقة، قابل للتصدير PDF/Excel.</td></tr>
  <tr><td>منع تكرار الفاتورة</td>
      <td>رفض حفظ فاتورة برقم موجود مسبقاً مع رسالة «فاتورة مكررة» (اعتماد فردي وجماعي).</td></tr>
  <tr><td>رسائل واضحة</td>
      <td>الفواتير الفاشلة تُعرض بسبب مفهوم بدل رسائل تقنية.</td></tr>
</table>

<div class="note">
<b>ملاحظة عن جودة الاستخراج:</b> دقة قراءة الرقم الضريبي والاسم تعتمد على وضوح صورة الفاتورة
وعلى النموذج المستخدم. يُنصح برفع ملفات واضحة (مسح ضوئي جيد) للحصول على أفضل النتائج.
</div>
HTML;
$pdf->writeHTML($s5, true, false, true, false, '');

// ---------- 6. جدول المسارات ----------
$pdf->AddPage();
$rows = [
    ['المشتريات — الرفع', '/dashboard/purchase/ai'],
    ['المشتريات — المراجعة والاعتماد', '/dashboard/purchase/ai/review'],
    ['المشتريات — متابعة دفعة', '/dashboard/purchase/ai/batch/{id}'],
    ['المشتريات — تقرير حالة الفواتير', '/dashboard/purchase/ai/batch/{id}/report'],
    ['المشتريات — الفواتير المرفوضة', '/dashboard/purchase/ai/failed'],
    ['المشتريات — التقارير', '/dashboard/purchase/ai/reports'],
    ['الإيجارات — الرفع', '/dashboard/rent/ai'],
    ['الإيجارات — المراجعة والاعتماد', '/dashboard/rent/ai/review'],
    ['الإيجارات — متابعة دفعة', '/dashboard/rent/ai/batch/{id}'],
    ['الإيجارات — العقود المتعذّرة', '/dashboard/rent/ai/failed'],
    ['الإيجارات — التقارير', '/dashboard/rent/ai/reports'],
    ['التنبيهات — لوحة المتابعة', '/dashboard/rent/alerts'],
    ['التنبيهات — سند استلام (PDF)', '/dashboard/rent/alerts/receipt/{id}'],
];
$tr = '';
foreach ($rows as $r) {
    $tr .= "<tr><td>{$r[0]}</td><td class=\"path\">{$r[1]}</td></tr>";
}
$s6 = $css . <<<HTML
<h2>6. جدول المسارات الكامل</h2>
<p>استبدل {id} برقم الدفعة/العنصر. جميع المسارات تتطلب تسجيل الدخول.</p>
<table>
  <tr><th>الصفحة</th><th>المسار</th></tr>
  {$tr}
</table>
<br><br>
<p class="sub">— انتهى الدليل — نظام نور الصباح © 2026</p>
HTML;
$pdf->writeHTML($s6, true, false, true, false, '');

$out = __DIR__ . '/../USAGE_GUIDE_AI_AR.pdf';
$pdf->Output($out, 'F');
echo 'WROTE: ' . $out . ' (' . filesize($out) . " bytes)\n";
