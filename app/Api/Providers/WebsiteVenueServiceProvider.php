<?php

namespace App\Api\Providers;

use App\Api\Repositories\WebsiteVenueRepository;
use App\Api\Services\PageService;
use App\Api\Services\WebsiteVenueService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('App\Api\Contracts\WebsiteVenueContract', function ($app) {
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
        return ['App\Api\Contracts\WebsiteVenueContract'];
    }
}
