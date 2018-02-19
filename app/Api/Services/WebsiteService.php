<?php

namespace Laraspace\Api\Services;

use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Api\Contracts\WebsiteContract;
use Laraspace\Api\Repositories\WebsiteRepository;
use Laraspace\Custom\Helper\Image;

class WebsiteService implements WebsiteContract
{
  /**
   * @var WebsiteRepository
   */
  protected $websiteRepo;
  
	/**
   *  Success message
   */
  const SUCCESS_MSG = 'Data Sucessfully inserted';

  /**
   *  Error message
   */
  const ERROR_MSG = 'Error in Data';

  /**
   * Create a new controller instance.
   *
   * @param WebsiteRepository $websiteRepo
   */
  public function __construct(WebsiteRepository $websiteRepo)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->websiteRepo = $websiteRepo;
  }

   /*
   * Get All Websites
   *
   * @return response
   */
  public function index()
  {
    $data = $this->websiteRepo->getAll();
    if ($data) {
        return ['status_code' => '200', 'data' => $data];
    }

    return ['status_code' => '505', 'message' => self::ERROR_MSG];
  }

  /*
   * Get user accessible websites
   *
   * @return response
   */
  public function getUserAccessibleWebsites()
  {
  	$token = JWTAuth::getToken();
    $user = null;
    if($token)
    {
      $authUser = JWTAuth::parseToken()->toUser();
      $userObj = User::find($authUser->id);
      if($authUser && $userObj->hasRole('tournament.administrator')) {
        $user = $userObj;
      }
    }
    $data = $this->websiteRepo->getUserAccessibleWebsites($user);

    if ($data) {
      return ['status_code' => '200', 'data' => $data];
    }

    return ['status_code' => '500', 'message' => self::ERROR_MSG];
  }

  /*
   * Save website data
   *
   * @return response
   */
  public function saveWebsiteData($data) {
    $data['websiteData']['tournament_logo'] = $this->saveTournamentLogo($data);
    $data['websiteData']['social_sharing_graphic'] = $this->saveSocialSharingGraphicImage($data);
    $data = $this->websiteRepo->saveWebsiteData($data['websiteData']);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
  }

  /*
   * Save tournament logo
   *
   * @return response
   */
  private function saveTournamentLogo($data)
  {
    if($data['websiteData']['tournament_logo'] != '') {
      if(strpos($data['websiteData']['tournament_logo'],$this->getAWSUrl) !==  false) {
        $path = $this->getAWSUrl.'/assets/img/website_tournament_logo/';
        $imageLogo = str_replace($path,"",$data['websiteData']['tournament_logo']);
        return $imageLogo;
      }

      $imagePath = '/assets/img/website_tournament_logo/';
      $imageString = $data['websiteData']['tournament_logo'];

      return Image::uploadImage($imagePath, $imageString);

    } else {
      return '';
    }
  }

  /*
   * Save social sharing graphic image
   *
   * @return response
   */
  private function saveSocialSharingGraphicImage($data)
  {
    if($data['websiteData']['social_sharing_graphic'] != '') {
      if(strpos($data['websiteData']['social_sharing_graphic'],$this->getAWSUrl) !==  false) {
        $path = $this->getAWSUrl.'/assets/img/social_sharing_graphic/';
        $imageLogo = str_replace($path,"",$data['websiteData']['social_sharing_graphic']);
        return $imageLogo;
      }

      $imagePath = '/assets/img/social_sharing_graphic/';
      $imageString = $data['websiteData']['social_sharing_graphic'];

      return Image::uploadImage($imagePath, $imageString);
    } else {
      return '';
    }
  }

  /*
   * Get website details
   *
   * @return response
   */
  public function websiteSummary($data)
  {
    $data = $data->all();
    $websiteData = $this->websiteRepo->websiteSummary($data['websiteId']);
    if ($websiteData) {
        return ['status_code' => '200', 'data'=>$websiteData];
    }
  }

  public function getWebsiteCustomisationOptions()
  {
    $allColours = $this->websiteRepo->getWebsiteCustomisationOptions();
    if ($allColours) {
        return ['status_code' => '200', 'data'=>$allColours];
    }
  }

  /*
   * Get image path
   *
   * @return response
   */
  public function getImagePath()
  {
    $awsUrl = $this->getAWSUrl;

    $imagePath = [
      'website_tournament_logo' => $awsUrl . '/assets/img/website_tournament_logo/',
      'social_sharing_graphic' => $awsUrl . '/assets/img/social_sharing_graphic/',
      'hero_image' => $awsUrl . '/assets/img/hero_image/',
      'welcome_image' => $awsUrl . '/assets/img/welcome_image/',
      'organiser_logo' => $awsUrl . '/assets/img/organiser/',
      'sponsor_logo' => $awsUrl . '/assets/img/sponsor/',
      'photo' => $awsUrl . '/assets/img/photo/',
      'document' => $awsUrl . '/assets/img/document/',
      'editor_image' => $awsUrl . '/assets/img/editor_image/',
    ];

    return $imagePath;
  }

  /*
   * Get image path
   *
   * @return response
   */
  public function getWebsiteDefaultPages()
  {
    $pageData = $this->websiteRepo->getWebsiteDefaultPages();
    if ($pageData) {
      return ['status_code' => '200', 'data' => $pageData];
    }
  }
  /*
   * Get sponsors
   *
   * @return response
   */
  public function getSponsors($websiteId)
  {
    $data = $this->websiteRepo->getAllSponsors($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
