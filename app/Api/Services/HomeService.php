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

  /*
   * Save page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $data = $this->homeRepo->savePageData($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function getPageData($websiteId)
  {
    $data = $this->homeRepo->getPageData($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
