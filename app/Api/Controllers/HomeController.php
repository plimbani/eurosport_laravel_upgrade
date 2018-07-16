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
      'public_transport' => 'visitors',
      'tourist_information' => 'tourist_information',
      'tips' => 'visitors',
      'accommodation' => 'accommodation',
      'meals' => 'meals',
      'venue' => 'venue',
      'media' => 'media',
      'contact' => 'contact'
    ];

    foreach ($websites as $allWebsitKey => $website) {
      $websitePages = $website->pages->keyBy('name');
      $websiteId = $website->id;
      foreach ($allDefaultPages as $defaultPageKey => $page) {
        $pageName = $navigationMenuReference[$page['name']];
        $parentPageData = $this->saveNewPageData($websitePages[$pageName], $page, $websiteId, null);

        if(array_key_exists('children', $page)) {
          foreach ($page['children'] as $childPageKey => $child) {
            $childPageName = $navigationMenuReference[$child['name']];
            $isParentPageReference = 0;
            if($childPageName == 'public_transport' || $childPageName == 'tips') {
              $isParentPageReference = 1;
            }
            $this->saveNewPageData($websitePages[$childPageName], $child, $websiteId, $parentPageData->id, $isParentPageReference);
          }
        }

        //is_additional_page = 1 from pages
        //loop start
          //$additionalpage
          //1) get parent page record from old table website->pages
          //2) check for that page name in new table and get record id of it
          //method call with the recordid and additionalpage
        //loop end

        //in method, make entry from additionalpage and provide parent_id ad recordid
      }

        $additionalpages = $website->pages->where('is_additional_page', 1);
echo "<pre>";
        foreach ($additionalpages as $key => $additionalpage) {
          $parentPage = $website->pages->where('parent_id', $additionalpage->parent_id)->first();
          $newParentPage = NewPage::where('name', $parentPage->name)->where('website_id', $websiteId)->first();
          print_r($parentPage);
          echo $websiteId;
          echo "<pre>";var_dump($newParentPage);echo "</pre>";
          $this->saveAdditionalPageData($newParentPage->id, $additionalpage, $websiteId);
        }
    }
  }

  public function saveNewPageData($pageData, $pageItems, $websiteId, $parentId, $isParentPageReference = 0)
  {
    $newPage = new NewPage();
    $newPage->url = $pageItems['url'];
    $newPage->page_name = $pageItems['page_name'];
    $newPage->website_id = $websiteId;
    $newPage->parent_id = $parentId;
    $newPage->name = $pageItems['name'];
    $newPage->accessible_routes = $pageItems['accessible_routes'][0];
    $newPage->title = $pageItems['title'];
    $newPage->content = $isParentPageReference == 0 ? $pageData->content : null;
    $newPage->order = $pageData->order;
    $newPage->meta = $isParentPageReference == 0 ? $pageData->meta : null;
    $newPage->is_additional_page = $pageData->is_additional_page;
    $newPage->is_enabled = $pageData->is_enabled;
    $newPage->is_published = $pageData->is_published;
    $newPage->created_by = $pageData->created_by;
    $newPage->updated_by = $pageData->updated_by;
    $newPage->created_at = $pageData->created_at;
    $newPage->updated_at = $pageData->updated_at;
    $newPage->save();

    return $newPage;
  }

  public function saveAdditionalPageData($parentId, $additionalpage, $websiteId)
  {
    $newPage = new NewPage();
    $newPage->url = $additionalpage->url;
    $newPage->page_name = $additionalpage->page_name;
    $newPage->website_id = $websiteId;
    $newPage->parent_id = $parentId;
    $newPage->name = $additionalpage->name;
    $newPage->accessible_routes = $additionalpage->accessible_routes;
    $newPage->title = $additionalpage->title;
    $newPage->content = $additionalpage->content;
    $newPage->order = $additionalpage->order;
    $newPage->meta = $additionalpage->meta;
    $newPage->is_additional_page = $additionalpage->is_additional_page;
    $newPage->is_enabled = $additionalpage->is_enabled;
    $newPage->is_published = $additionalpage->is_published;
    $newPage->created_by = $additionalpage->created_by;
    $newPage->updated_by = $additionalpage->updated_by;
    $newPage->created_at = $additionalpage->created_at;
    $newPage->updated_at = $additionalpage->updated_at;
    $newPage->save();

    return $newPage;
  }  
  
}
