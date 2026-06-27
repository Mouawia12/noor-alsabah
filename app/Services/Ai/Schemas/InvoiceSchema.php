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
- invoice_date بصيغة YYYY-MM-DD إن أمكن.
- المبالغ أرقام فقط بدون رمز العملة. ضع العملة في حقل currency.
- amount_before_tax + tax_amount يجب أن يساوي total عادةً؛ إن لم يتطابق فاخفض الثقة.
- أعطِ لكل حقل درجة ثقة بين 0 و1 في field_confidence، ودرجة عامة في overall_confidence.
TXT;
    }
}
