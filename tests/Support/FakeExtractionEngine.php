<?php

namespace Tests\Support;

use App\Services\Ai\Contracts\ExtractionEngine;
use App\Services\Ai\DTO\ExtractionResult;

/**
 * محرك استخراج وهمي للاختبارات — يحاكي OpenAI دون أي استدعاء شبكة أو تكلفة.
 * يُرجِع بيانات معدّة مسبقاً، ويحاكي التصعيد للنموذج الأقوى (heavy) بنتيجة مستقلة.
 */
class FakeExtractionEngine implements ExtractionEngine
{
    /** عدّاد الاستدعاءات (للتحقق من التصعيد). */
    public int $calls = 0;

    public function __construct(
        public array $data = [],
        public ?float $confidence = 0.95,
        public array $fieldConfidence = [],
        public ?ExtractionResult $heavy = null,
        public ?\Throwable $throw = null,
    ) {}

    public function extract(array $imagePaths, array $jsonSchema, string $instructions, array $options = []): ExtractionResult
    {
        $this->calls++;

        if ($this->throw) {
            throw $this->throw;
        }

        if (! empty($options['heavy']) && $this->heavy) {
            return $this->heavy;
        }

        return new ExtractionResult(
            data: $this->data,
            confidence: $this->confidence,
            fieldConfidence: $this->fieldConfidence,
            model: ! empty($options['heavy']) ? 'fake-heavy' : 'fake-model',
        );
    }

    public function classifyBoundary(string $imagePath, array $options = []): array
    {
        return ['is_new_document' => true, 'confidence' => 1.0];
    }
}
