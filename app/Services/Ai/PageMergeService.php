<?php

namespace App\Services\Ai;

use Illuminate\Support\Collection;

/**
 * دمج عناصر الصفحات إلى مستندات: كل صفحة بلا مُعرّف (رقم عقد/فاتورة) وبلا مبلغ
 * تُعتبر تكملة (توقيع/ملحق) فتُدمج مع المستند السابق ولا تظهر كعنصر فارغ.
 * حتمي وبلا استدعاءات AI ⇒ قوي وقابل للتوسّع لآلاف الصفحات.
 */
class PageMergeService
{
    /**
     * @param Collection $items عناصر الدفعة (نموذج استيراد) مرتّبة تصاعدياً حسب الصفحة
     * @param string[]   $idFields     حقول المُعرّف (وجود أيّها = بداية مستند)
     * @param string[]   $amountFields حقول المبلغ (وجود أيّها = بداية مستند)
     * @return int عدد المستندات الناتجة (العناصر الظاهرة للمراجعة)
     */
    public function merge(Collection $items, array $idFields, array $amountFields, string $mergedStatus): int
    {
        $parent = null;
        $docs = 0;

        foreach ($items as $it) {
            $data = (array) ($it->extracted_json['data'] ?? []);
            $hasId = $this->anyFilled($data, $idFields);
            $hasAmount = $this->anyFilled($data, $amountFields);

            // مُعرّف هذه الصفحة والأب (نموذج REGA يكرّر رقم العقد في ترويسة كل صفحة)
            $curId = $this->idValue($data, $idFields);
            $parentId = $parent ? $this->idValue((array) ($parent->extracted_json['data'] ?? []), $idFields) : null;
            // نفس المُعرّف للأب ⇒ تكملة لنفس المستند (لا يبدأ عقداً جديداً)
            $sameDoc = $parent !== null && $curId !== null && $parentId !== null && $curId === $parentId;

            // أول عنصر دائماً بداية؛ وإلا يبدأ مستنداً جديداً بمُعرّف/مبلغ ما لم يكن نفس مُعرّف الأب
            $isStart = ($parent === null) || (! $sameDoc && ($hasId || $hasAmount));

            if ($isStart) {
                $parent = $it;
                $docs++;
                continue;
            }

            // تكملة → ادمجها مع المستند السابق
            $this->mergeInto($parent, $it, $mergedStatus);
        }

        return $docs;
    }

    /** دمج صفحة تكملة في مستندها الأب. */
    protected function mergeInto($parent, $child, string $mergedStatus): void
    {
        // 1) ضمّ صورة الصفحة لصور الأب (لعرضها في المراجعة)
        $paths = array_values(array_filter(array_merge(
            explode(',', (string) $parent->source_file_path),
            explode(',', (string) $child->source_file_path)
        )));
        $parent->source_file_path = implode(',', array_unique($paths));
        $parent->page_to = max((int) $parent->page_to, (int) $child->page_to);

        // 2) تعبئة الحقول الفارغة في الأب من بيانات التكملة
        $pj = $parent->extracted_json ?: [];
        $pdata = (array) ($pj['data'] ?? []);
        $cdata = (array) ($child->extracted_json['data'] ?? []);
        foreach ($cdata as $k => $v) {
            if (($pdata[$k] ?? null) === null || $pdata[$k] === '' || $pdata[$k] === []) {
                if ($v !== null && $v !== '' && $v !== []) {
                    $pdata[$k] = $v;
                }
            }
        }
        $pj['data'] = $pdata;
        $parent->extracted_json = $pj;
        $parent->save();

        // 3) إخفاء صفحة التكملة من المراجعة
        $child->update(['status' => $mergedStatus, 'error_reason' => null]);
    }

    /** القيمة المُطبّعة لأوّل حقل مُعرّف مُعبّأ (لمقارنة تطابق المستند بين الصفحات). */
    protected function idValue(array $data, array $fields): ?string
    {
        foreach ($fields as $f) {
            $v = $data[$f] ?? null;
            if ($v !== null && $v !== '' && $v !== [] && trim((string) $v) !== '') {
                // تطبيع: إزالة الفراغات وتوحيد الحالة حتى لا تُفرّق فروقٌ شكلية بين الصفحات
                return preg_replace('/\s+/u', '', mb_strtolower(trim((string) $v)));
            }
        }
        return null;
    }

    protected function anyFilled(array $data, array $fields): bool
    {
        foreach ($fields as $f) {
            $v = $data[$f] ?? null;
            if ($v !== null && $v !== '' && $v !== [] && trim((string) $v) !== '') {
                return true;
            }
        }
        return false;
    }
}
