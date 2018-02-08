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
    $id = ($data['websiteData']['websiteId'] !=0 || $data['websiteData']['websiteId'] !=0) ? $data['websiteData']['websiteId']:'';
    $data['websiteData']['tournament_logo']=$this->saveTournamentLogo($data, $id);
    $data = $this->websiteRepo->saveWebsiteData($data['websiteData']);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
  }

  // we can call same below function from Tournament service
  private function saveTournamentLogo($data, $id)
  {
    if($data['websiteData']['tournament_logo'] != '') {
      if(strpos($data['websiteData']['tournament_logo'],$this->getAWSUrl) !==  false) {
        $path = $this->getAWSUrl.'/assets/img/tournament_logo/';
        $imageLogo = str_replace($path,"",$data['websiteData']['tournament_logo']);
        return $imageLogo;
      }

      $s3 = \Storage::disk('s3');
      $imagePath = '/assets/img/tournament_logo/';
      $image_string = $data['websiteData']['tournament_logo'];
      $img = explode(',', $image_string);
      if(count($img)>1) {
          $imgData = base64_decode($img[1]);
      }else{
          return '';
      }

      if($id == '') {
        $now = new \DateTime();
        $timeStamp = $now->getTimestamp();
      } else {
        $timeStamp = $id;
      }

      $path = $imagePath.$timeStamp.'.png';
      $s3->put($path, $imgData);

      return $timeStamp.'.png';
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
}
