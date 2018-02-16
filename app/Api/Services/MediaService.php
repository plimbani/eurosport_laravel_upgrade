<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\MediaContract;
use Laraspace\Api\Repositories\MediaRepository;

class MediaService implements MediaContract
{
  /**
   * @var MediaRepository
   */
  protected $mediaRepo;

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
   * @param MediaRepository $mediaRepo
   */
  public function __construct(MediaRepository $mediaRepo)
  {
    $this->mediaRepo = $mediaRepo;
  }

  /*
   * Get statistics
   *
   * @return response
   */
  public function getPhotos($websiteId)
  {
    $data = $this->mediaRepo->getAllPhotos($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Get organisers
   *
   * @return response
   */
  public function getDocuments($websiteId)
  {
    $data = $this->mediaRepo->getAllDocuments($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Save page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $data = $this->mediaRepo->savePageData($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
