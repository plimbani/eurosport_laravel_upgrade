<?php

namespace Laraspace\Services;

use Laraspace\Contracts\TournamentContract;
use Laraspace\Repositories\TournamentRepository;
use Laraspace\Services\VenueTempService as VenueService;

class TournamentService implements TournamentContract
{
    /**
     *  Messages To Display.
     */
    const SUCCESS_MSG = 'Data Sucessfully inserted';
    const ERROR_MSG = 'Error in Data';

    public function __construct(TournamentRepository $tournamentRepoObj, VenueService $venueObj)
    {
        $this->tournamentRepoObj = $tournamentRepoObj;
        $this->venueServiceObj = $venueObj;
    }

     /*
     * Get All Tournaments
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index()
    {
        // Here we send Status Code and Messages
        $data = $this->tournamentRepoObj->getAll();
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
        return  ['status_code' => '101', 'message' => 'test message'];
        //$tournamentData = array();
        //$tournamentVenueData = array();

        $tournamentdata = $this->tournamentRepoObj->create($data);
        // $venueData = $this->venueServiceObj->create($data);

        // Now Save Venue Details For Tournament

        // return ($tournamentdata ) ? ['status_code' => '200', 'message' => self::SUCCESS_MSG,'data' => $tournamentdata] : ['status_code' => '505', 'message' => self::ERROR_MSG];
    }

    /**
     * Edit Tournament.
     *
     * @param array $data
     * @param mixed $id
     * @param mixed $tournamentId
     *
     * @return [type]
     */
    public function edit($data, $tournamentId)
    {
        $data = $data->all();
        $data = $this->tournamentRepoObj->edit($data, $tournamentId);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function delete($deleteId)
    {
        $tournamentRes = $this->tournamentRepoObj->getTournamentFromId($deleteId)->delete();
        if ($tournamentRes) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }

    /**
     *  Get Tournament Data.
     *
     *  @param int $tournamentData
     * @param mixed $tournamentId
     *
     *  @return [type]
     */
    public function getAllData($tournamentId)
    {
        return $this->tournamentRepoObj->getTournamentFromId($tournamentId);
    }
}
