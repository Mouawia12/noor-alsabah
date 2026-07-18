<?php

namespace App\Providers;

use App\Services\Ai\ExtractionManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // مدير محرك استخراج الذكاء الاصطناعي (معزول/قابل للتبديل)
        $this->app->singleton(ExtractionManager::class, fn () => new ExtractionManager());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ترقيم الصفحات بنمط Bootstrap (الثيم Metronic يعتمده)
        Paginator::useBootstrapFive();

        // إعدادات الذكاء الاصطناعي المخزّنة في قاعدة البيانات تتجاوز قيم .env
        $this->applyAiSettingsFromDatabase();
    }

    /**
     * يحمّل مفاتيح/نماذج الذكاء الاصطناعي المحفوظة في جدول settings إلى config('ai.*')،
     * فتتجاوز قيم .env دون لمس أي كود يقرأ الإعداد. آمن أثناء الهجرات (try/catch).
     */
    protected function applyAiSettingsFromDatabase(): void
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return;
            }
            $m = \App\Models\Setting::map();
            if (empty($m)) {
                return;
            }
            $set = function (string $cfg, string $key) use ($m) {
                if (isset($m[$key]) && $m[$key] !== '' && $m[$key] !== null) {
                    config([$cfg => $m[$key]]);
                }
            };
            // المزوّد النشط
            $set('ai.engine', 'ai_engine');
            // OpenAI
            $set('ai.openai.api_key', 'openai_api_key');
            $set('ai.openai.model', 'openai_model');
            $set('ai.openai.model_heavy', 'openai_model_heavy');
            // Gemini (Google)
            $set('ai.gemini.api_key', 'gemini_api_key');
            $set('ai.gemini.model', 'gemini_model');
            $set('ai.gemini.model_heavy', 'gemini_model_heavy');
            $set('ai.gemini.timeout', 'gemini_timeout');
        } catch (\Throwable $e) {
            // لا تُعطّل إقلاع التطبيق إن تعذّر قراءة الإعدادات
        }
    }
}
