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
    $website = new Website();
    $website->tournament_name = $data['tournament_name'];
    $website->tournament_dates = $data['tournament_date'];
    $website->tournament_location = $data['tournament_location'];
    $website->domain_name = $data['domain_name'];
    $website->linked_tournament = $data['linked_tournament'];
    $website->google_analytics_id = $data['google_analytics_id'];
    $website->save();

    return $website;
  }
}
