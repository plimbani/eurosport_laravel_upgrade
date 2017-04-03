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
    public function getTeams($tournamentId,$ageGroup)
    {
        // Here we send Status Code and Messages
        if($ageGroup!= ""){
            $data = $this->teamRepoObj->getAll($tournamentId,$ageGroup);
        }else{
            $data = $this->teamRepoObj->getAllFromTournamentId($tournamentId);
        }
        // dd($data);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function getAllTournamentTeams($data)
    {

      // Here we send Status Code and Messages
        $data = $data->all();
        $data = $this->teamRepoObj->getAllTournamentTeams($data['tournamentData']['tournamentId']);
        // dd($data);
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
        $data['country_id'] = $this->getCountryIdFromName($data['country']);
        // dd($data);
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

    public function assignTeams($data)
    {
        // dd($data);
        foreach ($data['data']['teamdata'] as $key => $value) {
            // dd($value);
            $team_id = str_replace('sel_', '', $value['name']);
            // $team_id = str_replace('sel_', '', $value['value']);
            $this->teamRepoObj->assignGroup($team_id,$value['value']);
            # code...
        }
        return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
    }

    /**
     * Delete Team.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function deleteFromTournament($tournamentId,$ageGroup) {
        return  $this->teamRepoObj->deleteFromTournament($tournamentId,$ageGroup);
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
