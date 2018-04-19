<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\VisitorContract;
use Laraspace\Api\Repositories\VisitorRepository;

class VisitorService implements VisitorContract
{
  /**
   * @var VisitorRepository
   */
  protected $visitorRepo;

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
   * @param VisitorRepository $visitorRepo
   */
  public function __construct(VisitorRepository $visitorRepo)
  {
    $this->visitorRepo = $visitorRepo;
  }

  /*
   * Save page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $data = $this->visitorRepo->savePageData($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function getPageData($websiteId)
  {
    $data = $this->visitorRepo->getPageData($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
