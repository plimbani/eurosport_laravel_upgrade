<?php

namespace App\Api\Providers;

use App\Api\Repositories\TournamentRepository;
use App\Api\Services\TournamentService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(\App\Api\Contracts\TournamentContract::class, function ($app) {
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
        return [\App\Api\Contracts\TournamentContract::class];
    }
}
