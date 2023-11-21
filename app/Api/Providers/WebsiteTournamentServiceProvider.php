<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Repositories\WebsiteTournamentRepository;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Services\WebsiteTournamentService;

class WebsiteTournamentServiceProvider extends ServiceProvider
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
        $this->app->bind('Laraspace\Api\Contracts\WebsiteTournamentContract', function ($app) {
            return new WebsiteTournamentService(new WebsiteTournamentRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Api\Contracts\WebsiteTournamentContract'];
    }
}
