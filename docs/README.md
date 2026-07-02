# وثائق وعيّنات — وحدات الذكاء الاصطناعي (نور الصباح)

## المحتوى
- **USAGE_GUIDE_AI_AR.pdf** — دليل استخدام وحدتي المشتريات والإيجارات بالذكاء الاصطناعي،
  يشمل الخطوات، المسارات (URLs)، متطلبات التشغيل على الخادم، والميزات المحدَّثة.
- **samples/** — عقود إيجار تجريبية (PDF) لاختبار الاستخراج وتوليد الدفعات:
  - `rent_contracts_sample_1.pdf` — 3 عقود (ربع سنوي/شهري/نصف سنوي).
  - `rent_contracts_sample_2.pdf` — 4 عقود (نهاية شهر/تواريخ يوم-شهر-سنة/سنوي/ثنائي اللغة).
  - `rent_contracts_sample_3.pdf` — 4 عقود (أرقام عربية-هندية/ناقص/دفعات متصاعدة/قصير).
- **generators/** — سكربتات PHP لإعادة توليد الدليل والعيّنات (TCPDF، دعم عربي RTL).

## إعادة التوليد
```bash
php docs/generators/make_guide.php        # يحدّث docs/USAGE_GUIDE_AI_AR.pdf
php docs/generators/make_contracts.php     # يحدّث docs/samples/rent_contracts_sample_1.pdf
php docs/generators/make_contracts2.php    # sample_2
php docs/generators/make_contracts3.php    # sample_3
```
تتطلب تثبيت اعتماديات المشروع (`composer install`) لتوفّر TCPDF.

> ملاحظة: العيّنات بيانات وهمية للاختبار فقط.
