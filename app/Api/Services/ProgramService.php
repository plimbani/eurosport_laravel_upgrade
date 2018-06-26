<?php

namespace Laraspace\Api\Services;

use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Api\Contracts\ProgramContract;
use Laraspace\Api\Repositories\ProgramRepository;

class ProgramService implements ProgramContract
{
  /**
   * @var ProgramRepository
   */
  protected $programRepo;

  /**
   * Create a new controller instance.
   *
   * @param ProgramRepository $programRepo
   */
  public function __construct(ProgramRepository $programRepo)
  {
    $this->programRepo = $programRepo;
  }

  /**
   * Get all itineraries
   *
   * @param ProgramRepository $programRepo
   */
  public function getItineraries($websiteId)
  {
    $data = $this->programRepo->getAllItineraries($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /**
   * Save program page data
   *
   * @param ProgramRepository $programRepo
   */
  public function saveProgramPageData($data)
  {
    $data = $this->programRepo->saveProgramPageData($data);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /**
   * Get program page data
   *
   * @param ProgramRepository $programRepo
   */
  public function getProgramPageData($websiteId)
  {
    $data = $this->programRepo->getProgramPageData($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}