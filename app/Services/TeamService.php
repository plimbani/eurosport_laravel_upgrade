<?php

namespace Laraspace\Services;

use Laraspace\Contracts\TeamContract;
use Laraspace\Repositories\TeamRepository;

class TeamService implements TeamContract
{
    public function __construct(TeamRepository $teamRepoObj)
    {
        $this->teamRepoObj = $teamRepoObj;
    }

    /*
     * Get All Teams
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index()
    {
        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getAll();
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    /**
     * create New Team.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function create($data)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->create($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Team.
     *
     * @param array $data
     * @param mixed $teamId
     *
     * @return [type]
     */
    public function edit($data, $teamId)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->edit($data, $teamId);

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    public function delete($deleteId)
    {
        $teamRes = $this->teamRepoObj->getTeamFromId($deleteId)->delete();
        if ($teamRes) {
            return ['code' => '200', 'message' => 'Team has been deleted sucessfully'];
        }
    }
}
