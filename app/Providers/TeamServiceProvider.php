<?php

namespace App\Providers;

use App\Repositories\TeamRepository;
use App\Services\TeamService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(\App\Contracts\TeamContract::class, function ($app) {
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
        return [\App\Contracts\TeamContract::class];
    }
}
