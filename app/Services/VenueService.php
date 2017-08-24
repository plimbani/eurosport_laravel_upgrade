<?php

namespace Laraspace\Services;

use Laraspace\Contracts\VenueContract;
use Laraspace\Repositories\VenueRepository;

class VenueTempService implements VenueContract
{
    public function __construct(VenueRepository $venueRepoObj)
    {
        $this->venueRepoObj = $venueRepoObj;
    }
        public function __construct()
    {
        $this->pitchRepoObj = new \Laraspace\Api\Repositories\PitchRepository();
        $this->pitchAvailableRepoObj = new \Laraspace\Api\Repositories\PitchAvailableRepository();
    }

    /*
     * Get All Venues
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index()
    {
        // Here we send Status Code and Messages
        $data = $this->venueRepoObj->getAll();
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    /**
     * create New Venues.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function create($data)
    {
        $data = $this->venueRepoObj->create($data);

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Venue.
     *
     * @param array $data
     * @param mixed $VenueId
     * @param mixed $venueId
     *
     * @return [type]
     */
    public function edit($data, $venueId)
    {
        $data = $data->all();
        $data = $this->venueRepoObj->edit($data, $venueId);

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    public function deleteTeam($deleteId)
    {
        $teamRes = $this->venueRepoObj->getTeamFromId($deleteId)->delete();
        if ($teamRes) {
            return ['code' => '200', 'message' => 'Venue has been deleted sucessfully'];
        }
    }
}
