<?php

namespace App\Services\Ai;

use App\Services\Ai\Contracts\ExtractionEngine;
use App\Services\Ai\Engines\GeminiEngine;
use App\Services\Ai\Engines\OpenAiEngine;
use InvalidArgumentException;

/**
 * يحلّ محرك الاستخراج المناسب وفق config('ai.engine').
 * إضافة محرك جديد مستقبلاً = حالة جديدة هنا فقط.
 */
class ExtractionManager
{
    public function engine(?string $name = null): ExtractionEngine
    {
        $name = $name ?? config('ai.engine', 'openai');

        return match ($name) {
            'openai' => new OpenAiEngine(),
            'gemini' => new GeminiEngine(),
            default  => throw new InvalidArgumentException("محرك استخراج غير مدعوم: {$name}"),
        };
    }
}
