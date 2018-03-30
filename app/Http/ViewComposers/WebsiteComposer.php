<?php

namespace Laraspace\Http\ViewComposers;

use Landlord;
use JavaScript;
use LaravelLocalization;
use Illuminate\View\View;
use Laraspace\Models\Page;
use Illuminate\Http\Request;
use Laraspace\Models\Website;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\WebsiteContract;

class WebsiteComposer
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
  public function __construct(Request $request, PageService $pageService, WebsiteContract $websiteContract)
  {
      $this->request = $request;
      $this->websiteContract = $websiteContract;
      $this->pageService = $pageService;
      $this->homePageName = 'home';
  }

  /**
   * Bind data to the view.
   *
   * @param  View  $view
   * @return void
   */
  public function compose(View $view)
  {
    $domain = $this->request->server('SERVER_NAME');
    $website = Website::where('domain_name', $domain)->first();

    $view->with('websiteDetail', $website);

    // Get all published pages
    $pages = $website->getPublishedPages()->toArray();
    $view->with('menu_items_count', count($pages));
    $view->with('menu_items', Page::buildPageTree($pages));

    // Get image path
    $imagesPath = $this->websiteContract->getImagesPath();
    $view->with('images_path', $imagesPath);

    // Get all website's organisers
    $organisers = $website->organisers;
    $view->with('organisers', $organisers);

    // Get all website's sponsors
    $sponsors = $website->sponsors;
    $view->with('sponsors', $sponsors);

    // Theme CSS path
    $colorThemes = config('wot.colorthemes');
    $themeCss = $website->color ? mix('frontend/css/' . $colorThemes[$website->color]) : mix('frontend/css/main.css');
    $view->with('theme_css', $themeCss);

    // Brand font class
    $fontClasses = config('wot.font_class');
    $brandFontClass = $website->font ? $fontClasses[$website->font] : 'Open Sans';
    $view->with('brand_font_class', $brandFontClass);

    // Hero image
    $homePageDetail = $this->pageService->getPageDetails($this->homePageName, $website->id);
    $homePageMeta = $homePageDetail->meta;
    $heroImage = ($homePageMeta && isset($homePageMeta['hero_image']) && $homePageMeta['hero_image']) ? $homePageMeta['hero_image'] : null;
    $heroImage = config('filesystems.disks.s3.url') . config('wot.imagePath.hero_image') . Page::heroImageSize() . '/' . $heroImage;
    $view->with('hero_image', $heroImage);

    $accessibleRoutesArray = $website->getPublishedPages()->pluck('accessible_routes')->toArray();
    $accessibleRoutesCollection = collect($accessibleRoutesArray);
    $flattenedAccessibleRoutes = $accessibleRoutesCollection->flatten();
    $accessibleRoutes = $flattenedAccessibleRoutes->unique()->toArray();
    $accessibleRoutes = array_merge($accessibleRoutes, config('wot.default_accessible_routes'));

    // All accessible routes
    $view->with('accessible_routes', $accessibleRoutes);

    JavaScript::put([
        'websiteId' => $website->id,
        'tournamentId' => $website->linked_tournament,
        'serverAddr' => env('BROADCAST_SERVER_ADDRESS'),
        'serverPort' => env('BROADCAST_SERVER_PORT'),
        'broadcastChannel' => config('broadcasting.channel'),
        'appSchema' => config('app.app_scheme'),
    ]);

    JavaScript::put([
      'currentLocale' => LaravelLocalization::getCurrentLocale()
    ]);
  }
}