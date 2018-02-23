<?php

namespace Laraspace\Http\Middleware;

use App;
use View;
use Config;
use Closure;
use Landlord;
use Carbon\Carbon;
use Laraspace\Models\Page;
use Laraspace\Models\Website;

class VerifyWebsite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $domain = $request->route('domain');
        $website = Website::where('domain_name', $domain)->first();
        View::share('websiteDetail', $website);

        if(!$website) {
            App::abort(404);
        }

        Landlord::addTenant('website', $website);

        // Get all published pages
        $pages = $website->getPublishedPages()->toArray();
        View::share('menu_items', Page::buildPageTree($pages));

        $accessibleRoutesArray = $website->getPublishedPages()->pluck('accessible_routes')->toArray();
        $accessibleRoutesCollection = collect($accessibleRoutesArray);
        $flattenedAccessibleRoutes = $accessibleRoutesCollection->flatten();
        $accessibleRoutes = $flattenedAccessibleRoutes->unique()->toArray();
        $accessibleRoutes = array_merge($accessibleRoutes, config('wot.default_accessible_routes'));
        $currentRoute = $request->route()->getName();
        if(!in_array($currentRoute, $accessibleRoutes)) {
            App::abort(404);
        }

        // Get all website's organisers
        $organisers = $website->organisers;
        View::share('organisers', $organisers);

        // Get all website's sponsors
        $sponsors = $website->sponsors;
        View::share('sponsors', $sponsors);

        $days = Config::get('wot.message_notification_days');
        $createdAfter = Carbon::today()->subDay($days);
        $messages = $website->messages()->whereDate('created_at', '>=', $createdAfter)->orderBy('created_at', 'desc')->get();
        View::share('messages', $messages);

        Config::set('wot.current_domain', $domain);

        return $next($request);
    }
}
