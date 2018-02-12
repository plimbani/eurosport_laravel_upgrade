<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Api\Services\PageService;

class WebsiteTournamentRepository
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
    $this->getAWSUrl = getenv('S3_URL');
    $this->pageService = $pageService;
    $this->age_categories = 'Tournament';
    $this->rules = 'Rules';
  }

  /*
   * Save website tournament data
   *
   * @return response
   */	
	public function saveWebsiteTournamentPageData($data)
	{
		// update stay page detail
    $wtPageDetail = array();
    $wtPageDetail['name'] = $this->age_categories;
    $wtPageDetail['content'] = $data['age_categories'];
    $this->pageService->updatePageDetails($wtPageDetail, $data['websiteId']);

    // update meals page detail
    $mealsPageDetail = array();
    $mealsPageDetail['name'] = $this->rules;
    $mealsPageDetail['content'] = $data['rules'];
    $this->pageService->updatePageDetails($mealsPageDetail, $data['websiteId']);
	}

  /*
   * Get website tournament data
   *
   * @return response
   */
  public function getWebsiteTournamentPageData($websiteId)
  {
    $pages = [$this->age_categories, $this->rules];
    return $this->pageService->getMultiplePagesData($pages, $websiteId);
  }
}