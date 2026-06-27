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
    }
}
