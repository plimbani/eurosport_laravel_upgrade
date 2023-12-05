<?php

namespace App\Api\Providers;

use App\Api\Repositories\MediaRepository;
use App\Api\Services\MediaService;
use App\Api\Services\PageService;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Api\Contracts\MediaContract', function ($app) {
            return new MediaService(new MediaRepository(new PageService()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Api\Contracts\MediaContract'];
    }
}
