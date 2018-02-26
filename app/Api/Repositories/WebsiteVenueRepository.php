<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Location;
use Laraspace\Api\Services\PageService;


class WebsiteVenueRepository
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
    $this->pageName = 'venue';
  }

  /*
   * Get all locations
   *
   * @return response
   */
  public function getAllLocations($websiteId)
  {
    $locations = Location::where('website_id', $websiteId)->orderBy('order')->get();
    return $locations;
  }

  /*
   * Get all location ids
   *
   * @return response
   */
  public function getAllLocationIds($websiteId)
  {
    $locationIds = Location::where('website_id', $websiteId)->pluck('id')->toArray();
    return $locationIds;
  }

  /*
   * Insert location
   *
   * @return response
   */
  public function insertLocation($websiteId, $data)
  {
    $location = new Location();
    $location->website_id = $websiteId;
    $location->name = $data['name'];
    $location->address = $data['address'];
    $location->order = $data['order'];
    $location->save();

    return $location;
  }

  /*
   * Update location
   *
   * @return response
   */
  public function updateLocation($data)
  {
    $location = Location::find($data['id']);
    $location->name = $data['name'];
    $location->address = $data['address'];
    $location->order = $data['order'];
    $location->save();

    return $location;
  }

  /*
   * Update location
   *
   * @return response
   */
  public function deleteLocation($locationId)
  {
    $location = Location::find($locationId);
    if($location->delete()) {
      return true;
    }
    return false;
  }

  /*
   * Delete multiple locations
   *
   * @return response
   */
  public function deleteLocations($locationIds = [])
  {
    Location::whereIn('id', $locationIds)->get()->each(function($location) {
      $location->delete();
    });
    
    return true;
  }

  /*
   * Save venue page data
   *
   * @return response
   */	
	public function savePageData($data)
	{
		$this->saveLocations($data);
	}

  /*
   * Save locations
   *
   * @return response
   */
  public function saveLocations($data)
  {
    $websiteId = $data['websiteId'];
    $locations = $data['locations'];

    $existingLocationsId = $this->getAllLocationIds($websiteId);

    $locationIds = [];
    for($i=0; $i<count($locations); $i++) {
      $locationData = $locations[$i];
      $locationData['order'] = $i + 1;
      if($locationData['id'] == '') {
        $location = $this->insertLocation($websiteId, $locationData);
      } else {
        $location = $this->updateLocation($locationData);
      }
      $locationIds[] = $location->id;
    }

    $deleteLocationsId = array_diff($existingLocationsId, $locationIds);

    $this->deleteLocations($deleteLocationsId);
  }
}
