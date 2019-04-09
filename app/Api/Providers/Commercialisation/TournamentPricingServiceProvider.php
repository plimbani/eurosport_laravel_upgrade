<?php

namespace Laraspace\Api\Providers\Commercialisation;

use Laraspace\Api\Repositories\Commercialisation\TournamentPricingRepository;
use Laraspace\Api\Services\Commercialisation\TournamentPricingService;
use Illuminate\Support\ServiceProvider;

class TournamentPricingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Laraspace\Api\Contracts\Commercialisation\TournamentPricingContract', function ($app) {
            return new TournamentPricingService(new TournamentPricingRepository());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        return ['Laraspace\Contracts\Commercialisation\TournamentPricingContract'];
    }
}
