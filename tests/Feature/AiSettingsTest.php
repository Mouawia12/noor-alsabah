<?php

use App\Models\Setting;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Services\Ai\Engines\GeminiEngine;
use App\Services\Ai\ExtractionManager;

/**
 * صفحة إعدادات مفاتيح الـ API: تخزين في قاعدة البيانات يتجاوز .env، اختيار المزوّد
 * (OpenAI/Gemini)، سرّية المفاتيح، وحماية الوصول (مدير النظام فقط).
 */

it('stores settings in the database and overrides ai config', function () {
    Setting::put('ai_engine', 'gemini');
    Setting::put('gemini_api_key', 'AIza-KEY');
    Setting::put('gemini_model', 'gemini-2.0-flash');

    // إعادة تطبيق التجاوز كما عند الإقلاع
    (new AppServiceProvider(app()))->boot();

    expect(config('ai.engine'))->toBe('gemini');
    expect(config('ai.gemini.api_key'))->toBe('AIza-KEY');
    expect(config('ai.gemini.model'))->toBe('gemini-2.0-flash');
});

it('resolves the Gemini engine when selected with a key', function () {
    config(['ai.engine' => 'gemini', 'ai.gemini.api_key' => 'AIza-KEY']);
    expect(app(ExtractionManager::class)->engine())->toBeInstanceOf(GeminiEngine::class);
});

it('sanitizes a strict JSON schema into a Gemini-compatible responseSchema', function () {
    $engine = (new ReflectionClass(GeminiEngine::class))->newInstanceWithoutConstructor();
    $m = (new ReflectionClass(GeminiEngine::class))->getMethod('toGeminiSchema');
    $m->setAccessible(true);

    $schema = [
        'type' => 'object',
        'additionalProperties' => false,
        'strict' => true,
        'required' => ['no'],
        'properties' => [
            'no'   => ['type' => ['string', 'null']],
            'list' => ['type' => 'array', 'items' => ['type' => 'object', 'additionalProperties' => false, 'properties' => ['x' => ['type' => 'number']]]],
        ],
    ];
    $g = $m->invoke($engine, $schema);
    $json = json_encode($g);

    expect($json)->not->toContain('additionalProperties');
    expect($json)->not->toContain('strict');
    expect($g['properties']['no']['type'])->toBe('string');   // ["string","null"] → string + nullable
    expect($g['properties']['no']['nullable'])->toBeTrue();
    expect($json)->not->toContain('"additionalProperties"');  // متداخل أيضاً
});

/** مدير نظام للاختبار: العمود isAdmin غير موجود في جدول اختبار SQLite، فنضبط السمة في الذاكرة (guardAdmin يقرأها). */
function adminUser(): User
{
    $u = User::factory()->create();
    $u->isAdmin = 1;

    return $u;
}

it('secret key is kept when the field is left blank on update', function () {
    Setting::put('openai_api_key', 'sk-EXISTING');

    // حفظ بلا مفتاح جديد (فارغ) → يبقى المفتاح الحالي
    $this->actingAs(adminUser())->post(route('dashboard.settings.update'), [
        'ai_engine' => 'openai', 'openai_model' => 'gpt-5-mini', 'openai_api_key' => '',
    ])->assertRedirect();

    expect(Setting::get('openai_api_key'))->toBe('sk-EXISTING');
    expect(Setting::get('openai_model'))->toBe('gpt-5-mini');
});

it('updates the secret key only when a value is provided', function () {
    $this->actingAs(adminUser())->post(route('dashboard.settings.update'), [
        'ai_engine' => 'gemini', 'gemini_api_key' => 'AIza-NEW',
    ])->assertRedirect();

    expect(Setting::get('gemini_api_key'))->toBe('AIza-NEW');
    expect(Setting::get('ai_engine'))->toBe('gemini');
});

it('forbids non-admin users from the settings page and update', function () {
    $user = User::factory()->create(); // بلا isAdmin/emp_job → ليس مديراً
    $this->actingAs($user)->get(route('dashboard.settings.index'))->assertForbidden();
    $this->actingAs($user)->post(route('dashboard.settings.update'), ['ai_engine' => 'openai'])->assertForbidden();
});
