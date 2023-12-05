<?php

namespace App\Api\Providers;

use App\Api\Services\VenueService;
use Illuminate\Support\ServiceProvider;

class VenueServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Api\Contracts\VenueContract::class, function ($app) {
            return new VenueService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Api\Contracts\VenueContract::class];
    }
}
