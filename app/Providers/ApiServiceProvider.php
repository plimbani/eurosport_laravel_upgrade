<?php

namespace App\Providers;

use App\Services\ApiService;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function register()
    {
        //$app = app();
        //$app->bind('helloworld','App\Contracts\ApiContract');
        //$dispatcherObj = app('Dingo\Api\Dispatcher');

        $this->app->bind(\App\Contracts\ApiContract::class, function ($app) {
            return new ApiService();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Contracts\ApiContract::class];
    }
}
