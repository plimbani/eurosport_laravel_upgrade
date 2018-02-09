<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\HomeContract;
use Laraspace\Api\Repositories\HomeRepository;

class HomeService implements HomeContract
{
  /**
   * @var HomeRepository
   */
  protected $homeRepo;

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
   * @param HomeRepository $homeRepo
   */
  public function __construct(HomeRepository $homeRepo)
  {
    $this->homeRepo = $homeRepo;
  }

  /*
   * Get statistics
   *
   * @return response
   */
  public function getStatistics($websiteId)
  {
    $data = $this->homeRepo->getAllStatistics($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Store statistic
   *
   * @return response
   */
  public function storeStatistic($websiteId, $data)
  {
    $result = $this->homeRepo->insertStatistic($websiteId, $data);
    
    return ['data' => $result, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
  }

  /*
   * Update statistic
   *
   * @return response
   */
  public function updateStatistic($data)
  {
    $result = $this->homeRepo->updateStatistic($data);
    
    return ['data' => $result, 'status_code' => '200', 'message' => 'Data Sucessfully Updated'];
  }

  /*
   * Delete statistic
   *
   * @return response
   */
  public function deleteStatistic($websiteId)
  {
    $result = $this->homeRepo->deleteStatistic($websiteId);
    
    return ['data' => $result, 'status_code' => '200', 'message' => 'Data Sucessfully Deleted'];
  }

  /*
   * Get organisers
   *
   * @return response
   */
  public function getOrganisers($websiteId)
  {
    $data = $this->homeRepo->getAllOrganisers($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  public function savePageData($data)
  {
    $data = $this->homeRepo->savePageData($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
