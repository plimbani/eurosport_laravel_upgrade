<?php

namespace App\Api\Providers;

use App\Api\Repositories\ProgramRepository;
use App\Api\Services\PageService;
use App\Api\Services\ProgramService;
use Illuminate\Support\ServiceProvider;

class ProgramServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Api\Contracts\ProgramContract', function ($app) {
            return new ProgramService(new ProgramRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Api\Contracts\ProgramContract'];
    }
}
