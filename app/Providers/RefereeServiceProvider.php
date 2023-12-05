<?php

namespace App\Providers;

use App\Services\RefereeService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(\App\Contracts\RefereeContract::class, function ($app) {
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
        return [\App\Contracts\RefereeContract::class];
    }
}
