<?php

namespace App\Api\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\Repositories\AgeGroupRepository;
use App\Api\Services\AgeGroupService;

class AgeGroupServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Api\Contracts\AgeGroupContract::class, function ($app) {
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
        return ['App\Api\Contracts\AgeContract'];
    }
}
