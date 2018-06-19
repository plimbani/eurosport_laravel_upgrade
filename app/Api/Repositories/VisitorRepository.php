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
    $meta = array();
    $meta['arrival_check_in_information'] = $data['arrival_check_in_information'];
    $meta['public_transport'] = $data['public_transport'];
    $meta['tips'] = $data['tips'];
    $pageDetail['meta'] = $meta;
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
    $touristPageDetail = $this->pageService->getPageDetails($this->touristPageName, $websiteId);
    return ['visitor' => $visitorPageDetail, 'tourist' => $touristPageDetail];
  }
}
