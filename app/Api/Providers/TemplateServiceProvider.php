<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Repositories\AgeGroupRepository;
use Laraspace\Api\Repositories\TemplateRepository;
use Laraspace\Api\Services\AgeGroupService;
use Laraspace\Api\Services\TemplateService;

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
        $this->app->bind('Laraspace\Api\Contracts\TemplateContract', function ($app) {
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
        return ['Laraspace\Api\Contracts\TemplateContract'];
    }
}
