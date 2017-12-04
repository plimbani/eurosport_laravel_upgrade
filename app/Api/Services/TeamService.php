<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\TeamContract;
use Laraspace\Api\Repositories\TeamRepository;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Club;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Competition;
use DB;



class TeamService implements TeamContract
{
    public function __construct(TeamRepository $teamRepoObj)
    {
        $this->teamRepoObj = $teamRepoObj;
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
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
        // dd($data);
        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getAllFromFilter($data);

        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function getClubs($tournamentData)
    {

        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getClubData($tournamentData['tournamentData']);
        // print_r($data);exit;
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function getClubTeams($tournamentData)
    {
        $data = $this->teamRepoObj->getTeamData($tournamentData['tournamentData']);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }
          return ['status_code' => '505', 'message' => 'Error in Data'];
    }


    public function getAllTournamentTeams($data)
    {

      // Here we send Status Code and Messages

        $data = $this->teamRepoObj->getAllTournamentTeams($data['tournamentData']['tournamentId']);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }
    public function getAllFromCompetitionId($data)
    {
      // dd($data);
        $data = $this->teamRepoObj->getAllFromCompetitionId($data['tournamentData']['competitionId']);
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
            $competitionData = TournamentCompetationTemplates::where('tournament_id', $data->tournamentData['tournamentId'])
                ->where('category_age',$ageCategory)
                ->first();
            if($competitionData){
               $data['age_group_id'] = $competitionData['id'];
            }
        }
        $teamData = $this->teamRepoObj->getTeambyTeamId($data['teamid'],$data->tournamentData['tournamentId']);
         \Log::info($teamData);
         if($data['club']!='')
         {
            // Here we first find the club name in Database
            $clubData1 = Club::where('name',$data['club'])->get();
            // Here we check if No data then create
            if(count($clubData1) == 0) {
                // Its New values
                $club_array = array('user_id'=>'1','name'=>$data['club']);
                $clubData = Club::create($club_array);
                $update = [
                  'tournament_id' =>  $data->tournamentData['tournamentId'],
                  'club_id' => $clubData->id,
                ];
                // print_r($update);exit;
                $data['club_id'] = $clubData->id;
                $clubData->tournament()->attach($update);
            }
            else {
                // here check if record is exist
               $club_id = $clubData1[0]->id;
               $tournament_id = $data->tournamentData['tournamentId'];
              $clubData2 = Club::whereHas('tournament', function($q) use ($club_id,$tournament_id) {
                  $q->where('tournament_id',$tournament_id);
                  $q->where('club_id',$club_id);
              })->exists();

              /*  Club::where('tournament_id','=',$data->tournamentData['tournamentId'])
                      ->where('club_id','=',$clubData1[0]->id)->exists(); */

                if(!$clubData2) {
                  // we have to insert the value in tournament id
                   $updateEd = [
                  'tournament_id' =>  $data->tournamentData['tournamentId'],
                  'club_id' => $club_id,
                ];
                  \DB::table('tournament_club')->insert($updateEd);
                  //$clubData1->tournament()->attach($updateEd);
                }

                $data['club_id'] = $club_id;
            }
         }

         if($data['age_group_id'] != 0){

            if(isset($teamData['id']) ){

                 $editData =  [
                    'id' => $teamData['id'],
                    'name' => $data['team'],
                    'place' => $data['place'],
                    'country_id' => $data['country_id'],
                    'club_id' => $data['club_id'],
                    'age_group_id' => $data['age_group_id']
                ];

                $data = $this->teamRepoObj->edit($editData, $teamData['id']);
            } else {
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

      // $this->UpdateMatches($data);
      $teamsData = $this->teamRepoObj->getAllUpdatedTeam($data);
      $teamsList = $this->teamRepoObj->getAllGroupTeam($teamsData);
      $tournamentId = $data['data']['tournament_id'];
      $ageGroupId  = $data['data']['age_group'];
       
      $teamData = $data['data']['teamdata'];

      // for group assignment validation
      $tempFixturesCount = TempFixture::where('tournament_id', $data['data']['tournament_id'])
                                  ->where('age_group_id', $data['data']['age_group'])
                                  ->where(function($query){
                                    $query->orWhereNotNull('hometeam_score')
                                          ->orWhereNotNull('awayteam_score');
                                  })
                                  ->get()
                                  ->count();

      if($tempFixturesCount > 0) { 
        
        $tournamentCompetationTemplatesTotalTeamsCount = TournamentCompetationTemplates::where('id', $data['data']['age_group'])->first();

        $finalTeamdata = [];
        foreach ($teamData as $key => $team) {
          if($team['value'] != '') {
            $finalTeamdata[] = $team;
          }
        } 

        if(count($finalTeamdata) != $tournamentCompetationTemplatesTotalTeamsCount->total_teams) {
          return ['status_code' => '422', 'message' => 'You need to assign all teams.'];
        }
        
        $this->teamRepoObj->updateMatches($teamsList,$teamsData,$data['data']);
      }

      $this->teamRepoObj->saveTeamManualRankingFromStandings($tournamentId, $ageGroupId, $teamsList);

      foreach ($teamData as $key => $value) {
          $team_id = str_replace('sel_', '', $value['name']);
          $this->teamRepoObj->assignGroup($team_id,$value['value'],$data['data'],$tempFixturesCount);
          # code...
      }
      $matchData = array('tournamentId'=>$tournamentId, 'ageGroupId'=>$ageGroupId);
      $matchresult =  $this->matchRepoObj->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);

      return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
    }
    
    public function getAllTeamsGroup($data)
    {
        foreach ($data['data']['teamdata'] as $key => $value) {
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
    public function getTeamsList($data)
    {
      // here first we get the key and based on that we listed out team details
      $data = $this->teamRepoObj->getTeamList($data);
      if ($data) {
            return ['status_code' => '200', 'data'=>$data,
            'message' => 'Data Successfully Deleted'];
        }
    }

    public function changeTeamName($data)
    {
      // here first we get the key and based on that we listed out team details
      $data = $this->teamRepoObj->changeTeamName($data['teamData']);
      if ($data) {
          return ['status_code' => '200', 'data'=>$data,
          'message' => 'Data Successfully Deleted'];
      }
    }

    public function getAllCompetitionTeamsFromFixture($data)
    {
        $data = $this->teamRepoObj->getAllCompetitionTeamsFromFixture($data['tournamentData']['competitionId']);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data']; 
    }

}
