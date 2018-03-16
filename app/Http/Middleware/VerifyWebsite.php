<?php

namespace Laraspace\Http\Middleware;

use App;
use View;
use Config;
use Closure;
use Landlord;
use Redirect;
use JavaScript;
use Carbon\Carbon;
use LaravelLocalization;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\WebsiteContract;

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

        if($website->is_website_offline == 1) {
          return Redirect::away($website->offline_redirect_url, 302);
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

        // Get image path
        $imagesPath = $this->websiteContract->getImagesPath();
        View::share('images_path', $imagesPath);

        // Get all website's organisers
        $organisers = $website->organisers;
        View::share('organisers', $organisers);

        // Get all website's sponsors
        $sponsors = $website->sponsors;
        View::share('sponsors', $sponsors);

        // Theme CSS path
        $colorThemes = config('wot.colorthemes');
        $themeCss = $website->color ? mix('frontend/css/' . $colorThemes[$website->color]) : mix('assets/css/frontend/main.css');
        View::share('theme_css', $themeCss);

        // Hero image
        $homePageDetail = $this->pageService->getPageDetails($this->homePageName, $website->id);
        $homePageMeta = $homePageDetail->meta;
        $heroImage = ($homePageMeta && isset($homePageMeta['hero_image']) && $homePageMeta['hero_image']) ? $homePageMeta['hero_image'] : null;
        $heroImage = config('filesystems.disks.s3.url') . config('wot.imagePath.hero_image') . $heroImage;
        View::share('hero_image', $heroImage);

        JavaScript::put([
            'websiteId' => $website->id,
            'serverAddr' => env('BROADCAST_SERVER_ADDRESS'),
            'serverPort' => env('BROADCAST_SERVER_PORT'),
            'broadcastChannel' => config('broadcasting.channel'),
            'appSchema' => config('app.app_scheme'),
        ]);

        JavaScript::put([
          'currentLocale' => LaravelLocalization::getCurrentLocale()
        ]);

        return $next($request);
    }
}
