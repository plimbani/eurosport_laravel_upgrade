<?php

namespace App\Api\Providers;

use App\Api\Repositories\WebsiteRepository;
use App\Api\Services\PageService;
use App\Api\Services\WebsiteService;
use Illuminate\Support\ServiceProvider;

class WebsiteServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Api\Contracts\WebsiteContract', function ($app) {
            return new WebsiteService(new WebsiteRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Api\Contracts\WebsiteContract'];
    }
}
