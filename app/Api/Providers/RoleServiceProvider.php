<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Services\RoleService;

class RoleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the Laraspacelication services.
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
        $this->app->bind('Laraspace\Api\Contracts\RoleContract', function ($app) {
            return new RoleService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Api\Contracts\RoleContract'];
    }
}
