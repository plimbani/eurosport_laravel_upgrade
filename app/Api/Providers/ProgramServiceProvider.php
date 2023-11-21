<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Repositories\ProgramRepository;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Services\ProgramService;

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
        $this->app->bind('Laraspace\Api\Contracts\ProgramContract', function ($app) {
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
        return ['Laraspace\Api\Contracts\ProgramContract'];
    }
}
