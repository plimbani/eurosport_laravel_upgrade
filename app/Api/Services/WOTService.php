<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\WOTContract;
use Laraspace\Api\Repositories\WOTRepository;

class WOTService implements WOTContract
{
    /**
     * @var WOTRepository
     */
    protected $wotRepo;

    /**
     * Create a new controller instance.
     *
     * @param WOTRepository $wotRepo
     */
    public function __construct()
    {
        $this->wotRepo = new \Laraspace\Api\Repositories\WOTRepository();
    }

    /*
    * Get locations
    *
    * @return response
    */
    public function getWebsiteId($request)
    {
        $data = $this->wotRepo->getWebsiteId($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }
    
    /*
    * Get locations
    *
    * @return response
    */
    public function getLocations($request)
    {
        $data = $this->wotRepo->getAllLocations($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get markers
    *
    * @return response
    */
    public function getMarkers($request)
    {
        $data = $this->wotRepo->getAllMarkers($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get contact
    *
    * @return response
    */
    public function getContact($request)
    {
        $data = $this->wotRepo->getContact($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get Accommodation
    *
    * @return response
    */
    public function getAccommodation($request)
    {
        $data = $this->wotRepo->getAccommodation($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get getVisitors
    *
    * @return response
    */
    public function getVisitors($request)
    {
        $data = $this->wotRepo->getVisitors($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get getTouristInformation
    *
    * @return response
    */
    public function getTouristInformation($request)
    {
        $data = $this->wotRepo->getTouristInformation($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get getTips
    *
    * @return response
    */
    public function getTips($request)
    {
        $data = $this->wotRepo->getTips($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get getProgram
    *
    * @return response
    */
    public function getProgram($request)
    {
        $data = $this->wotRepo->getProgram($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get getSponsors
    *
    * @return response
    */
    public function getSponsors($request)
    {
        $data = $this->wotRepo->getSponsors($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }

    /*
    * Get getOrganiser
    *
    * @return response
    */
    public function getOrganiser($request)
    {
        $data = $this->wotRepo->getOrganiser($request);

        return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
    }
}