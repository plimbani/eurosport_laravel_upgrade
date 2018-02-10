<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Api\Services\PageService;

class WebsiteRepository
{

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
   * Create a new controller instance.
   */
	public function __construct(PageService $pageService)
  {
    $this->tournamentLogo =  getenv('S3_URL').'/assets/img/website_tournament_logo/';
    $this->socialSharingGraphicImage = getenv('S3_URL').'/assets/img/social_sharing_graphic/';
    $this->pageService = $pageService;
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
    if(isset($data['websiteId']) && $data['websiteId'] != null){
      $websiteId = $data['websiteId'];
      $website = Website::find($websiteId);
      $data['isExistingWebsite'] = true;
    } else {
      $website = new Website();
      $data['isExistingWebsite'] = false;
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

    $data['websiteId'] = $website->id;

    $this->saveWebsitePageDetail($data);

    return $website;
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
      $websiteData->tournament_logo = $this->tournamentLogo . $websiteData->tournament_logo;
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
}
