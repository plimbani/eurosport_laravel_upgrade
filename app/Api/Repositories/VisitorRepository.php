<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Api\Services\PageService;

class VisitorRepository
{
  /**
   * @var Page service
   */
  protected $pageService;

  /**
   * @var Page name
   */
  protected $pageName;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->pageService = $pageService;
    $this->visitorPageName = 'visitors';
    $this->publicTransportPageName = 'public_transport';
    $this->tipsPageName = 'tips';
    $this->touristPageName = 'tourist_information';
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    // Visitor page details
    $pageDetail = array();
    $pageDetail['name'] = $this->visitorPageName;
    $pageDetail['content'] = $data['arrival_check_in_information']; 
    $this->pageService->updatePageDetails($pageDetail, $data['websiteId']);

    // Public transport page details
    $pageDetail = array();
    $pageDetail['name'] = $this->publicTransportPageName;
    $pageDetail['content'] = $data['public_transport']; 
    $this->pageService->updatePageDetails($pageDetail, $data['websiteId']);

    // Tips page details
    $pageDetail = array();
    $pageDetail['name'] = $this->tipsPageName;
    $pageDetail['content'] = $data['tips']; 
    $this->pageService->updatePageDetails($pageDetail, $data['websiteId']);

    // Visitor page details
    $pageDetail = array();
    $pageDetail['name'] = $this->touristPageName;
    $pageDetail['content'] = $data['tourist_information'];
    $this->pageService->updatePageDetails($pageDetail, $data['websiteId']);
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function getPageData($websiteId)
  {
    $visitorPageDetail = $this->pageService->getPageDetails($this->visitorPageName, $websiteId);
    $publicTransportPageDetail = $this->pageService->getPageDetails($this->publicTransportPageName, $websiteId);
    $tipsPageDetail = $this->pageService->getPageDetails($this->tipsPageName, $websiteId);
    $touristPageDetail = $this->pageService->getPageDetails($this->touristPageName, $websiteId);
    return ['visitor' => $visitorPageDetail, 'public_transport' => $publicTransportPageDetail, 'tips' => $tipsPageDetail, 'tourist' => $touristPageDetail];
  }
}
