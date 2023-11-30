<?php

namespace App\Api\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\Repositories\HomeRepository;
use App\Api\Services\HomeService;
use App\Api\Services\PageService;

class HomeServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Api\Contracts\HomeContract', function ($app) {
            return new HomeService(new HomeRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Api\Contracts\HomeContract'];
    }
}
