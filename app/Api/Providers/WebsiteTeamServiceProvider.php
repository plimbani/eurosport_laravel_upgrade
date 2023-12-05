<?php

namespace App\Api\Providers;

use App\Api\Repositories\WebsiteTeamRepository;
use App\Api\Services\PageService;
use App\Api\Services\WebsiteTeamService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('App\Api\Contracts\WebsiteTeamContract', function ($app) {
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
        return ['App\Api\Contracts\WebsiteTeamContract'];
    }
}
