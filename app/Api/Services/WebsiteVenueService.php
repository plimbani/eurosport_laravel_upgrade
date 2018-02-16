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
  public function saveVenuePageData($data) 
  {
    $data = $this->venueRepo->saveVenuePageData($data);
      
    return ['data' => $data, 'status_code' => '200', 'message' => 'Data Sucessfully Inserted'];   
  }

   /*
     * Get All Tournaments
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index($tournamentId)
    {
        // Here we send Status Code and Messages        
        $data = $this->venueRepoObj->getAllVenues($tournamentId);

        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => self::ERROR_MSG];
    }

   
    /**
     * create New Tournament.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function create($data)
    {
        $data = $data->all();
        $data = $this->venueRepoObj->create($data['tournamentData']);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
             'data'=>$data];
        }
    }
    
    /**
     * Edit Venue.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        
        $data = $this->venueRepoObj->edit($data);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
             'data'=>$data];
        }
    }

    /**
     * Delete Tournament.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function delete($data)
    {
        $data = $data->all();
        $data = $this->venueRepoObj->delete($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
}
