<?php

namespace App\Http\Middleware;

use App;
use App\Api\Contracts\WebsiteContract;
use App\Api\Services\PageService;
use App\Models\Page;
use App\Models\Website;
use Closure;
use Landlord;
use Redirect;
use View;

class VerifyWebsite
{
    /**
     * @var Home page name
     */
    protected $homePageName;

    /**
     * @var Page service
     */
    protected $pageService;

    /**
     * @var Website contract
     */
    protected $websiteContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PageService $pageService, WebsiteContract $websiteContract)
    {
        $this->websiteContract = $websiteContract;
        $this->pageService = $pageService;
        $this->homePageName = 'home';
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $previewUrl = config('config-variables.website_preview_url');
        $domain = $request->route('domain');
        $website = Website::where('domain_name', $domain)->orWhere('preview_domain', $domain)->first();
        View::share('websiteDetail', $website);

        if (! $website) {
            return Redirect::away(config('app.url'), 302);
        }

        if ($website->is_website_offline == 1 && strpos($domain, str_replace('{id}-', '', $previewUrl)) === false) {
            return Redirect::away($website->offline_redirect_url, 302);
        }

        Landlord::addTenant('website', $website);

        $accessibleRoutesArray = $website->getPublishedPages()->pluck('accessible_routes')->toArray();
        $accessibleRoutesCollection = collect($accessibleRoutesArray);
        $flattenedAccessibleRoutes = $accessibleRoutesCollection->flatten();
        $accessibleRoutes = $flattenedAccessibleRoutes->unique()->toArray();
        $accessibleRoutes = array_merge($accessibleRoutes, config('wot.default_accessible_routes'));
        $currentRoute = $request->route()->getName();
        if (! in_array($currentRoute, $accessibleRoutes)) {
            App::abort(404);
        }

        return $next($request);
    }
}
