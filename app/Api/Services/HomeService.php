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
}
