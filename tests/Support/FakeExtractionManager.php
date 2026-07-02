<?php

namespace Tests\Support;

use App\Services\Ai\Contracts\ExtractionEngine;
use App\Services\Ai\ExtractionManager;

/**
 * مدير استخراج وهمي: يُرجِع دائماً المحرك الوهمي المُمرَّر — لتثبيته في الحاوية
 * أثناء الاختبارات بدل OpenAiEngine الحقيقي.
 */
class FakeExtractionManager extends ExtractionManager
{
    public function __construct(protected ExtractionEngine $fake) {}

    public function engine(?string $name = null): ExtractionEngine
    {
        return $this->fake;
    }
}
