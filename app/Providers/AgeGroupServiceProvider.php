<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\AgeGroupRepository;
use App\Services\AgeGroupService;

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
        $this->app->bind(\App\Contracts\AgeGroupContract::class, function ($app) {
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
        return ['App\Contracts\AgeContract'];
    }
}
