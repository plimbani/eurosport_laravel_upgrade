<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\Services\TeamService;

class TeamServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Api\Contracts\TeamContract', function ($app) {
            return new TeamService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Api\Contracts\TeamContract'];
    }
}
