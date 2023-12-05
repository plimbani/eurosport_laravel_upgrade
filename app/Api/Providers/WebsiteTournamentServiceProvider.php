<?php

namespace App\Api\Providers;

use App\Api\Repositories\WebsiteTournamentRepository;
use App\Api\Services\PageService;
use App\Api\Services\WebsiteTournamentService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('App\Api\Contracts\WebsiteTournamentContract', function ($app) {
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
        return ['App\Api\Contracts\WebsiteTournamentContract'];
    }
}
