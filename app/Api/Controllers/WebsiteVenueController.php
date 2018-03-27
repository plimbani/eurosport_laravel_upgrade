<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\WebsiteVenueContract;

use Laraspace\Http\Requests\Venue\GetLocationsRequest;
use Laraspace\Http\Requests\Venue\GetMarkersRequest;
use Laraspace\Http\Requests\Venue\StoreUpdateRequest;

/**
 * Tournament Resource Description.
 *
 * @Resource("tournament")
 *
 * @Author Knayak@aecordigital.com
 */
class WebsiteVenueController extends BaseController
{
  /**
   * @var stayContract
   */
  protected $websiteVenueContract;

  /**
   * @param object $tournamentObj
   */
  public function __construct(WebsiteVenueContract $websiteVenueContract)
  {
      $this->websiteVenueContract = $websiteVenueContract;
  }

  /**
   * Get all locations
   *
   * Get a JSON representation of all the locations.
   *
   * @Get("/getLocations")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getLocations(GetLocationsRequest $request, $websiteId)
  {
    return $this->websiteVenueContract->getLocations($websiteId);
  }

  /**
   * Get all markers
   *
   * Get a JSON representation of all the markers.
   *
   * @Get("/getMarkers")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getMarkers(GetMarkersRequest $request, $websiteId)
  {
    return $this->websiteVenueContract->getMarkers($websiteId);
  }

  /*
   * Save venue page data
   *
   * @return response
   */ 
  public function savePageData(StoreUpdateRequest $request)
  {
    return $this->websiteVenueContract->savePageData($request->all());
  }
}
