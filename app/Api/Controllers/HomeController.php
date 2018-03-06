<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

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
  public function getStatistics(Request $request, $websiteId)
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
  public function getOrganisers(Request $request, $websiteId)
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
  public function savePageData(Request $request)
  {
    return $this->homeContract->savePageData($request);
  }

  /**
   * Get home page data
   *
   * @Get("/getHomePageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getPageData(Request $request, $websiteId)
  {
    return $this->homeContract->getPageData($websiteId);
  }
  
}
