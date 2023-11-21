<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Repositories\TournamentRepository;
use Laraspace\Api\Services\TournamentService;

class TournamentServiceProvider extends ServiceProvider
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
        $this->app->bind(\Laraspace\Api\Contracts\TournamentContract::class, function ($app) {
            return new TournamentService(new TournamentRepository());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\Laraspace\Api\Contracts\TournamentContract::class];
    }
}
