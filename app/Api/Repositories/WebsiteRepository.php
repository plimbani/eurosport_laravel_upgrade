<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Website;
use Laraspace\Models\Sponsor;
use Laraspace\Custom\Helper\Image;

class WebsiteRepository
{
  /**
   * @var AWS URL
   */
  protected $getAWSUrl;

	/**
   * Create a new controller instance.
   */
	public function __construct()
  {
    $this->getAWSUrl = getenv('S3_URL');
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
    $website = $this->saveWebsite($data);

    if(!isset($data['websiteId']) || empty($data['websiteId']))
    {
      $data['websiteId'] = $website->id;
    }
    
    $this->saveSponsors($data);
    return $website;
  }

  /*
   * Save website
   *
   * @return response
   */
  public function saveWebsite($data) 
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
   * Save sponsors
   *
   * @return response
   */
  public function saveSponsors($data) 
  {
    $websiteId = $data['websiteId'];
    $sponsors = $data['sponsors'];

    $existingSponsorsId = $this->getAllSponsorIds($websiteId);

    $sponsorIds = [];
    for($i=0; $i<count($sponsors); $i++) {
      $sponsorData = $sponsors[$i];
      $sponsorData['order'] = $i + 1;

      // Upload image
      $sponsorData['logo'] = $this->uploadSponsorLogo($sponsorData['logo']);

      if($sponsorData['id'] == '') {
        $sponsor = $this->insertSponsor($websiteId, $sponsorData);
      } else {
        $sponsor = $this->updateSponsor($sponsorData);
      }
      $sponsorIds[] = $sponsor->id;
    }

    $deleteSponsorsId = array_diff($existingSponsorsId, $sponsorIds);

    $this->deleteSponsors($deleteSponsorsId);
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

  /*
   * Get all sponsors
   *
   * @return response
   */
  public function getAllSponsors($websiteId)
  {
    $sponsors = Sponsor::where('website_id', $websiteId)->orderBy('order')->get();

    return $sponsors;
  }

  /*
   * Get all sponsors ids
   *
   * @return response
   */
  public function getAllSponsorIds($websiteId)
  {
    $sponsorIds = Sponsor::where('website_id', $websiteId)->pluck('id')->toArray();
    return $sponsorIds;
  }

  /*
   * Upload sponsor logo
   *
   * @return response
   */
  public function uploadSponsorLogo($logo)
  {
    if($logo != '') {
      if(strpos($logo, $this->getAWSUrl) !==  false) {
        $path = $this->getAWSUrl . '/assets/img/sponsor/';
        $imageLogo = str_replace($path, "", $logo);
        return $imageLogo;
      }

      $imagePath = '/assets/img/sponsor/';
      $imageString = $logo;

      return Image::uploadImage($imagePath, $imageString);
    }
  }

  /*
   * Insert sponsor
   *
   * @return response
   */
  public function insertSponsor($websiteId, $data)
  {
    $sponsor = new Sponsor();
    $sponsor->website_id = $websiteId;
    $sponsor->name = $data['name'];
    $sponsor->order = $data['order'];
    $sponsor->logo = $data['logo'];
    $sponsor->website = $data['website'];
    $sponsor->save();

    return $sponsor;
  }

  /*
   * Update sponsor
   *
   * @return response
   */
  public function updateSponsor($data)
  {
    $sponsor = Sponsor::find($data['id']);
    $sponsor->name = $data['name'];
    $sponsor->order = $data['order'];
    $sponsor->logo = $data['logo'];
    $sponsor->website = $data['website'];
    $sponsor->save();

    return $sponsor;
  }

  /*
   * Delete multiple organisers
   *
   * @return response
   */
  public function deleteSponsors($sponsorIds = [])
  {
    Sponsor::whereIn('id', $sponsorIds)->delete();
    return true;
  }
}
