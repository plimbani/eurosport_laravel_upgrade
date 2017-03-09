<?php

namespace Laraspace\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    protected $localProviders;
    protected $localAliases;
    
    public function __construct($app)
    {
        $this->app = $app;
        $this->localProviders = config('app.localProviders');
        $this->localAliases = config('app.localAliases');
    }


    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //register the service providers
        if ($this->app->isLocal() && ! empty($this->localProviders)) {
            foreach ($this->localProviders as $provider) {
                $this->app->register($provider);
            }
        }
        //register the alias
        if ($this->app->isLocal() && ! empty($this->localAliases)) {
            foreach ($this->localAliases as $alias => $facade) {
                $this->app->alias($alias, $facade);
            }
        }
    }
}
