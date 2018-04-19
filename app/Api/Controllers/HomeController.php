<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
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
  
}
