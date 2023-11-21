<?php

namespace Laraspace\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('partials.frontend.meta', \Laraspace\Http\ViewComposers\WebsiteMetaComposer::class);
        View::composer(['frontend.*', 'errors.404'], \Laraspace\Http\ViewComposers\WebsiteComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
