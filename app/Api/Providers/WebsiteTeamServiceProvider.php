<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Repositories\WebsiteTeamRepository;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Services\WebsiteTeamService;

class WebsiteTeamServiceProvider extends ServiceProvider
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
        $this->app->bind('Laraspace\Api\Contracts\WebsiteTeamContract', function ($app) {
            return new WebsiteTeamService(new WebsiteTeamRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Api\Contracts\WebsiteTeamContract'];
    }
}
