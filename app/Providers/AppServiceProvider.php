<?php

namespace App\Providers;

use App\Models\AgeCategory;
use App\Models\AgeCategoryTeam;
use App\Models\Contact;
use App\Models\Document;
use App\Models\HistoryAgeCategory;
use App\Models\HistoryTeam;
use App\Models\HistoryYear;
use App\Models\Itinerary;
use App\Models\Location;
use App\Models\Map;
use App\Models\Organiser;
use App\Models\Page;
use App\Models\Photo;
use App\Models\Sponsor;
use App\Models\Statistic;
use App\Models\Website;
use App\Observers\AgeCategoryObserver;
use App\Observers\AgeCategoryTeamObserver;
use App\Observers\ContactObserver;
use App\Observers\DocumentObserver;
use App\Observers\HistoryAgeCategoryObserver;
use App\Observers\HistoryTeamObserver;
use App\Observers\HistoryYearObserver;
use App\Observers\ItineraryObserver;
use App\Observers\LocationObserver;
use App\Observers\MapObserver;
use App\Observers\OrganiserObserver;
use App\Observers\PageObserver;
use App\Observers\PhotoObserver;
use App\Observers\SponsorObserver;
use App\Observers\StatisticObserver;
use App\Observers\WebsiteObserver;
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
