<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Map;
use Laraspace\Models\Location;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Api\Services\PageService;


class WebsiteVenueRepository
{
  use AuthUserDetail;

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
  public function insertLocation($websiteId, $currentLoggedInUserId, $data)
  {
    $location = new Location();
    $location->website_id = $websiteId;
    $location->name = $data['name'];
    $location->address = $data['address'];
    $location->order = $data['order'];
    $location->created_by = $currentLoggedInUserId;
    $location->save();

    return $location;
  }

  /*
   * Update location
   *
   * @return response
   */
  public function updateLocation($currentLoggedInUserId, $data)
  {
    $location = Location::find($data['id']);
    $location->name = $data['name'];
    $location->address = $data['address'];
    $location->order = $data['order'];
    if($location->isDirty()) {
      $location->updated_by = $currentLoggedInUserId;
      $location->save();
    }

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
   * Get all markers
   *
   * @return response
   */
  public function getAllMarkers($websiteId)
  {
    $markers = Map::where('website_id', $websiteId)->get();
    return $markers;
  }

  /*
   * Get all marker ids
   *
   * @return response
   */
  public function getAllMarkerIds($websiteId)
  {
    $markerIds = Map::where('website_id', $websiteId)->pluck('id')->toArray();
    return $markerIds;
  }

  /*
   * Insert marker
   *
   * @return response
   */
  public function insertMarker($websiteId, $currentLoggedInUserId, $data)
  {
    $marker = new Map();
    $marker->website_id = $websiteId;
    $marker->latitude = $data['position']['lat'];
    $marker->longitude = $data['position']['lng'];
    $marker->information = $data['information'];
    $marker->created_by = $currentLoggedInUserId;
    $marker->save();

    return $marker;
  }

  /*
   * Update marker
   *
   * @return response
   */
  public function updateMarker($currentLoggedInUserId, $data)
  {
    $marker = Map::find($data['id']);
    $marker->latitude = $data['position']['lat'];
    $marker->longitude = $data['position']['lng'];
    $marker->information = $data['information'];
    if($marker->isDirty()) {
      $marker->updated_by = $currentLoggedInUserId;
      $marker->save();
    }

    return $marker;
  }

  /*
   * Delete multiple markers
   *
   * @return response
   */
  public function deleteMarkers($markerIds = [])
  {
    Map::whereIn('id', $markerIds)->get()->each(function($marker) {
      $marker->delete();
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
		$this->saveMarkers($data);
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
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($locationData['id'] == '') {
        $location = $this->insertLocation($websiteId, $currentLoggedInUserId, $locationData);
      } else {
        $location = $this->updateLocation($currentLoggedInUserId, $locationData);
      }
      $locationIds[] = $location->id;
    }

    $deleteLocationsId = array_diff($existingLocationsId, $locationIds);

    $this->deleteLocations($deleteLocationsId);
  }

  /*
   * Save markers
   *
   * @return response
   */
  public function saveMarkers($data)
  {
    $websiteId = $data['websiteId'];
    $markers = $data['markers'];

    $existingMarkersId = $this->getAllMarkerIds($websiteId);

    $markerIds = [];
    for($i=0; $i<count($markers); $i++) {
      $markerData = $markers[$i];
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($markerData['id'] == '') {
        $marker = $this->insertMarker($websiteId, $currentLoggedInUserId, $markerData);
      } else {
        $marker = $this->updateMarker($currentLoggedInUserId, $markerData);
      }
      $markerIds[] = $marker->id;
    }
    $deleteMarkersId = array_diff($existingMarkersId, $markerIds);
    $this->deleteMarkers($deleteMarkersId);
  }
}
