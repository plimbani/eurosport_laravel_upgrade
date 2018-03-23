<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\WebsiteTeamContract;

use Laraspace\Http\Requests\WebsiteTeam\StoreUpdateRequest;
use Laraspace\Http\Requests\WebsiteTeam\GetTeamPageDataRequest;
use Laraspace\Http\Requests\WebsiteTeam\GetAgeCategoriesDataRequest;
use Laraspace\Http\Requests\WebsiteTeam\ImportAgeCategoryAndTeamDataRequest;

/**
 * Home description.
 *
 * @Resource("pages")
 *
 */
class WebsiteTeamController extends BaseController
{
  /**
   * @var WebsiteTeamContract
   */
  protected $websiteTeamContract;

  /**
   * Create a new controller instance.
   *
   * @param WebsiteTeamContract $websiteTeamContract
   */
  public function __construct(WebsiteTeamContract $websiteTeamContract)
  {
    $this->websiteTeamContract = $websiteTeamContract;
  }

  /**
   * Get all age categories
   *
   * @Get("/getAllAgeCategories")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getAgeCategories(GetAgeCategoriesDataRequest $request, $websiteId)
  {
    return $this->websiteTeamContract->getAgeCategories($websiteId);
  }

  /*
   * Save page data
   *
   * @Get("/savePageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function savePageData(StoreUpdateRequest $request)
  {
    return $this->websiteTeamContract->savePageData($request);
  }

  /**
   * Get page data
   *
   * @Get("/getPageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getPageData(GetTeamPageDataRequest $request, $websiteId)
  {
    return $this->websiteTeamContract->getPageData($request);
  }
  
  /**
   * Import age category and team data
   *
   * @Get("/importAgeCategoryAndTeamData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */

  public function importAgeCategoryAndTeamData(ImportAgeCategoryAndTeamDataRequest $request)
  {
    return $this->websiteTeamContract->importAgeCategoryAndTeamData($request);
  }
}
