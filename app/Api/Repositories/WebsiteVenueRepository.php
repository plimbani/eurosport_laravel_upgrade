<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Venue;
use Laraspace\Models\Website;
use Laraspace\Api\Services\PageService;


class WebsiteVenueRepository
{
	/**
   * @var Page service
   */
  protected $pageService;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->pageService = $pageService;
  }

  /*
   * Save venue page data
   *
   * @return response
   */	
	public function saveVenuePageData($data)
	{
		echo "<pre>";print_r($data);echo "</pre>";exit;
		/*if(isset($data['websiteId']) && $data['websiteId'] != null){
      $websiteId = $data['websiteId'];
      $website = Website::find($websiteId);
      $data['isExistingWebsite'] = true;
    } else {
      $website = new Website();
      $data['isExistingWebsite'] = false;
    }*/
	}

    public function getAllVenues($tournamentId)
    {
        return Venue::orderBy('name','ASC')->where('tournament_id',$tournamentId)->get();
    }


}
