<?php

namespace App\Api\Services;

use App\Api\Contracts\TeamContract;
use App\Api\Repositories\TeamRepository;

class TeamService implements TeamContract
{
    public function __construct(TeamRepository $teamRepoObj)
    {
        $this->teamRepoObj = $teamRepoObj;
    }

    public function getAllTeams()
    {
        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getAllTeams();
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function createTeam($data)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->createTeam($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Match.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data,$id)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->edit($data,$id);
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
    public function deleteTeam($deleteId)
    {
        $matchRes = $this->teamRepoObj->getTeamFromId($deleteId)->delete();
        if ($matchRes) {
            return ['code' => '200', 'message' => 'Team has been deleted sucessfully'];
        }
    }
}
