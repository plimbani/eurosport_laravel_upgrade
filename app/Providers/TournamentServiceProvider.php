<?php

namespace Laraspace\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Repositories\TournamentRepository;
use Laraspace\Repositories\VenueRepository;
use Laraspace\Services\TournamentService;
use Laraspace\Services\VenueTempService as VenueService;

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
        $this->app->bind(\Laraspace\Contracts\TournamentContract::class, function ($app) {
            return new TournamentService(new TournamentRepository(), new VenueService(new VenueRepository()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\Laraspace\Contracts\TournamentContract::class];
    }
}
