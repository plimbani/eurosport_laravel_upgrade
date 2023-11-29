<?php

namespace App\Api\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\Repositories\AgeGroupRepository;
use App\Api\Repositories\TemplateRepository;
use App\Api\Services\AgeGroupService;
use App\Api\Services\TemplateService;

class TemplateServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Api\Contracts\TemplateContract::class, function ($app) {
            return new TemplateService(new TemplateRepository(new AgeGroupService(new AgeGroupRepository())));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\App\Api\Contracts\TemplateContract::class];
    }
}
