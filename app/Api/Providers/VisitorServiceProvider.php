<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Repositories\VisitorRepository;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Services\VisitorService;

class VisitorServiceProvider extends ServiceProvider
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
        $this->app->bind('Laraspace\Api\Contracts\VisitorContract', function ($app) {
            return new VisitorService(new VisitorRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laraspace\Api\Contracts\VisitorContract'];
    }
}
