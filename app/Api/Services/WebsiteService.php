<?php

namespace Laraspace\Api\Services;

use Storage;
use JWTAuth;
use Config;
use Laraspace\Models\User;
use Laraspace\Api\Contracts\WebsiteContract;
use Laraspace\Api\Repositories\WebsiteRepository;
use Laraspace\Custom\Helper\Image;
use Intervention\Image\ImageManager;
use Laraspace\Jobs\ImageConversion;

class WebsiteService implements WebsiteContract
{
  /**
   * @var ImageManager
   */
  protected $images;
  /**
   * @var WebsiteRepository
   */
  protected $websiteRepo;

  /**
   * @var local temperary image destination
   */
  protected $tempImages;

  /**
   * @var predefined image path
   */
  protected $imagePath;

  /**
   * @var predefined conversion sizes
   */
  protected $conversions;
  
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
   * @param WebsiteRepository $websiteRepo
   */
  public function __construct(WebsiteRepository $websiteRepo)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->images = new ImageManager;
    $this->websiteRepo = $websiteRepo;
    $this->tempImages = Config::get('wot.tempImages');
    $this->imagePath = Config::get('wot.imagePath');
    $this->conversions = Config::get('image-conversion.conversions');
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

    $tournament_logo = $data['websiteData']['tournament_logo'];
    $data['websiteData']['tournament_logo'] = basename(parse_url($tournament_logo)['path']);
    // $data['websiteData']['tournament_logo'] = $this->saveTournamentLogo($data);
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
    return array_map(function($path){
      return $this->getAWSUrl.$path;
    }, $this->imagePath);
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

  /*
   * Save website tournament logo
   *
   * @return response
   */
  public function uploadTournamentLogo($request)
  {
    $filename = Image::createTempImage($request->image);
    $localpath = $this->tempImages.$filename;
    $s3path = $this->imagePath['website_tournament_logo'].$filename;

    // moving main image file to S3
    $disk = Storage::disk('s3');
    $disk->put($s3path, file_get_contents($localpath), 'public');

    ImageConversion::dispatch(
      $filename, 
      $this->tempImages, 
      $this->imagePath['website_tournament_logo'], 
      $this->conversions['website_tournament_logo']
    );

    return $this->getAWSUrl . $s3path;
  }

  /*
   * Save website social graphic
   *
   * @return response
   */
  public function uploadSocialGraphic($request)
  {
    $image = $request->image;
    $filename = md5(microtime(true) . rand(10,99)) . '.' . $image->getClientOriginalExtension();
    $s3path = $this->imagePath['social_sharing_graphic'].$filename;
    $disk = Storage::disk('s3');
    $disk->put($s3path, file_get_contents($image), 'public');

    return $this->getAWSUrl . $s3path;
  }

  /*
   * Save website social image
   *
   * @return response
   */
  public function uploadSponsorImage($request)
  {
    $filename = Image::createTempImage($request->image);
    $localpath = $this->tempImages.$filename;
    $s3path = $this->imagePath['sponsor_logo'].$filename;

    // moving main image file to S3
    $disk = Storage::disk('s3');
    $disk->put($s3path, file_get_contents($localpath), 'public');

    ImageConversion::dispatch(
      $filename, 
      $this->tempImages, 
      $this->imagePath['sponsor_logo'], 
      $this->conversions['sponsor_logo']
    );

    return $this->getAWSUrl . $s3path;
  }
}
