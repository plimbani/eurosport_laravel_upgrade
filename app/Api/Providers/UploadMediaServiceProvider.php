<?php

namespace App\Api\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\Services\UploadMediaService;

class UploadMediaServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Api\Contracts\UploadMediaContract::class, function ($app) {
            return new UploadMediaService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Api\Contracts\UploadMediaContract::class];
    }
}
