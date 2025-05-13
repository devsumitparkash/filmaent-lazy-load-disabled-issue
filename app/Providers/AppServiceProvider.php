<?php

namespace App\Providers;

use App\Services\MoneyFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerMoneyFormatter();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureModels();
    }

    /**
     * Configure the application's models
     */
    private function configureModels(): void
    {


        Model::shouldBeStrict();

        Model::preventLazyLoading(! app()->isProduction());
        // Model::preventLazyLoading(false);

        /**
         * We can either Model::unguard to Disable all mass assignable restrictions
         * Or Allow  non-fillable attributes to be silently discarded set on by uisng Model::shouldBeStrict()
         */
        Model::preventSilentlyDiscardingAttributes(false);
        // Model::unguard();
    }

    protected function registerMoneyFormatter(): void
    {
        $this->app->singleton(MoneyFormatter::class);
    }
}
