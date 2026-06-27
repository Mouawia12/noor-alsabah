<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * التعلّم المستمر: تسجيل تصحيحات المستخدم على الحقول المستخرجة،
 * واستخدامها كتلميحات تُحقن في مطالبة الاستخراج لتحسين الدقة مع الوقت.
 */
class CorrectionService
{
    /** الحقول التي نتتبّع تصحيحاتها لكل نوع. */
    protected const TRACK = [
        'purchase' => ['supplier_name', 'tax_number', 'invoice_no', 'invoice_date', 'currency', 'amount_before_tax', 'tax_amount', 'total'],
        'rent'     => ['contract_no', 'start_date', 'end_date', 'landlord', 'tenant', 'rent_value', 'payments_count', 'payment_amount'],
    ];

    /** تسجيل الفروق بين المستخرَج والقيمة النهائية المعتمدة. */
    public function record(string $kind, array $extracted, array $final, ?string $entityKey = null, ?int $userId = null): int
    {
        $count = 0;
        foreach (self::TRACK[$kind] ?? [] as $f) {
            $ex = $extracted[$f] ?? null;
            $fi = $final[$f] ?? null;
            // تجاهل غير المتغيّر أو الفارغ من الطرفين
            if ($this->norm($ex) === $this->norm($fi) || ($this->norm($fi) === '')) {
                continue;
            }
            DB::table('ai_correction')->insert([
                'kind'            => $kind,
                'entity_key'      => $entityKey,
                'field'           => $f,
                'extracted_value' => is_scalar($ex) ? (string) $ex : json_encode($ex),
                'corrected_value' => is_scalar($fi) ? (string) $fi : json_encode($fi),
                'user_id'         => $userId,
                'created_at'      => now(),
            ]);
            $count++;
        }
        if ($count) {
            Cache::forget('ai_hints_' . $kind);
        }
        return $count;
    }

    /** تلميحات تُحقن في المطالبة (أحدث تصحيحات مميّزة) — مُخزَّنة مؤقتاً. */
    public function hints(string $kind, int $limit = 8): string
    {
        return Cache::remember('ai_hints_' . $kind, now()->addHours(2), function () use ($kind, $limit) {
            $rows = DB::table('ai_correction')
                ->where('kind', $kind)
                ->orderByDesc('id')
                ->limit(50)->get(['field', 'extracted_value', 'corrected_value']);

            $seen = [];
            $lines = [];
            foreach ($rows as $r) {
                $key = $r->field . '|' . $r->extracted_value;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $lines[] = "- الحقل {$r->field}: سبق أن صُحّح من \"{$r->extracted_value}\" إلى \"{$r->corrected_value}\".";
                if (count($lines) >= $limit) {
                    break;
                }
            }

            if (empty($lines)) {
                return '';
            }

            return "\n\nاسترشد بهذه التصحيحات السابقة من المستخدمين لتفادي أخطاء متكررة:\n" . implode("\n", $lines);
        });
    }

    protected function norm($v): string
    {
        return trim(mb_strtolower((string) ($v ?? '')));
    }
}
