<?php

namespace Laraspace\Api\Providers\Commercialisation;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Services\Commercialisation\TournamentPricingService;
use Laraspace\Api\Repositories\Commercialisation\TournamentPricingRepository;

class TournamentPricingServiceProvider extends ServiceProvider
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
        $this->app->bind('Laraspace\Api\Contracts\Commercialisation\TournamentPricingContract', function ($app) {
            return new TournamentPricingService(new TournamentPricingRepository());
        });        
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Contracts\Commercialisation\TournamentPricingContract'];
    }    
}
