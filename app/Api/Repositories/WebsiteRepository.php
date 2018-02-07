<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Website;

class WebsiteRepository
{
	/**
   * Create a new controller instance.
   */
	public function __construct()
  {
    
  }

  /*
   * Get all websites
   *
   * @return websites array
   */
  public function getAll()
  {
    $websites = Website::All();
    return $websites;
  }

  /*
   * Get user accessible websites
   *
   * @param User $user
   *
   * @return response
   */
  public function getUserAccessibleWebsites($user)
  {
  	$websites = Website::All();

  	if($user) {
      $websites = $user->websites;
    }

    return $websites;
  }

  public function saveWebsiteData($data) 
  {
    if(isset($data['websiteId']) && $data['websiteId'] != 0){
      $websiteId = $data['websiteId'];
      $websiteData = Website::where('id', $websiteId)->first();
      $websiteData->tournament_name = $data['tournament_name'];
      $websiteData->tournament_dates = $data['tournament_date'];
      $websiteData->tournament_location = $data['tournament_location'];
      $websiteData->domain_name = $data['domain_name'];
      $websiteData->linked_tournament = $data['linked_tournament'];
      $websiteData->google_analytics_id = $data['google_analytics_id'];
      $websiteData->tournament_logo = ($data['tournament_logo'] != '') ? getenv('S3_URL').'/assets/img/tournament_logo/'.$data['tournament_logo'] : NULL;
      $websiteData->save();
      return $websiteData;
    } else {
      $website = new Website();
      $website->tournament_name = $data['tournament_name'];
      $website->tournament_dates = $data['tournament_date'];
      $website->tournament_location = $data['tournament_location'];
      $website->domain_name = $data['domain_name'];
      $website->linked_tournament = $data['linked_tournament'];
      $website->google_analytics_id = $data['google_analytics_id'];
      $website->tournament_logo = ($data['tournament_logo'] != '') ? getenv('S3_URL').'/assets/img/tournament_logo/'.$data['tournament_logo'] : NULL;
      $website->save();

      return $website;
    }    
  }

  public function websiteSummary($websiteId) {
    $summaryData = array();

    $websiteData = Website::where('id', $websiteId)->first();
    $summaryData['tournament_name'] = $websiteData->tournament_name;
    $summaryData['tournament_dates'] = $websiteData->tournament_dates;
    $summaryData['tournament_location'] = $websiteData->tournament_location;
    $summaryData['domain_name'] = $websiteData->domain_name;
    $summaryData['linked_tournament'] = $websiteData->linked_tournament;
    $summaryData['google_analytics_id'] = $websiteData->google_analytics_id;

    return $websiteData;
  }
}
