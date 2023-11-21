<?php

namespace Laraspace\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Repositories\VenueRepository;
use Laraspace\Services\VenueTempService as VenueService;

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
        $this->app->bind('Laraspace\Contracts\VenueContract', function ($app) {
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
        return ['Laraspace\Contracts\VenueContract'];
    }
}
