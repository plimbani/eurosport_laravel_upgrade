<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MatchService;

class MatchServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Contracts\MatchContract::class, function ($app) {
            return new MatchService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Contracts\MatchContract::class];
    }
}
