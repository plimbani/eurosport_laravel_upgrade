<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TeamService;
use App\Repositories\TeamRepository;

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
        $this->app->bind('App\Contracts\TeamContract', function ($app) {
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
        return ['App\Contracts\TeamContract'];
    }
}
