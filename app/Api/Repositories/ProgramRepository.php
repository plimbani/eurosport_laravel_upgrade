<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Models\Itinerary;
use Laraspace\Models\ItineraryItem;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Api\Services\PageService;

class ProgramRepository
{
  use AuthUserDetail;

  /**
   * @var Program page name
   */
  protected $programPageName;

  /**
   * @var Program page name
   */
  protected $programPageUrl;

  /**
   * @var Additional page route name
   */
  protected $additionalPageRoutesName;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->pageService = $pageService;
    $this->programPageName = 'program';
    $this->programPageUrl = '/program';
    $this->additionalPageRoutesName = ['additional.program.page.details'];
  }

  /*
   * Get all itineraries
   *
   * @return response
   */
	public function getAllItineraries($websiteId)
	{
		$itineraries = Itinerary::with(['items' => function($query){
            $query->orderBy('order');
          }])->where('website_id', $websiteId)->orderBy('order')->get();
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
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($itineraryData['id'] == '') {
        $itinerary = $this->insertItinerary($websiteId, $currentLoggedInUserId, $itineraryData);
      } else {
        $itinerary = $this->updateItinerary($currentLoggedInUserId, $itineraryData);
      }
      $this->saveItineraryItems($itineraryData['items'], $itinerary->id, $websiteId);      
      $itineraryIds[] = $itinerary->id;
    }

    $deleteItinerariesId = array_diff($existingItineraryId, $itineraryIds);

    $this->deleteItineraries($deleteItinerariesId);
  }

  /*
   * Save itinerary items
   *
   * @return response
   */
  function saveItineraryItems($items, $itineraryId, $websiteId) {
    $existingItineraryItemsId = $this->getAllItineraryItemsIds($itineraryId, $websiteId);

    $itineraryItemsIds = [];
    for($i=0; $i<count($items); $i++) {
      $itemData = $items[$i];
      $itemData['order'] = $i + 1;
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($itemData['id'] == '') {
        $ItineraryItem = $this->insertItineraryItem($itineraryId, $websiteId, $currentLoggedInUserId, $itemData);
      } else {
        $ItineraryItem = $this->updateItineraryItem($currentLoggedInUserId, $itemData);
      }
      $itineraryItemsIds[] = $ItineraryItem->id;      
    }
    $deleteItineraryItemId = array_diff($existingItineraryItemsId, $itineraryItemsIds);

    $this->deleteItineraryItems($deleteItineraryItemId);
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
   * Get all age category teams ids
   *
   * @return response
   */
  public function getAllItineraryItemsIds($itineraryId, $websiteId)
  {
    $itineraryItemsIds = ItineraryItem::where('website_id', $websiteId)->where('itinerary_id', $itineraryId)->pluck('id')->toArray();

    return $itineraryItemsIds;
  }

  /*
   * Insert itinerary item
   *
   * @return response
   */
  public function insertItineraryItem($itineraryId, $websiteId, $currentLoggedInUserId, $data)
  {
    $itineraryItem = new ItineraryItem();

    $itineraryItem->website_id = $websiteId;
    $itineraryItem->itinerary_id = $itineraryId;
    $itineraryItem->day = $data['day'];
    $itineraryItem->time = $data['time'];
    $itineraryItem->item = $data['item'];
    $itineraryItem->order = $data['order'];
    $itineraryItem->created_by = $currentLoggedInUserId;
    $itineraryItem->save();

    return $itineraryItem;
  }

  /*
   * Update itinerary item
   *
   * @return response
   */
  public function updateItineraryItem($currentLoggedInUserId, $data)
  {
    $tineraryItem = ItineraryItem::find($data['id']);
    $tineraryItem->day = $data['day'];
    $tineraryItem->time = $data['time'];
    $tineraryItem->item = $data['item'];
    $tineraryItem->order = $data['order'];
    if($tineraryItem->isDirty()) {
      $tineraryItem->updated_by = $currentLoggedInUserId;
      $tineraryItem->save();
    }

    return $tineraryItem;
  }

  /*
   * Insert itinerary
   *
   * @return response
   */
  public function insertItinerary($websiteId, $currentLoggedInUserId, $data)
  {
    $itinerary = new Itinerary();
    $itinerary->website_id = $websiteId;
    $itinerary->name = $data['name'];
    $itinerary->order = $data['order'];
    $itinerary->created_by = $currentLoggedInUserId;
    $itinerary->save();

    return $itinerary;
  }

  /*
   * Update itinerary
   *
   * @return response
   */
  public function updateItinerary($currentLoggedInUserId, $data)
  {
    $itinerary = Itinerary::find($data['id']);
    $itinerary->name = $data['name'];
    $itinerary->order = $data['order'];
    if($itinerary->isDirty()) {
      $itinerary->updated_by = $currentLoggedInUserId;
      $itinerary->save();
    }

    return $itinerary;
  }

  /*
   * Delete multiple itineraries
   *
   * @return response
   */
  public function deleteItineraries($itineraryIds = [])
  {
    Itinerary::whereIn('id', $itineraryIds)->get()->each(function($itinerary) {
      $itinerary->delete();
    });
    return true;
  }

  /*
   * Delete multiple itinerary items
   *
   * @return response
   */
  public function deleteItineraryItems($itineraryItemIds = [])
  {
    ItineraryItem::whereIn('id', $itineraryItemIds)->get()->each(function($itinerariesItem) {
      $itinerariesItem->delete();
    });
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
        $pageDetails = $this->pageService->generateUrl($pageData['title'], $websiteId, $this->programPageUrl);
        $name = $this->pageService->generateName($pageData['title'], $websiteId);
        $pageData['url'] = $pageDetails['url'];
        $pageData['page_name'] = $pageDetails['page_name'];
        $pageData['accessible_routes'] = $this->additionalPageRoutesName;
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