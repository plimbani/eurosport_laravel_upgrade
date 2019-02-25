<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Laraspace\Http\Requests\WebsiteTournament\StoreUpdateRequest;
use Laraspace\Http\Requests\WebsiteTournament\GetWebsiteTournamentRequest;

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
	public function savePageData(StoreUpdateRequest $request)
	{
    return $this->websiteTournamentContract->savePageData($request->all());
	}

  /**
   * Get WebsiteTournament page data
   *
   * @return response
   */
  public function getPageData(GetWebsiteTournamentRequest $request, $websiteId)
  {
    return $this->websiteTournamentContract->getPageData($websiteId);
  }

}