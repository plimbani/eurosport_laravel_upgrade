<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\VenueContract;
use Laraspace\Traits\TournamentAccess;

class VenueService implements VenueContract
{
    use TournamentAccess;

    /**
     *  Messages To Display.
     */
    const SUCCESS_MSG = 'Data Sucessfully inserted';

    const ERROR_MSG = 'Error in Data';

    public function __construct()
    {
        $this->venueRepoObj = new \Laraspace\Api\Repositories\VenueRepository();
        // $this->venueRepoObj = new \Laraspace\Api\Repositories\PitchRepository();

    }

    /*
    * Get All Tournaments
    *
    * @param  array $api_key,$state,$type
    * @return response
    */
    public function index($tournamentId)
    {
        $isTournamentAccessible = $this->checkForTournamentAccess($tournamentId);
        if (! $isTournamentAccessible) {
            abort(403, 'Unauthorized action.');
        }
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
     * @param  mixed  $data
     * @return [type]
     */
    public function create($data)
    {
        $data = $data->all();
        $data = $this->venueRepoObj->create($data['tournamentData']);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
                'data' => $data];
        }
    }

    /**
     * Edit Venue.
     *
     * @param  array  $data
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();

        $data = $this->venueRepoObj->edit($data);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
                'data' => $data];
        }
    }

    /**
     * Delete Tournament.
     *
     * @param  array  $data
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
