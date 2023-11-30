<?php

namespace App\Api\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\Services\RefereeService;

class RefereeServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Api\Contracts\RefereeContract::class, function ($app) {
            return new RefereeService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Api\Contracts\RefereeContract::class];
    }
}
