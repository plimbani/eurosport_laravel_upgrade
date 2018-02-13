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
    $this->pageService->updatePageDetails($stayPageDetail, $data['website_id']);

    // update meals page detail
    $mealsPageDetail = array();
    $mealsPageDetail['name'] = $this->mealsPageName;
    $mealsPageDetail['content'] = $data['meals_page_content'];
    $this->pageService->updatePageDetails($mealsPageDetail, $data['website_id']);

    // update accommodation page detail
    $accommodationPageDetail = array();
    $accommodationPageDetail['name'] = $this->accommodationPageName;
    $accommodationPageDetail['content'] = $data['accommodation_page_content'];
    $this->pageService->updatePageDetails($accommodationPageDetail, $data['website_id']);

    // save additional pages
    $this->saveAdditionalPages($data);
	}

  /*
   * Get staypage data
   *
   * @return response
   */
  public function getStayPageData($websiteId)
  {
    $pages = [$this->stayPageName, $this->mealsPageName, $this->accommodationPageName];
    $pagesData = $this->pageService->getMultiplePagesData($pages, $websiteId);
    $additionalPages = $this->pageService->getAdditionalPagesByParentId($pagesData['stay']['id'], $websiteId);      

    return array_merge($pagesData,['additionalPages' => $additionalPages]);
  }

  /*
   * Save additional page data
   *
   * @return response
   */
  public function saveAdditionalPages($data)
  {
    $websiteId = $data['website_id'];
    $additionalPages = $data['additional_pages'];

    $existingPageIds = $this->getAllAdditionalPageIds($data['parent_id']);

    $additionalPageIds = [];
    foreach ($additionalPages as $key => $page) {

      $pageData = $page;
      $pageData['order'] = $key + 1;

      if($pageData['id'] == '') {
        $url = $this->pageService->generateUrl($pageData['title'], '', $data['website_id']);
        $name = $this->pageService->generateName($pageData['title'], '', $data['website_id']);
        $pageData['slug'] = $url;
        $pageData['name'] = $name;
        $pageData['parent_id'] = $data['parent_id'];
        $pageData['is_additional_page'] = 1;

        $pageObject = $this->pageService->insertPageDetails($pageData, $websiteId);
      } else {
        $pageObject = $this->pageService->updatePageDetails($pageData, $websiteId);
      }

      $additionalPageIds[] = $pageObject->id;
    }

    $deletePageId = array_diff($existingPageIds, $additionalPageIds);

    $this->deletePages($deletePageId);
  }

  /*
   * Get all page ids
   *
   * @return response
   */
  public function getAllAdditionalPageIds($parentId)
  {
    $pageIds = Page::where('parent_id', $parentId)->where('is_additional_page', 1)->pluck('id')->toArray();
    return $pageIds;
  }

  /*
   * Delete pages
   *
   * @return response
   */
  public function deletePages($pageIds = [])
  {
    Page::whereIn('id', $pageIds)->delete();
    return true;
  }    
}