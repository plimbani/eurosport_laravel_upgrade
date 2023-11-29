<?php

namespace App\Api\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

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
        foreach (Config::get('wot.website_default_pages') as $key => $page) {
            if (isset($page['children'])) {
                foreach ($page['children'] as $childKey => $childPage) {
                    if ($childPage['name'] == 'age_categories') {
                        Config::set("wot.website_default_pages.$key.children.$childKey.content", file_get_contents(base_path('resources/predefined_html/age_category.html')));
                    }
                }
            }
        }
    }
}
