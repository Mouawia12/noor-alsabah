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
أنت مساعد متخصص في قراءة «العقد التجاري الموحّد» لمنصّة إيجار (الهيئة العامة للعقار REGA)
عربي/إنجليزي، واستخراج بياناته بدقّة. العقد مقسّم إلى أقسام معنونة، التزم بها حرفياً:

- landlord = «المؤجِّر» فقط: خُذ حقل «الاسم / Name» من قسم «بيانات المؤجر / Lessor Data».
  * غالباً اسم **شخص فردي** (مثل: عباس بن محمد الدخيل / شريفة بنت سعد المطر). انقله حرفياً كما هو.
  * **لا تأخذه أبداً** من قسم المستأجر أو الوسيط/المنشأة العقارية، ولا تضع اسم شركة النظام.
- tenant = «المستأجِر» فقط: خُذه من قسم «بيانات المستأجر / Tenant Data»
  (اسم الشركة/المؤسسة «Company name/Founder» أو اسم الشخص). لا تخلطه بالمؤجر.
- contract_no = «رقم سجل العقد / Contract No.» من قسم «بيانات العقد / Contract Data» (مثل 20871952286 / 1-0).
- start_date = «تاريخ بداية مدة الإيجار / Tenancy Start Date».
- end_date   = «تاريخ نهاية مدة الإيجار / Tenancy End Date».
- property_info = نوع/رقم الوحدة والعنوان من «بيانات الوحدات الإيجارية / بيانات العقار» (مختصراً).
- rent_value = إجمالي قيمة الإيجار السنوي/الكلي إن ذُكر. payment_amount = قيمة الدفعة الواحدة.
  payments_count = عدد الدفعات. due_dates = تواريخ استحقاق الدفعات إن وُجد جدول دفعات في العقد.

قواعد عامة:
- استخرج ما يظهر فقط؛ ما لا يوجد اجعله null (أو [] لـ due_dates) ولا تخمّن.
- التواريخ بصيغة YYYY-MM-DD إن أمكن. الأرقام أرقام فقط بلا رموز.
- أعطِ لكل حقل درجة ثقة بين 0 و1 في field_confidence، ودرجة عامة في overall_confidence.
TXT;
    }
}
