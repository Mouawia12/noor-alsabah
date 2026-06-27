<?php

namespace App\Services\Ai\Schemas;

/**
 * مخطط استخراج عقد الإيجار (Structured Output) + ثقة لكل حقل.
 */
class ContractSchema
{
    public const FIELDS = [
        'contract_no'       => ['string', 'null'],
        'contract_date'     => ['string', 'null'],
        'start_date'        => ['string', 'null'],
        'end_date'          => ['string', 'null'],
        'landlord'          => ['string', 'null'],
        'tenant'            => ['string', 'null'],
        'property_info'     => ['string', 'null'],
        'rent_value'        => ['number', 'null'],
        'payments_count'    => ['integer', 'null'],
        'payment_amount'    => ['number', 'null'],
        'renewal_terms'     => ['string', 'null'],
        'termination_terms' => ['string', 'null'],
    ];

    public static function schema(): array
    {
        // due_dates مصفوفة تواريخ — تُضاف يدوياً لأنها ليست حقلاً مفرداً
        $schema = SchemaBuilder::build(self::FIELDS, extraDataProps: [
            'due_dates' => ['type' => 'array', 'items' => ['type' => 'string']],
        ], extraConfidenceKeys: ['due_dates']);

        return $schema;
    }

    public static function prompt(): string
    {
        return <<<'TXT'
أنت مساعد متخصص في قراءة عقود الإيجار (عربية/إنجليزية/مختلطة) واستخراج بياناتها بدقة.
- استخرج بيانات العقد كما تظهر. ما لا يوجد اجعله null (أو [] لـ due_dates) ولا تخمّن.
- التواريخ بصيغة YYYY-MM-DD إن أمكن.
- rent_value و payment_amount أرقام فقط. payments_count عدد صحيح.
- due_dates: قائمة تواريخ استحقاق الدفعات إن كانت مذكورة في العقد.
- أعطِ لكل حقل درجة ثقة بين 0 و1 في field_confidence، ودرجة عامة في overall_confidence.
TXT;
    }
}
