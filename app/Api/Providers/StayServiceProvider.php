<?php

namespace App\Api\Providers;

use App\Api\Repositories\StayRepository;
use App\Api\Services\PageService;
use App\Api\Services\StayService;
use Illuminate\Support\ServiceProvider;

class StayServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Api\Contracts\StayContract::class, function ($app) {
            return new StayService(new StayRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Api\Contracts\StayContract::class];
    }
}
