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
    echo "<pre>";
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
      if(count($website->pages) === 0) {
        continue;
      }

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
      }

      $additionalpages = $website->pages->where('is_additional_page', 1);
      foreach ($additionalpages as $key => $additionalpage) {
        $parentPage = $website->pages->where('id', $additionalpage->parent_id)->first();
        $newParentPage = NewPage::where('name', $parentPage->name)->where('website_id', $websiteId)->first();
        $this->saveAdditionalPageData($newParentPage->id, $additionalpage, $websiteId);
      }
    }

    echo "Script run successfully.";
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
    $newAdditionalPage = new NewPage();
    $newAdditionalPage->url = $additionalpage->url;
    $newAdditionalPage->page_name = $additionalpage->page_name;
    $newAdditionalPage->website_id = $websiteId;
    $newAdditionalPage->parent_id = $parentId;
    $newAdditionalPage->name = $additionalpage->name;
    $newAdditionalPage->accessible_routes = $additionalpage->accessible_routes;
    $newAdditionalPage->title = $additionalpage->title;
    $newAdditionalPage->content = $additionalpage->content;
    $newAdditionalPage->order = $additionalpage->order;
    $newAdditionalPage->meta = $additionalpage->meta;
    $newAdditionalPage->is_additional_page = $additionalpage->is_additional_page;
    $newAdditionalPage->is_enabled = $additionalpage->is_enabled;
    $newAdditionalPage->is_published = $additionalpage->is_published;
    $newAdditionalPage->created_by = $additionalpage->created_by;
    $newAdditionalPage->updated_by = $additionalpage->updated_by;
    $newAdditionalPage->created_at = $additionalpage->created_at;
    $newAdditionalPage->updated_at = $additionalpage->updated_at;
    $newAdditionalPage->save();

    return $newAdditionalPage;
  }  
  
}
