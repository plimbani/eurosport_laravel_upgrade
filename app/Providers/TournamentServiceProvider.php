<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TournamentService;
use App\Services\VenueService;
use App\Repositories\VenueRepository;
use App\Repositories\TournamentRepository;

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
        $this->app->bind('App\Contracts\TournamentContract', function ($app) {
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
        return ['App\Contracts\TournamentContract'];
    }
}
