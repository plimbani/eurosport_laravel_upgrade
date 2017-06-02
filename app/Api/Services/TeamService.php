<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\TeamContract;
use Laraspace\Api\Repositories\TeamRepository;
use Laraspace\Models\TournamentCompetationTemplates;


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
    public function getTeams($teamData)
    {
        $data = $teamData->toArray()['teamData'];
        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getAllFromFilter($data);

        // dd($data);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }


    public function getAllTournamentTeams($data)
    {

      // Here we send Status Code and Messages

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
        if($cid){
            return $cid->id;
        }else{
            return "error";
        }


        // return 1;
    }
    public function create($data)
    {
        if($data['country']!=''){

            $data['country_id'] = $this->getCountryIdFromName($data['country']) != 'error' ? $this->getCountryIdFromName($data['country']) : '1';
        }else{
            $data['country_id'] = '1';
        }
        $data['age_group_id'] = 0;
        $ageCategory = trim($data['event']) ;
        
        if($ageCategory!= ''){
            \Log::info($ageCategory);

            $ageCategory = str_replace(strstr($ageCategory, '/'),'',$ageCategory);
            
            $competitionData = TournamentCompetationTemplates::where('tournament_id', $data->tournamentData['tournamentId'])
                ->where('category_age',$ageCategory)
                ->first();

            if($competitionData){
               $data['age_group_id'] = $competitionData['id'];
            }

        }
        $teamData = $this->teamRepoObj->getTeambyTeamId($data['teamid']);
         \Log::info($teamData);
         if($data['age_group_id'] != 0){
            if(isset($teamData['id'])  ){

                 $editData =  [
                    'id' => $teamData['id'],
                    'name' => $data['team'],
                    'place' => $data['place'] ,
                    'country_id' => $data['country_id'],
                    'age_group_id' => $data['age_group_id']
                ];
                $data = $this->teamRepoObj->edit($editData);
            }else{

                 $data = $this->teamRepoObj->create($data);
            }
         }
        
         \Log::info($data);
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
        foreach ($data['data']['teamdata'] as $key => $value) {
            // dd($value);
            $team_id = str_replace('sel_', '', $value['name']);
            // $team_id = str_replace('sel_', '', $value['value']);
            $this->teamRepoObj->assignGroup($team_id,$value['value'],$data['data']);
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
