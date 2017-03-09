<?php

namespace Laraspace\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Services\AgeGroupService;
use Laraspace\Repositories\AgeGroupRepository;

class AgeGroupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the Application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Laraspace\Contracts\AgeGroupContract', function ($app) {
            return new AgeGroupService(new AgeGroupRepository());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Contracts\AgeContract'];
    }
}
