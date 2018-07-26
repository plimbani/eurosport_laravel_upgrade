<?php

namespace Laraspace\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Services\ApiService;

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

        $this->app->bind('Laraspace\Contracts\ApiContract', function ($app) {
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
        return ['Laraspace\Contracts\ApiContract'];
    }
}
