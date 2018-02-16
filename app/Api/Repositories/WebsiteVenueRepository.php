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
	public function savePageData($data)
	{
		  
	}

}
