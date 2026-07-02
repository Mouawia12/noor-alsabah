<?php

namespace App\Services\Ai;

/**
 * تحقق منطقي من بيانات الفاتورة المستخرجة (لا يغيّر القيم — يُرجع ملاحظات).
 */
class InvoiceValidationService
{
    /**
     * @return array{issues: string[], math_ok: bool}
     */
    public function validate(array $data): array
    {
        $issues = [];

        $before = $this->num($data['amount_before_tax'] ?? null);
        $tax    = $this->num($data['tax_amount'] ?? null);
        $total  = $this->num($data['total'] ?? null);

        $mathOk = true;
        if ($before !== null && $tax !== null && $total !== null) {
            // سماحية بسيطة للتقريب
            if (abs(($before + $tax) - $total) > 0.05) {
                $issues[] = 'قيمة الضريبة غير متطابقة: الإجمالي لا يساوي (المبلغ قبل الضريبة + الضريبة).';
                $mathOk = false;
            }
        }

        if (empty($data['invoice_no'])) {
            $issues[] = 'رقم الفاتورة مفقود.';
        }

        if (! empty($data['invoice_date']) && ! $this->looksLikeDate($data['invoice_date'])) {
            $issues[] = 'تاريخ الفاتورة بصيغة غير واضحة.';
        }

        // الرقم الضريبي: تمييز الغياب الكامل عن الصيغة الخاطئة (طلب العميل: «الرقم الضريبي غير موجود»)
        if (empty($data['tax_number'])) {
            $issues[] = 'الرقم الضريبي غير موجود.';
        } elseif (! preg_match('/^\d{10,15}$/', preg_replace('/\s+/', '', (string) $data['tax_number']))) {
            $issues[] = 'الرقم الضريبي بصيغة غير معتادة.';
        }

        return ['issues' => $issues, 'math_ok' => $mathOk];
    }

    protected function num($v): ?float
    {
        if ($v === null || $v === '') {
            return null;
        }
        return is_numeric($v) ? (float) $v : null;
    }

    protected function looksLikeDate($v): bool
    {
        return DateNormalizer::looksLikeDate($v);
    }
}
