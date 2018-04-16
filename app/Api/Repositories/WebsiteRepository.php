<?php

namespace Laraspace\Api\Repositories;

use DB;
use Config;
use Laraspace\Models\Page;
use Laraspace\Models\Contact;
use Laraspace\Models\Sponsor;
use Laraspace\Models\Website;
use Laraspace\Custom\Helper\Image;
use Laraspace\Custom\Helper\Common;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Api\Services\PageService;
use Illuminate\Support\Facades\Storage;

class WebsiteRepository
{
  use AuthUserDetail;

  /**
   * @var Tournament logo
   */
  protected $tournamentLogo;

  /**
   * @var Social sharing graphic image
   */
  protected $socialSharingGraphicImage;

  /**
   * @var Page service
   */
  protected $pageService;

  /**
   * @var AWS URL
   */
  protected $getAWSUrl;

  /**
   * @var Sponsor logo image
   */
  protected $sponsorLogoPath;

  /**
   * @var disk
   */
  protected $disk;

  /**
   * @var diskName
   */
  protected $diskName;

  /**
   * @var conversions
   */
  protected $sponsorLogoConversions;

  /**
   * @var Tournament logo conversions
   */
  protected $websiteTournamentLogoConversions;

  /**
   * @var Tournament logo path
   */
  protected $websiteTournamentLogoPath;

  /**
   * @var Social sharing graphic image path
   */
  protected $socialSharingGraphicImagePath;

	/**
   * Create a new controller instance.
   */
	public function __construct(PageService $pageService)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->tournamentLogo =  getenv('S3_URL').'/assets/img/website_tournament_logo/';
    $this->socialSharingGraphicImage = getenv('S3_URL').'/assets/img/social_sharing_graphic/';
    $this->pageService = $pageService;
    $this->sponsorLogoPath = config('wot.imagePath.sponsor_logo');
    $this->diskName = config('filesystems.disks.s3.driver');
    $this->disk = Storage::disk($this->diskName);
    $this->sponsorLogoConversions = config('image-conversion.conversions.sponsor_logo');
    $this->websiteTournamentLogoConversions = config('image-conversion.conversions.website_tournament_logo');
    $this->websiteTournamentLogoPath = config('wot.imagePath.website_tournament_logo');
    $this->socialSharingGraphicImagePath = config('wot.imagePath.social_sharing_graphic');
  }

  /**
   * Destroy instance.
   *
   * @return void
   */
  public function __destruct()
  {
    unset($this->getAWSUrl);
    unset($this->tournamentLogo);
    unset($this->socialSharingGraphicImage);
    unset($this->pageService);
    unset($this->diskName);
    unset($this->disk);
    unset($this->sponsorLogoPath);
    unset($this->sponsorLogoConversions);
    unset($this->websiteTournamentLogoConversions);
    unset($this->websiteTournamentLogoPath);
    unset($this->socialSharingGraphicImagePath);
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
  	$websites = Website::with('pages')->get();

  	if($user) {
      $websites = $user->load(['websites', 'websites.pages'])->websites;
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
    $loggedInUser = $this->getCurrentLoggedInUserDetail();

    if(isset($data['websiteId']) && $data['websiteId'] != null){
      $websiteId = $data['websiteId'];
      $website = Website::find($websiteId);
      $data['isExistingWebsite'] = true;
    } else {
      $website = new Website();
      $data['isExistingWebsite'] = false;
    }

    // Allowed access to particular user roles
    if($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator') || $loggedInUser->hasRole('Internal.administrator')) {
      $website->tournament_name = $data['tournament_name'];
      $website->tournament_dates = $data['tournament_date'];
      $website->tournament_location = $data['tournament_location'];
      $website->domain_name = $data['domain_name'];
      $website->linked_tournament = $data['linked_tournament'];
      $website->google_analytics_id = $data['google_analytics_id'];
      $website->is_website_offline = $data['is_website_offline'];
      $website->offline_redirect_url = $data['is_website_offline'] == 1 ? Common::addSchemeToUrl($data['offline_redirect_url']) : null;
      
      if($data['isExistingWebsite'] == true) {
        if ($website->tournament_logo != '' && $website->tournament_logo != NULL && $website->tournament_logo != $data['tournament_logo']) {
          if ($this->disk->exists($this->websiteTournamentLogoPath . $website->tournament_logo)) {
            $this->disk->delete($this->websiteTournamentLogoPath . $website->tournament_logo);
            foreach ($this->websiteTournamentLogoConversions as $key => $value) {
              if ($this->disk->exists($this->websiteTournamentLogoPath . $key . '/' . $website->tournament_logo)) {
                $this->disk->delete($this->websiteTournamentLogoPath . $key . '/' . $website->tournament_logo);
              }
            }
          }
        }
      }
      $website->tournament_logo = ($data['tournament_logo'] != '') ? $data['tournament_logo'] : NULL;
    }
    
    if($data['isExistingWebsite'] == true) {
      if ($website->social_sharing_graphic != '' && $website->social_sharing_graphic != NULL && $website->social_sharing_graphic != $data['social_sharing_graphic']) {
        if ($this->disk->exists($this->socialSharingGraphicImagePath . $website->social_sharing_graphic)) {
          $this->disk->delete($this->socialSharingGraphicImagePath . $website->social_sharing_graphic);
        }
      }
    }
    $website->social_sharing_graphic = ($data['social_sharing_graphic'] != '') ? $data['social_sharing_graphic'] : NULL;
    $website->color = $data['color'];
    $website->font = $data['font'];

    $currentLoggedInUserId = $loggedInUser->id;
    if($data['isExistingWebsite'] == false){
      $website->created_by = $currentLoggedInUserId;
      $website->save();
    } else {
      if($website->isDirty()) {
        $website->updated_by = $currentLoggedInUserId;
        $website->save();
      }
    }

    $data['websiteId'] = $website->id;

    // Allowed access to particular user roles
    if($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator') || $loggedInUser->hasRole('Internal.administrator')) {
      $this->saveWebsitePageDetail($data);
    }

    $this->saveContactDetail($currentLoggedInUserId, $data);

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
      $sponsorData['logo'] = basename(parse_url($sponsorData['logo'])['path']);
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($sponsorData['id'] == '') {
        $sponsor = $this->insertSponsor($websiteId, $currentLoggedInUserId, $sponsorData);
      } else {
        $sponsor = $this->updateSponsor($currentLoggedInUserId, $sponsorData);
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
    $websiteData = Website::with('pages')->where('id', $websiteId)->first();

    $websiteData->pageTreeArray = Page::buildPageTree($websiteData->pages->toArray());

    if($websiteData->tournament_logo != null) {
      $websiteData->tournament_logo = $websiteData->tournamentLogo('thumbnail');
    }

    if($websiteData->social_sharing_graphic != null) {
      $websiteData->social_sharing_graphic = $this->socialSharingGraphicImage . $websiteData->social_sharing_graphic;
    }

    return $websiteData;
  }

  /*
   * Get website customisation options
   *
   * @return response
   */
  public function getWebsiteCustomisationOptions() {
    return config('wot.website_customisation_options');
  }

  /*
   * Get website default pages
   *
   * @return response
   */
  public function getWebsiteDefaultPages()
  {
    return config('wot.website_default_pages');
  }

  /*
   * Save page detail page
   *
   * @return response
   */
  public function saveWebsitePageDetail($data)
  {
    $pages = $data['pages'];
    $websiteId = $data['websiteId'];
    $isExistingWebsite = $data['isExistingWebsite'];

    $this->processPageTree($pages, $websiteId, $isExistingWebsite);
  }

  /*
   * Process page tree
   *
   * @return response
   */
  public function processPageTree(array $pages, $websiteId, $isExistingWebsite, $parent = null)
  {
    foreach($pages as $pageDetail) {
      $pageDetail['parent_id'] = $parent;
      $pageObj = null;
      if($isExistingWebsite) {
        $pageObj = $this->pageService->updatePageDetails($pageDetail, $websiteId);
      } else {
        $pageObj = $this->pageService->insertPageDetails($pageDetail, $websiteId);
      }
      if(isset($pageDetail['children'])) {
        $this->processPageTree($pageDetail['children'], $websiteId, $isExistingWebsite, $pageObj->id);
      }
    }
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
   * Insert sponsor
   *
   * @return response
   */
  public function insertSponsor($websiteId, $currentLoggedInUserId, $data)
  {
    $sponsor = new Sponsor();
    $sponsor->website_id = $websiteId;
    $sponsor->name = $data['name'];
    $sponsor->order = $data['order'];
    $sponsor->logo = $data['logo'];
    $sponsor->website = $data['website'];
    $sponsor->created_by = $currentLoggedInUserId;
    $sponsor->save();

    return $sponsor;
  }

  /*
   * Update sponsor
   *
   * @return response
   */
  public function updateSponsor($currentLoggedInUserId, $data)
  {
    $sponsor = Sponsor::find($data['id']);
    $sponsor->name = $data['name'];
    $sponsor->order = $data['order'];
    $sponsor->logo = $data['logo'];
    $sponsor->website = $data['website'];
    if($sponsor->isDirty()) {
      $sponsor->updated_by = $currentLoggedInUserId;
      $sponsor->save();
    }

    return $sponsor;
  }

  /*
   * Delete multiple organisers
   *
   * @return response
   */
  public function deleteSponsors($sponsorIds = [])
  {
    Sponsor::whereIn('id', $sponsorIds)->get()->each(function($sponsor) {
      if ($this->disk->exists($this->sponsorLogoPath . $sponsor->logo)) {
        $this->disk->delete($this->sponsorLogoPath . $sponsor->logo);
        foreach ($this->sponsorLogoConversions as $key => $value) {
          if ($this->disk->exists($this->sponsorLogoPath . $key . '/' . $sponsor->logo)) {
            $this->disk->delete($this->sponsorLogoPath . $key . '/' . $sponsor->logo);
          }
        }
      }
      $sponsor->delete();
    });

    return true;
  }

  /*
   * Save contact details
   *
   * @return response
   */
  public function saveContactDetail($currentLoggedInUserId, $data)
  {
    if($data['isExistingWebsite'] === false) {
      $contact = new Contact();
      $contact->website_id = $data['websiteId'];
      $contact->created_by = $currentLoggedInUserId;
      $contact->save();
    }
  }

  /*
   * Get website details
   *
   * @return response
   */
  public function getWebsiteDetails($websiteId)
  {
    $websiteDetails = Website::with('pages')->where('id', $websiteId)->first();

    return $websiteDetails;
  }
}
