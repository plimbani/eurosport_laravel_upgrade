<?php

namespace Laraspace\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setTournamentPageContent();
    }

    /**
     * Make relative urls into absolute urls
     *
     * @return void
     */
    public function setTournamentPageContent()
    {
        foreach(Config::get('wot.website_default_pages') as $key => $page) {
            if($page['name'] == 'tournament') {
                Config::set("wot.website_default_pages.$key.content", file_get_contents(base_path('resources/predefined_html/age_category.html')));
            }
        }
    }
}