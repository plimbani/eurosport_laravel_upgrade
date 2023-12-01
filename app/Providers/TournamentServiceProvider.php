<?php

namespace App\Providers;

use App\Repositories\TournamentRepository;
use App\Repositories\VenueRepository;
use App\Services\TournamentService;
use App\Services\VenueTempService as VenueService;
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
        $this->app->bind(\App\Contracts\TournamentContract::class, function ($app) {
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
        return [\App\Contracts\TournamentContract::class];
    }
}
