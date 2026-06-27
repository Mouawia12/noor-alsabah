<?php

namespace App\Services\Ai\Contracts;

use App\Services\Ai\DTO\ExtractionResult;

/**
 * عقد محرك الاستخراج — الطبقة معزولة بحيث يمكن تبديل المحرك (OpenAI/غيره)
 * دون لمس بقية النظام.
 */
interface ExtractionEngine
{
    /**
     * استخراج بيانات منظّمة من صور المستند.
     *
     * @param  array  $imagePaths   مسارات صور صفحات المستند (PNG/JPG) على القرص.
     * @param  array  $jsonSchema   مخطط JSON Schema للمخرجات المطلوبة.
     * @param  string $instructions تعليمات الاستخراج (system prompt).
     * @param  array  $options      خيارات: heavy(bool) لاستخدام النموذج الأقوى.
     */
    public function extract(array $imagePaths, array $jsonSchema, string $instructions, array $options = []): ExtractionResult;

    /**
     * تصنيف سريع: هل هذه الصفحة بداية مستند جديد؟ (لكشف حدود الفواتير)
     *
     * @param  string $imagePath صورة الصفحة.
     * @return array{is_new_document: bool, confidence: float}
     */
    public function classifyBoundary(string $imagePath, array $options = []): array;
}
