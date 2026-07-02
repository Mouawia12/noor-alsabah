<?php

namespace App\Services\Ai\Schemas;

/**
 * مخطط استخراج الفاتورة (Structured Output) + ثقة لكل حقل.
 * يخدم اللغات المختلطة عربي/إنجليزي.
 */
class InvoiceSchema
{
    /** الحقول المطلوب استخراجها وأنواعها. */
    public const FIELDS = [
        'supplier_name'     => ['string', 'null'],
        'tax_number'        => ['string', 'null'],
        'invoice_no'        => ['string', 'null'],
        'invoice_date'      => ['string', 'null'], // صيغة YYYY-MM-DD إن أمكن
        'currency'          => ['string', 'null'],
        'amount_before_tax' => ['number', 'null'],
        'tax_amount'        => ['number', 'null'],
        'total'             => ['number', 'null'],
        'note'              => ['string', 'null'],
    ];

    public static function schema(): array
    {
        return SchemaBuilder::build(self::FIELDS);
    }

    public static function prompt(): string
    {
        return <<<'TXT'
أنت مساعد متخصص في قراءة الفواتير (عربية/إنجليزية/مختلطة) واستخراج بياناتها بدقة.
- استخرج الحقول كما تظهر في الفاتورة. إن لم يوجد حقل اجعله null ولا تخمّن.

- supplier_name: انقل اسم المورد/الشركة **حرفياً كما هو مكتوب** في الفاتورة (عربي أو إنجليزي)
  دون ترجمة أو تحويل حروف أو اختصار أو إعادة صياغة. إن كان بالإنجليزية اتركه بالإنجليزية،
  وإن كان بالعربية اتركه بالعربية. وهو اسم البائع/المورد لا اسم المشتري/العميل.

- tax_number: **الرقم الضريبي (VAT) للمورد** (البائع) لا رقم المشتري.
  * في السعودية غالباً 15 خانة يبدأ بـ3 وينتهي بـ3. قد يظهر بعنوان:
    «الرقم الضريبي» أو «الرقم الضريبي للمورد» أو «VAT No» أو «VAT Registration Number» أو «TRN».
  * انقله أرقاماً فقط بدون فراغات أو رموز.
  * **لا تخلطه** مع «رقم السجل التجاري C.R.» أو «رقم الفاتورة» أو «الآيبان» أو الهاتف.
  * إن ظهر رقم بطول 10–15 خانة بجوار كلمة ضريبي/VAT/TRN فهو الرقم الضريبي؛ استخرجه.

- invoice_no: رقم الفاتورة كما هو (قد يحوي حروفاً/شرطات).
- invoice_date بصيغة YYYY-MM-DD إن أمكن.
- المبالغ أرقام فقط بدون رمز العملة. ضع العملة في حقل currency.
- amount_before_tax + tax_amount يجب أن يساوي total عادةً؛ إن لم يتطابق فاخفض الثقة.
- أعطِ لكل حقل درجة ثقة بين 0 و1 في field_confidence، ودرجة عامة في overall_confidence.
TXT;
    }
}
