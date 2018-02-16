<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\WebsiteVenueContract;
use Laraspace\Api\Repositories\WebsiteVenueRepository;

class WebsiteVenueService implements WebsiteVenueContract
{
  /**
   * @var VenueRepository
   */
  protected $venueRepo;

  /**
   * Create a new controller instance.
   *
   * @param StayRepository $stayRepo
   */
  public function __construct(WebsiteVenueRepository $venueRepo)
  {
    $this->venueRepo = $venueRepo;
  }

  /*
   * Save venue page data
   *
   * @return response
   */
  public function savePageData($data) 
  {
    $data = $this->venueRepo->savePageData($data);
      
    return ['data' => $data, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
  }
}
