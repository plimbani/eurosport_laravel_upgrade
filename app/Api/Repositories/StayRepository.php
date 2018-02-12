<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Api\Services\PageService;

class StayRepository
{
  /**
   * @var Page service
   */
  protected $pageService;

  /**
   * @var Stay page name
   */
  protected $stayPageName;

  /**
   * @var Meals page name
   */
  protected $mealsPageName;

  /**
   * @var Accommodation page name
   */
  protected $accommodationPageName;  

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->pageService = $pageService;
    $this->stayPageName = 'stay';
    $this->mealsPageName = 'meals';
    $this->accommodationPageName = 'accommodation';
  }
  /*
   * Save staypage data
   *
   * @return response
   */	
	public function saveStayPageData($data)
	{
		// update stay page detail
    $stayPageDetail = array();
    $stayPageDetail['name'] = $this->stayPageName;
    $stayPageDetail['content'] = $data['stay_introduction_content'];
    $this->pageService->updatePageDetails($stayPageDetail, $data['websiteId']);

    // update meals page detail
    $mealsPageDetail = array();
    $mealsPageDetail['name'] = $this->mealsPageName;
    $mealsPageDetail['content'] = $data['meals_page_content'];
    $this->pageService->updatePageDetails($mealsPageDetail, $data['websiteId']);

    // update accommodation page detail
    $accommodationPageDetail = array();
    $accommodationPageDetail['name'] = $this->accommodationPageName;
    $accommodationPageDetail['content'] = $data['accommodation_page_content'];
    $this->pageService->updatePageDetails($accommodationPageDetail, $data['websiteId']);
	}

  /*
   * Get staypage data
   *
   * @return response
   */
  public function getStayPageData($websiteId)
  {
    $pages = [$this->stayPageName, $this->mealsPageName, $this->accommodationPageName];
   
    return $this->pageService->getMultiplePagesData($pages, $websiteId);
  }

  /*
   * Save additional page data
   *
   * @return response
   */
  public function addAdditionalPage($data)
  {
    echo "<pre>";print_r($data);echo "</pre>";exit;
    $page = new Page();
  }
}