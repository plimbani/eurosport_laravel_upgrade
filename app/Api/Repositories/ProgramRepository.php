<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Models\Itinerary;
use Laraspace\Api\Services\PageService;

class ProgramRepository
{

  /**
   * @var Program page name
   */
  protected $programPageName;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->pageService = $pageService;
    $this->programPageName = 'program';
  }  

  /*
   * Get all itineraries
   *
   * @return response
   */	
	public function getItineraries($websiteId)
	{
		$itineraries = Itinerary::where('website_id', $websiteId)->orderBy('order')->get();
    return $itineraries;
	}

  /*
   * Save program page data
   *
   * @return response
   */ 
  public function saveProgramPageData($data)
  {
    $this->saveItineraries($data);
    $this->saveAdditionalPages($data);
  }

  /*
   * Save itinerary
   *
   * @return response
   */ 
  public function saveItineraries($data)
  {
    $websiteId = $data['websiteId'];
    $itineraries = $data['itineraries'];

    $existingItineraryId = $this->getAllItineraryIds($websiteId);

    $itineraryIds = [];

    for($i=0; $i<count($itineraries); $i++) {
      $itineraryData = $itineraries[$i];
      $itineraryData['order'] = $i + 1;
      if($itineraryData['id'] == '') {
        $itinerary = $this->insertItinerary($websiteId, $itineraryData);
      } else {
        $itinerary = $this->updateItinerary($itineraryData);
      }
      $itineraryIds[] = $itinerary->id;
    }

    $deleteItinerariesId = array_diff($existingItineraryId, $itineraryIds);

    $this->deleteItineraries($deleteItinerariesId);
  }

  /*
   * Get all itineraries ids
   *
   * @return response
   */
  public function getAllItineraryIds($websiteId)
  {
    $itineraryIds = Itinerary::where('website_id', $websiteId)->pluck('id')->toArray();
    return $itineraryIds;
  }

  /*
   * Insert itinerary
   *
   * @return response
   */
  public function insertItinerary($websiteId, $data)
  {
    $itinerary = new Itinerary();
    $itinerary->website_id = $websiteId;
    $itinerary->day = $data['day'];
    $itinerary->time = $data['time'];
    $itinerary->item = $data['item'];
    $itinerary->order = $data['order'];
    $itinerary->save();

    return $itinerary;
  }

  /*
   * Update itinerary
   *
   * @return response
   */
  public function updateItinerary($data)
  {
    $itinerary = Itinerary::find($data['id']);
    $itinerary->day = $data['day'];
    $itinerary->time = $data['time'];
    $itinerary->item = $data['item'];
    $itinerary->order = $data['order'];
    $itinerary->save();

    return $itinerary;
  }

  /*
   * Delete multiple itineraries
   *
   * @return response
   */
  public function deleteItineraries($itineraryIds = [])
  {
    Itinerary::whereIn('id', $itineraryIds)->delete();
    return true;
  }

  /*
   * Get program page data
   *
   * @return response
   */
  public function getProgramPageData($websiteId)
  {
    $pagesData = $this->pageService->getPageDetails($this->programPageName, $websiteId);  
    $additionalPages = $this->pageService->getAdditionalPagesByParentId($pagesData->id, $websiteId);

    return ['pagesData' => $pagesData, 'additionalPages' => $additionalPages];
  }

  /*
   * Save additional page data
   *
   * @return response
   */
  public function saveAdditionalPages($data)
  {
    $websiteId = $data['websiteId'];
    $additionalPages = $data['additional_pages'];

    $existingPageIds = $this->getAllAdditionalPageIds($data['parent_id']);

    $additionalPageIds = [];
    foreach ($additionalPages as $key => $page) {

      $pageData = $page;
      $pageData['order'] = $key + 1;

      if($pageData['id'] == '') {
        $url = $this->pageService->generateUrl($pageData['title'], $websiteId, $this->programPageName);
        $name = $this->pageService->generateName($pageData['title'], $websiteId);
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

    $this->pageService->deletePages($deletePageId);
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
}