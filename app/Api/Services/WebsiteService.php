<?php

namespace Laraspace\Api\Services;

use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Api\Contracts\WebsiteContract;
use Laraspace\Api\Repositories\WebsiteRepository;

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

  // we can call same below function from Tournament service
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

      return $this->uploadImage($imagePath, $imageString);

    } else {
      return '';
    }
  }

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

      return $this->uploadImage($imagePath, $imageString);
    } else {
      return '';
    }
  }

  public function websiteSummary($data)
  {
    $data = $data->all();
    $websiteData = $this->websiteRepo->websiteSummary($data['websiteId']);
    if ($websiteData) {
        return ['status_code' => '200', 'data'=>$websiteData];
    }
  }

  public function uploadImage($imagePath, $imageString)
  {
    $s3 = \Storage::disk('s3');
    $img = explode(',', $imageString);
    if(count($img)>1) {
      $imgData = base64_decode($img[1]);
    }else{
      return '';
    }

    $timeStamp = md5(microtime(true) . rand(10,99));

    $path = $imagePath.$timeStamp.'.png';
    $s3->put($path, $imgData);

    return $timeStamp.'.png';
  }
}
