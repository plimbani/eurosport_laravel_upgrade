<?php

namespace Laraspace\Api\Services;

use DB;
use Laraspace\Api\Contracts\MatchContract;
use Validator;
use Laraspace\Model\Role;

class MatchService implements MatchContract
{
    public function __construct()
    {
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
    }

    public function getAllMatches()
    {
        return $this->matchRepoObj->getAllMatches();
    }

    /**
     * create New Match.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function createMatch($data)
    {
        $data = $data->all();
        $data = $this->matchRepoObj->createMatch($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Match.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        $data = $this->matchRepoObj->edit($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Match.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function deleteMatch($deleteId)
    {
        $matchRes = $this->matchRepoObj->getMatchFromId($deleteId)->delete();
        if ($matchRes) {
            return ['code' => '200', 'message' => 'Match Sucessfully Deleted'];
        }
    }
    /**
     * Get Draws Details For Competation.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getDraws($data)
    {
        $tournamentId = $data['tournamentId'];

        $matchResData = $this->matchRepoObj->getDraws($tournamentId);

        if ($matchResData) {
            return ['status_code' => '200', 'data' => $matchResData,'message' => 'Draw data'];
        }
    }
    /**
     * Get Fixtures Details For Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getFixtures($data)
    {
        $data = $data->all();

        // $fixtureResData = $this->matchRepoObj->getFixtures($data['tournamentData']);
        $fixtureResData = $this->matchRepoObj->getTempFixtures($data['tournamentData']);

        if ($fixtureResData) {
            return ['status_code' => '200', 'data' => $fixtureResData,'message' => 'Match Fixture data'];
        }
    }
    /**
     * Get Standing  Details For Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getStanding($data)
    {
        $data = $data->all();

        $standingResData = $this->matchRepoObj->getStanding($data['tournamentData']);

        if ($standingResData) {
            return ['status_code' => '200', 'data' => $standingResData,'message' => 'Match Standing data'];
        }
    }
    /**
     * Get Draw Table  Details For Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getDrawTable($data)
    {
       $data = $data->all();
        
       $drawTableResData = $this->matchRepoObj->getDrawTable($data['tournamentData']);

        if (is_array($drawTableResData)) {
            return ['status_code' => '200', 'data' => $drawTableResData,'message' => 'Match Draw data'];
        } else {
            return ['status_code' => '300', 'message' => $drawTableResData];
        }
    }
    
    
}
