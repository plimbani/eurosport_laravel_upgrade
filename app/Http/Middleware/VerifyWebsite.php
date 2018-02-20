<?php

namespace Laraspace\Http\Middleware;

use App;
use View;
use Config;
use Closure;
use Landlord;
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

        if(!$website) {
            App::abort(404);
        }

        Landlord::addTenant('website', $website);

        // Get all published pages
        $menuItemArray = $website->getPublishedPages()->toArray();
        View::share('menu_items', Page::buildPageTree($menuItemArray));

        // Get all website's organisers
        $organisers = $website->organisers;
        View::share('organisers', $organisers);

        // Get all website's sponsors
        $sponsors = $website->sponsors;
        View::share('sponsors', $sponsors);

        Config::set('wot.current_domain', $domain);

        return $next($request);
    }
}
