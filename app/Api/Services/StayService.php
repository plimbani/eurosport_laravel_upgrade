<?php

namespace Laraspace\Api\Services;

use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Api\Contracts\StayContract;
use Laraspace\Api\Repositories\StayRepository;

class StayService implements StayContract
{
  /**
   * @var StayRepository
   */
  protected $stayRepo;

  /**
   * Create a new controller instance.
   *
   * @param StayRepository $stayRepo
   */
  public function __construct(StayRepository $stayRepo)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->stayRepo = $stayRepo;
  }

  /*
   * Save staypage data
   *
   * @return response
   */
  public function saveStayPageData($data) 
  {
    $data = $this->stayRepo->saveStayPageData($data);
      
    return ['data' => $data, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];  	
  }

  /*
   * Get staypage data
   *
   * @return response
   */
  public function getStayPageData($websiteId)
  {
    $data = $this->stayRepo->getStayPageData($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}