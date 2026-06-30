<?php

namespace App\Services\Ai\Engines;

use App\Services\Ai\Contracts\ExtractionEngine;
use App\Services\Ai\DTO\ExtractionResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

/**
 * محوّل OpenAI (رؤية + Structured Outputs) عبر Guzzle.
 * يقرأ الإعدادات من config('ai.openai'). لا يحتوي أي مفتاح صريح.
 */
class OpenAiEngine implements ExtractionEngine
{
    protected array $cfg;
    protected Client $http;

    public function __construct(?array $cfg = null)
    {
        $this->cfg = $cfg ?? (array) config('ai.openai');

        if (empty($this->cfg['api_key'])) {
            throw new RuntimeException('OPENAI_API_KEY غير مضبوط في .env');
        }

        $this->http = new Client([
            'base_uri' => rtrim($this->cfg['base_url'] ?? 'https://api.openai.com/v1', '/') . '/',
            'timeout'  => (int) ($this->cfg['timeout'] ?? 120),
        ]);
    }

    public function extract(array $imagePaths, array $jsonSchema, string $instructions, array $options = []): ExtractionResult
    {
        $model = ! empty($options['heavy'])
            ? ($this->cfg['model_heavy'] ?? 'gpt-4o')
            : ($this->cfg['model'] ?? 'gpt-4o-mini');

        $content = [];
        foreach ($imagePaths as $path) {
            $content[] = [
                'type'      => 'image_url',
                'image_url' => ['url' => $this->toDataUri($path)],
            ];
        }
        $content[] = ['type' => 'text', 'text' => 'استخرج البيانات من المستند أعلاه وفق المخطط المطلوب.'];

        $body = [
            'model'    => $model,
            'temperature' => 0,
            'messages' => [
                ['role' => 'system', 'content' => $instructions],
                ['role' => 'user', 'content' => $content],
            ],
            'response_format' => [
                'type'        => 'json_schema',
                'json_schema' => [
                    'name'   => 'extraction',
                    'strict' => true,
                    'schema' => $jsonSchema,
                ],
            ],
        ];

        $json = $this->request($body);
        $parsed = $this->parseContent($json);

        // فصل الثقة عن البيانات إن وُجدت ضمن المخرجات
        $overall = $parsed['overall_confidence'] ?? null;
        $fieldConf = $parsed['field_confidence'] ?? [];
        unset($parsed['overall_confidence'], $parsed['field_confidence']);

        return new ExtractionResult(
            data: $parsed['data'] ?? $parsed,
            confidence: $overall !== null ? (float) $overall : null,
            fieldConfidence: is_array($fieldConf) ? $fieldConf : [],
            model: $model,
            calls: 1,
            raw: json_encode($json['choices'][0]['message']['content'] ?? null, JSON_UNESCAPED_UNICODE),
        );
    }

    public function classifyBoundary(string $imagePath, array $options = []): array
    {
        $schema = [
            'type' => 'object',
            'additionalProperties' => false,
            'required' => ['is_new_document', 'confidence'],
            'properties' => [
                'is_new_document' => ['type' => 'boolean'],
                'confidence'      => ['type' => 'number'],
            ],
        ];

        $body = [
            'model'       => $this->cfg['model'] ?? 'gpt-4o-mini',
            'temperature' => 0,
            'messages'    => [
                ['role' => 'system', 'content' => 'هل هذه الصفحة بداية مستند/فاتورة/عقد جديد (وليست استكمالاً للصفحة السابقة)؟ أعد القرار ونسبة الثقة.'],
                ['role' => 'user', 'content' => [
                    ['type' => 'image_url', 'image_url' => ['url' => $this->toDataUri($imagePath)]],
                ]],
            ],
            'response_format' => [
                'type'        => 'json_schema',
                'json_schema' => ['name' => 'boundary', 'strict' => true, 'schema' => $schema],
            ],
        ];

        $parsed = $this->parseContent($this->request($body));

        return [
            'is_new_document' => (bool) ($parsed['is_new_document'] ?? true),
            'confidence'      => (float) ($parsed['confidence'] ?? 0),
        ];
    }

    /** استدعاء API مع إعادة محاولة بـ backoff على 429/5xx. */
    protected function request(array $body): array
    {
        $maxRetries = (int) ($this->cfg['max_retries'] ?? 3);
        $attempt = 0;

        while (true) {
            $attempt++;
            try {
                $res = $this->http->post('chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->cfg['api_key'],
                        'Content-Type'  => 'application/json',
                    ],
                    'json'        => $body,
                    'http_errors' => true,
                ]);

                return json_decode((string) $res->getBody(), true) ?: [];
            } catch (\Throwable $e) {
                // الحالة الحقيقية من رد Guzzle عند توفّره (getCode غالباً 0 لأخطاء الاتصال).
                $status = 0;
                if ($e instanceof RequestException && $e->hasResponse()) {
                    $status = $e->getResponse()->getStatusCode();
                }
                $retriable = in_array($status, [429, 500, 502, 503, 504], true) || $status === 0;

                if (! $retriable || $attempt >= $maxRetries) {
                    throw new RuntimeException('فشل استدعاء OpenAI: ' . $e->getMessage(), $status, $e);
                }
                // backoff تصاعدي: 1s, 2s, 4s ...
                usleep((int) (pow(2, $attempt - 1) * 1_000_000));
            }
        }
    }

    /** استخراج وفك JSON من رد الـ chat completion. */
    protected function parseContent(array $json): array
    {
        $content = $json['choices'][0]['message']['content'] ?? null;
        if ($content === null) {
            throw new RuntimeException('رد OpenAI فارغ أو غير متوقع');
        }
        $data = json_decode($content, true);
        if (! is_array($data)) {
            throw new RuntimeException('تعذّر فك JSON من رد OpenAI');
        }
        return $data;
    }

    /** تحويل صورة على القرص إلى data URI بترميز base64. */
    protected function toDataUri(string $path): string
    {
        if (! is_file($path)) {
            throw new RuntimeException("ملف الصورة غير موجود: {$path}");
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png'        => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp'       => 'image/webp',
            'gif'        => 'image/gif',
            default      => 'image/png',
        };

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}
