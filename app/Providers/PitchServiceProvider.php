<?php

namespace App\Providers;

use App\Services\PitchService;
use Illuminate\Support\ServiceProvider;

class PitchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Contracts\PitchContract::class, function ($app) {
            return new PitchService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Contracts\PitchContract::class];
    }
}
