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
    $this->websiteTournamentRepo = $websiteTournamentRepo;
  }

  /*
   * Save website tournament data
   *
   * @return response
   */
  public function savePageData($data) 
  {
    $data = $this->websiteTournamentRepo->savePageData($data);
      
    return ['data' => $data, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];  	
  }

  /*
   * Get website tournament data
   *
   * @return response
   */
  public function getPageData($websiteId)
  {
    $data = $this->websiteTournamentRepo->getPageData($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Get get all history years
   *
   * @return response
   */
  public function getAllHistoryYears($websiteId)
  {
    $data = $this->websiteTournamentRepo->getAllHistoryYears($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}