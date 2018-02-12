<?php

namespace Laraspace\Api\Services;

use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Api\Contracts\WebsiteTournamentContract;
use Laraspace\Api\Repositories\WebsiteTournamentRepository;

class WebsiteTournamentService implements WebsiteTournamentContract
{
  /**
   * @var WebsiteTournamentRepository
   */
  protected $websiteTournamentRepo;

  /**
   * Create a new controller instance.
   *
   * @param WebsiteTournamentRepository $websiteTournamentRepo
   */
  public function __construct(WebsiteTournamentRepository $websiteTournamentRepo)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->websiteTournamentRepo = $websiteTournamentRepo;
  }

  /*
   * Save website tournament data
   *
   * @return response
   */
  public function saveWebsiteTournamentPageData($data) 
  {
    $data = $this->websiteTournamentRepo->saveWebsiteTournamentPageData($data);
      
    return ['data' => $data, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];  	
  }

  /*
   * Get website tournament data
   *
   * @return response
   */
  public function getWebsiteTournamentPageData($websiteId)
  {
    $data = $this->websiteTournamentRepo->getWebsiteTournamentPageData($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}