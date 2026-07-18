<?php

namespace App\Services\Ai\Engines;

use App\Services\Ai\Contracts\ExtractionEngine;
use App\Services\Ai\DTO\ExtractionResult;
use GuzzleHttp\Client;
use RuntimeException;

/**
 * محوّل Google Gemini (رؤية + مخرجات JSON منظّمة) عبر Generative Language API.
 * يقرأ الإعدادات من config('ai.gemini'). واجهة مطابقة لـ OpenAiEngine (قابل للتبديل).
 */
class GeminiEngine implements ExtractionEngine
{
    protected array $cfg;
    protected Client $http;

    public function __construct(?array $cfg = null)
    {
        $this->cfg = $cfg ?? (array) config('ai.gemini');

        if (empty($this->cfg['api_key'])) {
            throw new RuntimeException('مفتاح Gemini API غير مضبوط (GEMINI_API_KEY أو من صفحة الإعدادات).');
        }

        $this->http = new Client([
            'base_uri' => rtrim($this->cfg['base_url'] ?? 'https://generativelanguage.googleapis.com/v1beta', '/') . '/',
            'timeout'  => (int) ($this->cfg['timeout'] ?? 120),
        ]);
    }

    public function extract(array $imagePaths, array $jsonSchema, string $instructions, array $options = []): ExtractionResult
    {
        $model = ! empty($options['heavy'])
            ? ($this->cfg['model_heavy'] ?? 'gemini-2.5-pro')
            : ($this->cfg['model'] ?? 'gemini-2.0-flash');

        $parts = [];
        foreach ($imagePaths as $path) {
            [$data, $mime] = $this->imageInline($path);
            $parts[] = ['inline_data' => ['mime_type' => $mime, 'data' => $data]];
        }
        $parts[] = ['text' => 'استخرج البيانات من المستند أعلاه وفق المخطط المطلوب، وأعد JSON فقط.'];

        $body = [
            'systemInstruction' => ['parts' => [['text' => $instructions]]],
            'contents'          => [['role' => 'user', 'parts' => $parts]],
            'generationConfig'  => [
                'responseMimeType' => 'application/json',
                'responseSchema'   => $this->toGeminiSchema($jsonSchema),
            ],
        ];

        $json = $this->request($model, $body);
        $parsed = $this->parseContent($json);

        $overall = $parsed['overall_confidence'] ?? null;
        $fieldConf = $parsed['field_confidence'] ?? [];
        unset($parsed['overall_confidence'], $parsed['field_confidence']);

        return new ExtractionResult(
            data: $parsed['data'] ?? $parsed,
            confidence: $overall !== null ? (float) $overall : null,
            fieldConfidence: is_array($fieldConf) ? $fieldConf : [],
            model: $model,
            calls: 1,
            raw: json_encode($json, JSON_UNESCAPED_UNICODE),
        );
    }

    public function classifyBoundary(string $imagePath, array $options = []): array
    {
        $schema = [
            'type'       => 'object',
            'required'   => ['is_new_document', 'confidence'],
            'properties' => [
                'is_new_document' => ['type' => 'boolean'],
                'confidence'      => ['type' => 'number'],
            ],
        ];
        [$data, $mime] = $this->imageInline($imagePath);

        $body = [
            'systemInstruction' => ['parts' => [['text' => 'هل هذه الصفحة بداية مستند/فاتورة/عقد جديد (وليست استكمالاً)؟ أعد القرار ونسبة الثقة كـ JSON.']]],
            'contents'          => [['role' => 'user', 'parts' => [['inline_data' => ['mime_type' => $mime, 'data' => $data]]]]],
            'generationConfig'  => ['responseMimeType' => 'application/json', 'responseSchema' => $schema],
        ];

        $parsed = $this->parseContent($this->request($this->cfg['model'] ?? 'gemini-2.0-flash', $body));

        return [
            'is_new_document' => (bool) ($parsed['is_new_document'] ?? true),
            'confidence'      => (float) ($parsed['confidence'] ?? 0),
        ];
    }

    /** استدعاء generateContent مع إعادة محاولة بـ backoff على 429/5xx. */
    protected function request(string $model, array $body): array
    {
        $maxRetries = (int) ($this->cfg['max_retries'] ?? 3);
        $url = 'models/' . $model . ':generateContent?key=' . urlencode($this->cfg['api_key']);
        $attempt = 0;

        while (true) {
            $attempt++;
            try {
                $res = $this->http->post($url, [
                    'headers'     => ['Content-Type' => 'application/json'],
                    'json'        => $body,
                    'http_errors' => true,
                ]);

                return json_decode((string) $res->getBody(), true) ?: [];
            } catch (\Throwable $e) {
                $status = method_exists($e, 'getCode') ? (int) $e->getCode() : 0;
                $retriable = in_array($status, [429, 500, 502, 503, 504], true) || $status === 0;

                if (! $retriable || $attempt >= $maxRetries) {
                    throw new RuntimeException('فشل استدعاء Gemini: ' . $e->getMessage(), $status, $e);
                }
                usleep((int) (pow(2, $attempt - 1) * 1_000_000));
            }
        }
    }

    /** فكّ JSON من رد Gemini (candidates[0].content.parts[*].text). */
    protected function parseContent(array $json): array
    {
        // حجب/رفض بسبب السياسات
        if (! empty($json['promptFeedback']['blockReason'])) {
            throw new RuntimeException('رفض Gemini المعالجة: ' . $json['promptFeedback']['blockReason']);
        }

        $cand = $json['candidates'][0] ?? null;
        if (! $cand) {
            throw new RuntimeException('رد Gemini فارغ أو غير متوقع');
        }
        if (($cand['finishReason'] ?? null) === 'MAX_TOKENS') {
            throw new RuntimeException('اقتُطعت نتيجة الاستخراج (تجاوز حد الطول)');
        }

        $text = '';
        foreach ($cand['content']['parts'] ?? [] as $p) {
            $text .= $p['text'] ?? '';
        }
        $text = trim($text);
        if ($text === '') {
            throw new RuntimeException('رد Gemini فارغ');
        }
        // إزالة أسوار الشيفرة إن وُجدت
        $text = preg_replace('/^```(?:json)?\s*|\s*```$/u', '', $text);

        $data = json_decode($text, true);
        if (! is_array($data)) {
            throw new RuntimeException('تعذّر فك JSON من رد Gemini');
        }

        return $data;
    }

    /**
     * يحوّل JSON Schema (بأسلوب OpenAI strict) إلى responseSchema المدعوم من Gemini:
     * يزيل المفاتيح غير المدعومة (additionalProperties/strict/$schema/title/default)
     * ويحوّل type=["x","null"] إلى type=x + nullable=true.
     */
    protected function toGeminiSchema($schema)
    {
        if (! is_array($schema)) {
            return $schema;
        }

        $out = [];
        foreach ($schema as $k => $v) {
            if (in_array($k, ['additionalProperties', 'strict', '$schema', 'title', 'default', 'examples'], true)) {
                continue;
            }
            if ($k === 'type' && is_array($v)) {
                $nonNull = array_values(array_filter($v, fn ($t) => $t !== 'null'));
                $out['type'] = $nonNull[0] ?? 'string';
                if (in_array('null', $v, true)) {
                    $out['nullable'] = true;
                }
                continue;
            }
            if ($k === 'properties' && is_array($v)) {
                $out['properties'] = [];
                foreach ($v as $pk => $pv) {
                    $out['properties'][$pk] = $this->toGeminiSchema($pv);
                }
                continue;
            }
            if ($k === 'items') {
                $out['items'] = $this->toGeminiSchema($v);
                continue;
            }
            $out[$k] = is_array($v) ? $this->toGeminiSchema($v) : $v;
        }

        return $out;
    }

    /** صورة على القرص → [base64, mime] (مع تصغير/ضغط اختياري للأداء). */
    protected function imageInline(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException("ملف الصورة غير موجود: {$path}");
        }

        if (config('ai.optimize_images', true)) {
            [$bytes, $mime] = \App\Services\Ai\ImageOptimizer::optimize(
                $path,
                (int) config('ai.image_max_edge', 2200),
                (int) config('ai.image_jpeg_quality', 85),
                (int) config('ai.image_min_bytes', 307200)
            );

            return [base64_encode($bytes), $mime];
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png'         => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp'        => 'image/webp',
            default       => 'image/png',
        };

        return [base64_encode(file_get_contents($path)), $mime];
    }
}
