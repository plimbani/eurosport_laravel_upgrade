<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AgeGroupService;
use App\Repositories\AgeGroupRepository;

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
        $this->app->bind('App\Contracts\AgeGroupContract', function ($app) {
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
