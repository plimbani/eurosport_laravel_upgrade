<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\WebsiteVenueContract;

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

  /*
   * Save venue page data
   *
   * @return response
   */ 
  public function savePageData(Request $request)
  {
    return $this->websiteVenueContract->savePageData($request->all());
  }
}
