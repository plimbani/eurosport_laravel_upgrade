<?php

namespace Laraspace\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Repositories\TeamRepository;
use Laraspace\Services\TeamService;

class TeamServiceProvider extends ServiceProvider
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
        $this->app->bind(\Laraspace\Contracts\TeamContract::class, function ($app) {
            return new TeamService(new TeamRepository());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\Laraspace\Contracts\TeamContract::class];
    }
}
