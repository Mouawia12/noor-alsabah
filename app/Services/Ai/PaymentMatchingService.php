<?php

namespace App\Services\Ai;

use App\Models\RentPaymentMatchItem;
use App\Models\ShopRentpay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * مطابقة دفعة إيجار واردة (غير مربوطة) بالعقد/المحل/الدفعة المستحقة الصحيحة.
 * تدرّج ثقة على غرار SupplierMatchingService: رقم العقد ← هوية/اسم المستأجر ← رقم الوحدة،
 * ثم مطابقة بند جدول السداد بالمبلغ (±سماحية) وتاريخ الاستحقاق.
 * لا تعتمد أي شيء آلياً — تُرجع اقتراحاً بنسبة ثقة وسبب وحالة لمراجعة المستخدم.
 */
class PaymentMatchingService
{
    /** سماحية فرق المبلغ (نظير ±0.01 في كشف تكرار المشتريات). */
    protected float $amountTolerance = 0.01;

    /** نافذة تقريب تاريخ الاستحقاق (أيام) لاختيار البند الأقرب. */
    protected int $dueWindowDays = 45;

    /**
     * @param  array  $payment  contract_no, tenant_name, tenant_id_no, unit_no, amount, due_date
     * @return array{
     *   shop_id: ?int, shop_rent_id: ?int, rentpay_id: ?int,
     *   matched: bool, confidence: float, reason: string, status: string
     * }
     */
    public function match(array $payment): array
    {
        $contractNo = trim((string) ($payment['contract_no'] ?? ''));
        $tenantId   = trim((string) ($payment['tenant_id_no'] ?? ''));
        $tenantName = trim((string) ($payment['tenant_name'] ?? ''));
        $unitNo     = trim((string) ($payment['unit_no'] ?? ''));
        $amount     = is_numeric($payment['amount'] ?? null) ? (float) $payment['amount'] : null;
        $dueDate    = DateNormalizer::toYmd($payment['due_date'] ?? null);

        $contract = null;
        $confidence = 0.0;
        $reasons = [];

        // 1) رقم العقد — أقوى مُعرّف (ثقة كاملة)
        if ($contractNo !== '') {
            $contract = DB::table('shop_rent')
                ->where('contract_no', $contractNo)->orWhere('rent_no', $contractNo)
                ->first();
            if ($contract) {
                $confidence = 1.0;
                $reasons[] = "تطابق رقم العقد «{$contractNo}»";
            }
        }

        // 2) هوية المستأجر (مُعرّف قوي)
        if (! $contract && $tenantId !== '') {
            $contract = DB::table('shop_rent')->where('tenant_id_no', $tenantId)->first();
            if ($contract) {
                $confidence = 0.95;
                $reasons[] = "تطابق هوية المستأجر «{$tenantId}»";
            }
        }

        // 3) مطابقة ضبابية لاسم المستأجر
        if (! $contract && $tenantName !== '') {
            [$contract, $nameScore] = $this->fuzzyTenant($tenantName);
            if ($contract && $nameScore >= 0.85) {
                $confidence = $nameScore;
                $reasons[] = 'تطابق تقريبي لاسم المستأجر (' . round($nameScore * 100) . '%)';
            } elseif ($contract && $nameScore >= 0.6) {
                // اقتراح ضعيف — يُعرض لكن يُصنَّف يتيماً حتى يؤكّده المستخدم
                return $this->orphan(
                    'اسم مستأجر قريب لكن غير مؤكَّد (' . round($nameScore * 100) . '%) — يحتاج تأكيداً',
                    ['shop_id' => (int) $contract->shop_id, 'shop_rent_id' => (int) $contract->shop_rent_id, 'confidence' => round($nameScore, 4)]
                );
            } else {
                $contract = null;
            }
        }

        // لا عقد مطابق → دفعة يتيمة
        if (! $contract) {
            return $this->orphan('لا يوجد عقد مطابق (رقم العقد/هوية/اسم المستأجر)');
        }

        // تعزيز الثقة إذا تطابق رقم الوحدة أيضاً
        if ($unitNo !== '' && isset($contract->unit_no) && $this->norm($contract->unit_no) === $this->norm($unitNo)) {
            $reasons[] = "تطابق رقم الوحدة «{$unitNo}»";
            $confidence = min(1.0, $confidence + 0.02);
        }

        $shopId = (int) $contract->shop_id;
        $shopRentId = (int) $contract->shop_rent_id;

        // اختيار بند جدول السداد الأقرب لهذا العقد (بالمبلغ ثم قرب تاريخ الاستحقاق)
        $rentpay = $this->matchInstallment($shopRentId, $amount, $dueDate);

        if (! $rentpay) {
            // العقد معروف لكن لا بند سداد مطابق — يُربط بالعقد ويُترك البند يدوياً
            return [
                'shop_id'      => $shopId,
                'shop_rent_id' => $shopRentId,
                'rentpay_id'   => null,
                'matched'      => false,
                'confidence'   => round($confidence, 4),
                'reason'       => implode(' — ', $reasons) . '؛ لا يوجد بند سداد مطابق للمبلغ/التاريخ',
                'status'       => RentPaymentMatchItem::MATCH_ORPHAN,
            ];
        }

        // تحديد الحالة مقابل البند المطابق
        $status = $this->classifyAmount($rentpay, $amount, $reasons);

        return [
            'shop_id'      => $shopId,
            'shop_rent_id' => $shopRentId,
            'rentpay_id'   => (int) $rentpay->rentpay_id,
            'matched'      => $status === RentPaymentMatchItem::MATCH_MATCHED,
            'confidence'   => round($confidence, 4),
            'reason'       => implode(' — ', $reasons),
            'status'       => $status,
        ];
    }

    /** يبني نتيجة «دفعة يتيمة». */
    protected function orphan(string $reason, array $extra = []): array
    {
        return array_merge([
            'shop_id'      => null,
            'shop_rent_id' => null,
            'rentpay_id'   => null,
            'matched'      => false,
            'confidence'   => 0.0,
            'reason'       => $reason,
            'status'       => RentPaymentMatchItem::MATCH_ORPHAN,
        ], $extra);
    }

    /** أقرب عقد باسم المستأجر عبر similar_text على الاسم المُطبّع. */
    protected function fuzzyTenant(string $name): array
    {
        $norm = $this->norm($name);
        $best = null;
        $bestScore = 0.0;

        $rows = DB::table('shop_rent')
            ->whereNotNull('tenant')->where('tenant', '!=', '')
            ->get(['shop_rent_id', 'shop_id', 'tenant', 'unit_no', 'contract_no', 'rent_no']);

        foreach ($rows as $row) {
            similar_text($norm, $this->norm($row->tenant), $pct);
            $score = $pct / 100;
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $row;
            }
        }

        return [$best, $bestScore];
    }

    /**
     * يختار بند جدول السداد الأنسب: أولوية للمبلغ المطابق (±سماحية)، ثم أقرب تاريخ استحقاق.
     * يبحث ضمن دفعات العقد (shop_rent_id)؛ إن غاب الربط يرجع للمحل عبر shop_id ما أمكن.
     */
    protected function matchInstallment(int $shopRentId, ?float $amount, ?string $dueDate): ?ShopRentpay
    {
        $query = ShopRentpay::where('shop_rent_id', $shopRentId);
        if ($query->clone()->count() === 0) {
            return null;
        }

        $candidates = $query->get();

        // 1) مبلغ مطابق ضمن السماحية + أقرب تاريخ استحقاق
        if ($amount !== null) {
            $amountMatches = $candidates->filter(
                fn ($p) => abs((float) $p->rentpay_price - $amount) <= $this->amountTolerance
            );
            if ($amountMatches->isNotEmpty()) {
                return $this->closestByDate($amountMatches, $dueDate) ?? $amountMatches->first();
            }
        }

        // 2) وإلا أقرب بند غير مسدَّد بالتاريخ ضمن النافذة
        $unpaid = $candidates->filter(fn ($p) => $p->status !== ShopRentpay::STATUS_PAID);
        $pool = $unpaid->isNotEmpty() ? $unpaid : $candidates;

        return $this->closestByDate($pool, $dueDate, $this->dueWindowDays) ?? $pool->first();
    }

    /** أقرب دفعة لتاريخ الاستحقاق (اختياري: ضمن نافذة أيام). */
    protected function closestByDate($payments, ?string $dueDate, ?int $withinDays = null): ?ShopRentpay
    {
        if (! $dueDate) {
            return null;
        }
        $target = Carbon::parse($dueDate);
        $best = null;
        $bestDiff = null;
        foreach ($payments as $p) {
            if (! $p->rentpay_dt) {
                continue;
            }
            $diff = abs(Carbon::parse($p->rentpay_dt)->diffInDays($target));
            if ($withinDays !== null && $diff > $withinDays) {
                continue;
            }
            if ($bestDiff === null || $diff < $bestDiff) {
                $bestDiff = $diff;
                $best = $p;
            }
        }

        return $best;
    }

    /** يصنّف حالة المبلغ مقابل البند: مطابق/ناقص/زائد/مكرّر. */
    protected function classifyAmount(ShopRentpay $rentpay, ?float $amount, array &$reasons): string
    {
        $due = (float) $rentpay->rentpay_price;

        // بند مسدَّد بالكامل + مبلغ مطابق → دفعة مكرّرة
        if ($rentpay->status === ShopRentpay::STATUS_PAID
            && $amount !== null && abs($amount - $due) <= $this->amountTolerance) {
            $reasons[] = 'البند مسدَّد مسبقاً بالكامل';
            return RentPaymentMatchItem::MATCH_DUPLICATE;
        }

        if ($amount === null) {
            $reasons[] = 'لا يوجد مبلغ للمقارنة';
            return RentPaymentMatchItem::MATCH_ORPHAN;
        }

        if (abs($amount - $due) <= $this->amountTolerance) {
            $reasons[] = 'المبلغ مطابق للمستحق';
            return RentPaymentMatchItem::MATCH_MATCHED;
        }
        if ($amount < $due) {
            $reasons[] = 'المبلغ أقل من المستحق (' . round($due - $amount, 2) . ' ناقص)';
            return RentPaymentMatchItem::MATCH_UNDERPAID;
        }

        $reasons[] = 'المبلغ أكبر من المستحق (' . round($amount - $due, 2) . ' زائد)';
        return RentPaymentMatchItem::MATCH_OVERPAID;
    }

    /** تطبيع نصّي (trim + توحيد المسافات + حروف صغيرة) — نفس مبدأ Supplier::normalizeName. */
    protected function norm(?string $v): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', (string) $v)));
    }
}
