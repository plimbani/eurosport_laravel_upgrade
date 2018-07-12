<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Laraspace\Models\Website;
use Laraspace\Models\NewPage;
use Laraspace\Http\Requests\Homepage\GetStatisticsRequest;
use Laraspace\Http\Requests\Homepage\GetOrganisersRequest;
use Laraspace\Http\Requests\Homepage\StoreUpdateRequest;
use Laraspace\Http\Requests\Homepage\GetHomePageDataRequest;

// Need to define only contracts
use Laraspace\Api\Contracts\HomeContract;

/**
 * Home description.
 *
 * @Resource("pages")
 *
 */
class HomeController extends BaseController
{
	/**
   * @var HomeContract
   */
  protected $homeContract;

  /**
   * Create a new controller instance.
   *
   * @param HomeContract $homeContract
   */
  public function __construct(HomeContract $homeContract)
  {
  	$this->homeContract = $homeContract;
  }

  /**
   * Get all statistics
   *
   * Get a JSON representation of all the statistics.
   *
   * @Get("/getStatistics")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getStatistics(GetStatisticsRequest $request, $websiteId)
  {
    return $this->homeContract->getStatistics($websiteId);
  }

  /**
   * Get all organisers
   *
   * Get a JSON representation of all the organisers
   *
   * @Get("/getOrganisers")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getOrganisers(GetOrganisersRequest $request, $websiteId)
  {
    return $this->homeContract->getOrganisers($websiteId);
  }

  /**
   * Save page data
   *
   * @Get("/savePageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function savePageData(StoreUpdateRequest $request)
  {
    return $this->homeContract->savePageData($request);
  }

  /**
   * Get home page data
   *
   * @Get("/getWebsiteHomePageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getPageData(GetHomePageDataRequest $request, $websiteId)
  {
    return $this->homeContract->getPageData($websiteId);
  }

  public function changeWebsiteMenus(Request $request)
  {
    $websites = Website::with('pages')->get();
    $allDefaultPages = config('wot.website_default_pages');

    $navigationMenuReference = [
      'home' => 'home',
      'matches' => 'matches',
      'tournament' => 'tournament',
      'age_categories' => 'tournament',
      'teams' => 'teams',
      'rules' => 'rules',
      'history' => 'history',
      'program' => 'program',
      'program_overview' => 'program',
      'stay' => 'stay',
      'visitors' => 'visitors',
      'public_transport' => 'public_transport',
      'tourist_information' => 'tourist_information',
      'tips' => 'tips',
      'accommodation' => 'accommodation',
      'meals' => 'meals',      
      'venue' => 'venue',
      'media' => 'media',
      'contact' => 'contact'
    ];

    foreach ($websites as $allWebsitKey => $website) {
      $websitePages = $website->pages->keyBy('name');
      foreach ($allDefaultPages as $defaultPageKey => $page) {
        $pageName = $navigationMenuReference[$page['name']];
        $this->saveNewPageData($websitePages[$pageName], $pageName);
        
        if(array_key_exists('children', $page)) {
          foreach ($page['children'] as $childPageKey => $child) {
            $childPageName = $navigationMenuReference[$child['name']];
            $this->saveNewPageData($websitePages[$childPageName], $pageName);
          }
        }

      }
    }
  }

  public function saveNewPageData($pageData, $pageName)
  {
    // $newPage = new NewPage();
    // $newPage->url = 
    // $newPage->page_name = 
    // $newPage->website_id = 
    // $newPage->parent_id = 
    // $newPage->name = 
    // $newPage->accessible_routes = 
    // $newPage->title = 
    // $newPage->content = 
    // $newPage->order = 
    // $newPage->meta = 
    // $newPage->is_additional_page = 
    // $newPage->is_enabled = 
    // $newPage->is_published = 
    // $newPage->created_by = 
    // $newPage->updated_by = 
    // $newPage->save();

    // return $newPage;
  }
  
}
