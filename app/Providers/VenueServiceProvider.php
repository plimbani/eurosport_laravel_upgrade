<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\VenueRepository;
use App\Services\VenueTempService as VenueService;

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
        $this->app->bind(\App\Contracts\VenueContract::class, function ($app) {
            return new VenueService(new VenueRepository());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Contracts\VenueContract::class];
    }
}
