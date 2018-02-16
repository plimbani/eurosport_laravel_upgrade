<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Services\WebsiteVenueService;
use Laraspace\Api\Repositories\WebsiteVenueRepository;

class WebsiteVenueServiceProvider extends ServiceProvider
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
        $this->app->bind('Laraspace\Api\Contracts\WebsiteVenueContract', function ($app) {
            return new WebsiteVenueService(new WebsiteVenueRepository(new PageService()));            
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Api\Contracts\WebsiteVenueContract'];
    }
}
