<?php

namespace App\Services\Ai\DTO;

/**
 * نتيجة استخراج موحّدة بصرف النظر عن المحرك (OpenAI أو غيره).
 */
class ExtractionResult
{
    public function __construct(
        public array $data,              // الحقول المستخرجة
        public ?float $confidence = null, // الثقة العامة (0..1)
        public array $fieldConfidence = [], // ثقة كل حقل
        public string $model = '',
        public int $calls = 1,            // عدد استدعاءات المحرك (للتكلفة)
        public ?string $raw = null,       // الرد الخام (للتدقيق)
    ) {}

    public function toArray(): array
    {
        return [
            'data'             => $this->data,
            'confidence'       => $this->confidence,
            'field_confidence' => $this->fieldConfidence,
            'model'            => $this->model,
            'calls'            => $this->calls,
        ];
    }
}
