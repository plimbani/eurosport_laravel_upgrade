<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Services\RefereeService;

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
        $this->app->bind('Laraspace\Api\Contracts\RefereeContract', function ($app) {
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
        return ['Laraspace\Api\Contracts\RefereeContract'];
    }
}
