<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\ContactContract;
use Laraspace\Api\Repositories\ContactRepository;

class ContactService implements ContactContract
{
  /**
   * @var ContactRepository
   */
  protected $contactRepo;

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
   * @param ContactRepository $contactRepo
   */
  public function __construct(ContactRepository $contactRepo)
  {
    $this->contactRepo = $contactRepo;
  }

  /*
   * Get contact details
   *
   * @return response
   */
  public function getContactDetails($websiteId)
  {
    $data = $this->contactRepo->getContactDetails($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Save contact details
   *
   * @return response
   */
  public function saveContactDetails($data)
  {
    $data = $this->contactRepo->saveContactDetails($data);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
