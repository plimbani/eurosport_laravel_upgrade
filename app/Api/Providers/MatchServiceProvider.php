<?php

namespace App\Api\Providers;

use App\Api\Services\MatchService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(\App\Api\Contracts\MatchContract::class, function ($app) {
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
        return [\App\Api\Contracts\MatchContract::class];
    }
}
