<?php

namespace Laraspace\Api\Services;

use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Api\Contracts\WebsiteContract;
use Laraspace\Api\Repositories\WebsiteRepository;

class WebsiteService implements WebsiteContract
{
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
      $this->websiteRepo = $websiteRepo;
  }

   /*
   * Get All Websites
   *
   * @param  array $api_key,$state,$type
   * @return response
   */
  public function index()
  {
    $token=JWTAuth::getToken();
    $user = null;
    if($token)
    {
      $authUser = JWTAuth::parseToken()->toUser();
      $userObj = User::find($authUser->id);
      if($authUser && $userObj->hasRole('website.administrator')) {
        $user = $userObj;
      }
    }
    $data = $this->websiteRepo->getAll('', $user);

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
    $data = $this->websiteRepo->saveWebsiteData($data['websiteData']);
  }
}
