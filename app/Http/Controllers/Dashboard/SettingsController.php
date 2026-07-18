<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * إعدادات مفاتيح الـ API والتكامل (OpenAI / Google Gemini) — تُخزَّن في قاعدة البيانات
 * وتتجاوز قيم .env. مخصّصة لمدير النظام فقط.
 */
class SettingsController extends Controller
{
    /** الإعدادات النصية العادية (تُحفظ أو تُفرَّغ لتعود لقيمة .env). */
    protected array $plainKeys = [
        'ai_engine',
        'openai_model', 'openai_model_heavy',
        'gemini_model', 'gemini_model_heavy', 'gemini_timeout',
    ];

    /** الإعدادات السرية (تُحدَّث فقط عند إدخال قيمة؛ فارغ = إبقاء الحالية). */
    protected array $secretKeys = ['openai_api_key', 'gemini_api_key'];

    protected function guardAdmin(): void
    {
        $u = auth()->user();
        $isAdmin = (bool) optional($u)->isAdmin || (int) optional($u)->emp_job === 1;
        abort_unless($isAdmin, 403, 'هذه الصفحة مخصّصة لمدير النظام فقط.');
    }

    public function index()
    {
        $this->guardAdmin();
        $s = Setting::map();

        return view('dashboard.settings.index', [
            'page_title'   => 'إعدادات مفاتيح الـ API والتكامل',
            's'            => $s,
            'engine'       => $s['ai_engine'] ?? config('ai.engine', 'openai'),
            // هل يوجد مفتاح محفوظ (في DB أو .env) — لعرض شارة «محفوظ ✓»
            'hasOpenaiKey' => ! empty($s['openai_api_key']) || ! empty(config('ai.openai.api_key')),
            'hasGeminiKey' => ! empty($s['gemini_api_key']) || ! empty(config('ai.gemini.api_key')),
        ]);
    }

    public function update(Request $request)
    {
        $this->guardAdmin();

        $data = $request->validate([
            'ai_engine'          => ['nullable', 'in:openai,gemini'],
            'openai_model'       => ['nullable', 'string', 'max:80'],
            'openai_model_heavy' => ['nullable', 'string', 'max:80'],
            'openai_api_key'     => ['nullable', 'string', 'max:300'],
            'gemini_model'       => ['nullable', 'string', 'max:80'],
            'gemini_model_heavy' => ['nullable', 'string', 'max:80'],
            'gemini_timeout'     => ['nullable', 'integer', 'min:10', 'max:600'],
            'gemini_api_key'     => ['nullable', 'string', 'max:300'],
        ], [
            'ai_engine.in'         => 'المزوّد يجب أن يكون OpenAI أو Gemini.',
            'gemini_timeout.integer' => 'المهلة يجب أن تكون رقماً بالثواني.',
        ]);

        foreach ($this->plainKeys as $k) {
            Setting::put($k, $data[$k] ?? '');
        }
        foreach ($this->secretKeys as $k) {
            if (! empty($data[$k])) {
                Setting::put($k, trim($data[$k]));
            }
        }

        return back()->with('alert.success', 'تم حفظ إعدادات الذكاء الاصطناعي بنجاح.');
    }

    /** فحص سريع: هل المزوّد النشط قابل للتهيئة (المفتاح مضبوط)؟ */
    public function test(Request $request)
    {
        $this->guardAdmin();
        try {
            $engine = app(\App\Services\Ai\ExtractionManager::class)->engine();

            return response()->json([
                'ok'      => true,
                'message' => 'المزوّد «' . class_basename($engine) . '» مهيّأ والمفتاح مضبوط.',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
