<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Services\StayService;
use Laraspace\Api\Repositories\StayRepository;

class StayServiceProvider extends ServiceProvider
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
      $this->app->bind('Laraspace\Api\Contracts\StayContract', function ($app) {
          return new StayService(new StayRepository(new PageService()));
      });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
      return ['Laraspace\Api\Contracts\StayContract'];
  }  
}