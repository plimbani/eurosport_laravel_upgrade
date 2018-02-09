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
    $this->tournamentLogo =  getenv('S3_URL').'/assets/img/website_tournament_logo/';
    $this->socialSharingGraphicImage = getenv('S3_URL').'/assets/img/social_sharing_graphic/';
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

  /*
   * Save website data
   *
   * @return response
   */
  public function saveWebsiteData($data) 
  {
    if(isset($data['websiteId']) && $data['websiteId'] != 0){
      $websiteId = $data['websiteId'];
      $website = Website::where('id', $websiteId)->first();
    } else {
      $website = new Website();
    }

    $website->tournament_name = $data['tournament_name'];
    $website->tournament_dates = $data['tournament_date'];
    $website->tournament_location = $data['tournament_location'];
    $website->domain_name = $data['domain_name'];
    $website->linked_tournament = $data['linked_tournament'];
    $website->google_analytics_id = $data['google_analytics_id'];
    $website->tournament_logo = ($data['tournament_logo'] != '') ? $data['tournament_logo'] : NULL;
    $website->social_sharing_graphic = ($data['social_sharing_graphic'] != '') ? $data['social_sharing_graphic'] : NULL;
    $website->primary_color = $data['primary_color'];
    $website->secondary_color = $data['secondary_color'];
    $website->heading_font = $data['heading_font'];
    $website->body_font = $data['body_font'];

    $website->save();

    return $website;
  }

  /*
   * Get website details
   *
   * @return response
   */
  public function websiteSummary($websiteId) {
    $websiteData = Website::find($websiteId);

    if($websiteData->tournament_logo != null) {
      $websiteData->tournament_logo = $this->tournamentLogo . $websiteData->tournament_logo;
    }

    if($websiteData->social_sharing_graphic != null) {
      $websiteData->social_sharing_graphic = $this->socialSharingGraphicImage . $websiteData->social_sharing_graphic;
    }    
    
    return $websiteData;
  }

  public function getWebsiteCustomisationOptions() {
    return config('wot.website_customisation_options');
  }
}
