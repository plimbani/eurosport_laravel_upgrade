<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\TeamContract;
use Laraspace\Api\Repositories\TeamRepository;

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
    public function getTeams($tournamentId)
    {
        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getAll($tournamentId);
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
    public function getCountryIdFromName($countryName) {
        $cid = \DB::table('countries')->where('name', $countryName)->select('id')->first();
        return $cid->id;
        
        // return 1;
    }
    public function create($data)
    {
        // dd($data->tournamentId);
       

        // $data = $data->all();
        $data['country_id'] = $this->getCountryIdFromName($data['country']);

        $data = $this->teamRepoObj->create($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Team.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->edit($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Team.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function deleteFromTournament($tournamentId) {
        return  $this->teamRepoObj->deleteFromTournament($tournamentId);
    }
    public function delete($data)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->delete($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
}
