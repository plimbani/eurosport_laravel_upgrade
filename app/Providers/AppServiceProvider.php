<?php

namespace Laraspace\Providers;

use Illuminate\Support\ServiceProvider;
use Laraspace\Models\AgeCategory;
use Laraspace\Models\AgeCategoryTeam;
use Laraspace\Models\Contact;
use Laraspace\Models\Document;
use Laraspace\Models\HistoryAgeCategory;
use Laraspace\Models\HistoryTeam;
use Laraspace\Models\HistoryYear;
use Laraspace\Models\Itinerary;
use Laraspace\Models\Location;
use Laraspace\Models\Map;
use Laraspace\Models\Organiser;
use Laraspace\Models\Page;
use Laraspace\Models\Photo;
use Laraspace\Models\Sponsor;
use Laraspace\Models\Statistic;
use Laraspace\Models\Website;
use Laraspace\Observers\AgeCategoryObserver;
use Laraspace\Observers\AgeCategoryTeamObserver;
use Laraspace\Observers\ContactObserver;
use Laraspace\Observers\DocumentObserver;
use Laraspace\Observers\HistoryAgeCategoryObserver;
use Laraspace\Observers\HistoryTeamObserver;
use Laraspace\Observers\HistoryYearObserver;
use Laraspace\Observers\ItineraryObserver;
use Laraspace\Observers\LocationObserver;
use Laraspace\Observers\MapObserver;
use Laraspace\Observers\OrganiserObserver;
use Laraspace\Observers\PageObserver;
use Laraspace\Observers\PhotoObserver;
use Laraspace\Observers\SponsorObserver;
use Laraspace\Observers\StatisticObserver;
use Laraspace\Observers\WebsiteObserver;

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
        Website::observe(WebsiteObserver::class);
        Statistic::observe(StatisticObserver::class);
        Sponsor::observe(SponsorObserver::class);
        Photo::observe(PhotoObserver::class);
        Page::observe(PageObserver::class);
        Organiser::observe(OrganiserObserver::class);
        Location::observe(LocationObserver::class);
        Map::observe(MapObserver::class);
        Itinerary::observe(ItineraryObserver::class);
        HistoryYear::observe(HistoryYearObserver::class);
        HistoryTeam::observe(HistoryTeamObserver::class);
        HistoryAgeCategory::observe(HistoryAgeCategoryObserver::class);
        Document::observe(DocumentObserver::class);
        Contact::observe(ContactObserver::class);
        AgeCategoryTeam::observe(AgeCategoryTeamObserver::class);
        AgeCategory::observe(AgeCategoryObserver::class);
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
