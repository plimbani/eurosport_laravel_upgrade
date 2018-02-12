<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\WebsiteTournamentContract;

/**
 * Website tournament.
 *
 * @Author mtilokani@aecordigital.com
 */
class WebsiteTournamentController extends BaseController
{
	/**
   * @var WebsiteTournamentContract
   */
  protected $websiteTournamentContract;
  
  /**
   * Create a new controller instance.
   *
   * @param WebsiteTournamentContract $websiteTournamentContract
   */
  public function __construct(WebsiteTournamentContract $websiteTournamentContract)
  {
  	$this->websiteTournamentContract = $websiteTournamentContract;
  }

  /*
   * Save WebsiteTournament page data
   *
   * @return response
   */	
	public function saveWebsiteTournamentPageData(Request $request)
	{
    return $this->websiteTournamentContract->saveWebsiteTournamentPageData($request->all());
	}

  /**
   * Get WebsiteTournament page data
   *
   * @return response
   */
  public function getWebsiteTournamentPageData(Request $request, $websiteId)
  {
    return $this->websiteTournamentContract->getWebsiteTournamentPageData($websiteId);
  }

}