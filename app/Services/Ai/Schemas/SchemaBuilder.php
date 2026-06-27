<?php

namespace App\Services\Ai\Schemas;

/**
 * يبني غلاف JSON Schema بنمط strict متوافق مع OpenAI Structured Outputs:
 * { data:{...الحقول}, field_confidence:{...نفس المفاتيح: number}, overall_confidence:number }
 * (strict يتطلب additionalProperties:false وأن تكون كل الخصائص في required)
 */
class SchemaBuilder
{
    /**
     * @param array $fields              ['field' => ['string','null'], ...]
     * @param array $extraDataProps      خصائص إضافية كاملة التعريف ضمن data (مثل مصفوفات)
     * @param array $extraConfidenceKeys مفاتيح إضافية في field_confidence
     */
    public static function build(array $fields, array $extraDataProps = [], array $extraConfidenceKeys = []): array
    {
        $dataProps = [];
        foreach ($fields as $name => $type) {
            $dataProps[$name] = ['type' => $type];
        }
        foreach ($extraDataProps as $name => $def) {
            $dataProps[$name] = $def;
        }
        $dataKeys = array_keys($dataProps);

        $confKeys = array_merge(array_keys($fields), $extraConfidenceKeys);
        $confProps = [];
        foreach ($confKeys as $name) {
            $confProps[$name] = ['type' => 'number'];
        }

        return [
            'type'                 => 'object',
            'additionalProperties' => false,
            'required'             => ['data', 'field_confidence', 'overall_confidence'],
            'properties'           => [
                'data' => [
                    'type'                 => 'object',
                    'additionalProperties' => false,
                    'required'             => $dataKeys,
                    'properties'           => $dataProps,
                ],
                'field_confidence' => [
                    'type'                 => 'object',
                    'additionalProperties' => false,
                    'required'             => $confKeys,
                    'properties'           => $confProps,
                ],
                'overall_confidence' => ['type' => 'number'],
            ],
        ];
    }
}
