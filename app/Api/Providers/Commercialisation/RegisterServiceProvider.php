<?php

namespace Laraspace\Api\Providers\Commercialisation;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Services\Commercialisation\RegisterService;
use Laraspace\Api\Repositories\Commercialisation\RegisterRepository;
use Laraspace\Api\Services\Commercialisation\TournamentPricingService;

class RegisterServiceProvider extends ServiceProvider
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
        $this->app->bind('Laraspace\Api\Contracts\Commercialisation\RegisterContract', function ($app) {
            return new RegisterService(new RegisterRepository());
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Api\Contracts\Commercialisation\RegisterContract'];
    }
}
