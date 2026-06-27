<?php

namespace App\Services\Ai;

use App\Models\Supplier;

/**
 * مطابقة المورد المستخرج مع الموردين المسجّلين (مطابقة ضبابية)،
 * أو اقتراح إنشاء مورد جديد — دون إنشاء فعلي قبل اعتماد المستخدم.
 */
class SupplierMatchingService
{
    /**
     * @return array{supplier_id: int|null, matched: bool, suggestion: string|null, score: float}
     */
    public function match(?string $name, ?string $taxNumber = null): array
    {
        $name = trim((string) $name);
        $taxNumber = trim((string) $taxNumber);

        // 1) تطابق بالرقم الضريبي (الأدق)
        if ($taxNumber !== '') {
            $byTax = Supplier::where('tax_number', $taxNumber)->first();
            if ($byTax) {
                return ['supplier_id' => (int) $byTax->supplier_id, 'matched' => true, 'suggestion' => $byTax->name, 'score' => 1.0];
            }
        }

        if ($name === '') {
            return ['supplier_id' => null, 'matched' => false, 'suggestion' => null, 'score' => 0.0];
        }

        // 2) تطابق تام على الاسم المطبّع
        $norm = Supplier::normalizeName($name);
        $exact = Supplier::where('name_normalized', $norm)->first();
        if ($exact) {
            return ['supplier_id' => (int) $exact->supplier_id, 'matched' => true, 'suggestion' => $exact->name, 'score' => 0.98];
        }

        // 3) مطابقة ضبابية على أقرب اسم
        $best = null;
        $bestScore = 0.0;
        foreach (Supplier::query()->get(['supplier_id', 'name', 'name_normalized']) as $s) {
            similar_text($norm, (string) $s->name_normalized, $pct);
            $score = $pct / 100;
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $s;
            }
        }

        if ($best && $bestScore >= 0.85) {
            return ['supplier_id' => (int) $best->supplier_id, 'matched' => true, 'suggestion' => $best->name, 'score' => round($bestScore, 2)];
        }

        // لا تطابق كافٍ — اقتراح إنشاء جديد (بعد اعتماد المستخدم)
        return [
            'supplier_id' => null,
            'matched'     => false,
            'suggestion'  => $best && $bestScore >= 0.6 ? $best->name : null,
            'score'       => round($bestScore, 2),
        ];
    }

    /** إنشاء مورد جديد (يُستدعى عند اعتماد المستخدم). */
    public function create(string $name, ?string $taxNumber = null, ?int $userId = null): Supplier
    {
        return Supplier::create([
            'name'            => $name,
            'name_normalized' => Supplier::normalizeName($name),
            'tax_number'      => $taxNumber ?: null,
            'create_user'     => $userId,
        ]);
    }
}
