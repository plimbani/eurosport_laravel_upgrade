<?php

namespace Laraspace\Api\Services;

use Laraspace\Models\Tournament;
use Laraspace\Api\Contracts\AgeGroupContract;
use Laraspace\Api\Repositories\AgeGroupRepository;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Team;
use Laraspace\Models\TempFixture;

class AgeGroupService implements AgeGroupContract
{
    public function __construct(AgeGroupRepository $ageRepoObj)
    {
        $this->ageGroupObj = $ageRepoObj;
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
    }

     /*
     * Get All AgeGroup
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index()
    {
        // Here we send Status Code and Messages
        $data = $this->ageGroupObj->getAll();
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    /*
     * Create Compeation Format
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function createCompetationFomat($data)
    {
        // Now here we set and Calculate and Save Data in
        //  tournament_competation_template Table
        $data = $data['compeationFormatData'];

        // Check if maximum team exceeds
        $totalCheckTeams = 0;

        $tournamentTotalTeamSumObj = TournamentCompetationTemplates::where('tournament_id', $data['tournament_id']);
        $maximumTeams = Tournament::find($data['tournament_id'])->maximum_teams;

        if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0){
          $tournamentTotalTeamSumObj = $tournamentTotalTeamSumObj->where('id', '!=' ,$data['competation_format_id']);
        }

        $tournamentTotalTeamSum = $tournamentTotalTeamSumObj->pluck('total_teams')->sum();
        $totalCheckTeams = $data['total_teams'] + $tournamentTotalTeamSum;

        if($maximumTeams == null) {
          return ['status_code' => '403', 'message' => 'Please add maximum teams limit on "Tournament details" page.'];
        }

        if(($totalCheckTeams > $maximumTeams)) {
          return ['status_code' => '403', 'message' => 'This category cannot be added as it exceeds the maximum teams set for this tournament.'];
        }

        // TODO: Here we set the value for Other Data
        // Impliclityly Add 2 For Multiplication
        if($data['game_duration_RR'] == 'other') {
          $data['game_duration_RR'] = $data['halves_RR'] * $data['game_duration_RR_other'];
        } else {
          $data['game_duration_RR'] = $data['halves_RR'] * $data['game_duration_RR'];
        }

        if($data['game_duration_FM'] == 'other') {
          $data['game_duration_FM'] = $data['halves_FM'] * $data['game_duration_FM_other'];
        } else {
          $data['game_duration_FM'] = $data['halves_FM'] * $data['game_duration_FM'];
        }

        if($data['match_interval_RR'] == 'other') {
          $data['match_interval_RR'] = $data['match_interval_RR_other'];
        }
        if($data['match_interval_FM'] == 'other') {
          $data['match_interval_FM'] = $data['match_interval_FM_other'];
        }
        // Todo : change For New Template
        $data['tournamentTemplate'] = $data['nwTemplate'];
        unset($data['nwTemplate']);
        if(is_int($data['tournamentTemplate'])){
          $nwdata = (array) $this->ageGroupObj->FindTemplate($data['tournamentTemplate']);
          $data['tournamentTemplate'] = $nwdata;
        }
        list($totalTime,$totalmatch,$dispFormatname) = $this->calculateTime($data);

        $data['total_time'] = $totalTime;
        $data['total_match'] = $totalmatch;
        $data['disp_format_name'] = $dispFormatname;

        if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0){

            $mininterval = TournamentCompetationTemplates::where('id', '=', $data['competation_format_id'])->first()->team_interval;
           
        }

        $id = $this->ageGroupObj->createCompeationFormat($data);


        // here we insert Groups in Competation Formats
        // First we check if its Edit or Update
        if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0)
        {
            // here we check if template data is changed if changed
            // delete all data and insert new one
            // TODO: Here we check if there is change then and then change Data
            if($data['tournament_template_id'] != $data['tournamentTemplate']['id'] ) {
                // Delete Competation Data
                $this->ageGroupObj->deleteCompetationData($data);
                // Delete temp_fixtures Data

                $id = $data['competation_format_id'];
                $this->addCompetationGroups($id,$data);

            } else {
             
              if($data['team_interval'] != $mininterval) {
               
              $teamsList = Team::where('age_group_id',$data['competation_format_id'])->pluck('id')->toArray();
 
                $tournamentId = $data['tournament_id'];
                $ageGroupId  = $data['competation_format_id'];

                $matchData = array('tournamentId'=>$tournamentId, 'ageGroupId'=>$ageGroupId);
                $matchresult =  $this->matchRepoObj->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);
                
              }
            }

        } else {
             $this->addCompetationGroups($id,$data);
        }


        //$competationData['tournament_competation_template_id'] = $data;
        //$competationData['tournament_id'] = $data['tournament_id'];
        //$competationData['name'] = $data['ageCategory_name'].'-'.$group_name;


        // Here also add in competation table data number of groups

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }
    private function addCompetationGroups($tournament_competation_template_id,
        $data){
        // Here we set data
       // $json_data = json_decode($jsonTemplateData);
        // Below are Fixed Data

        $competationData['tournament_competation_template_id'] = $tournament_competation_template_id;
        $competationData['tournament_id'] = $data['tournament_id'];
        $competationData['age_group_name'] = $data['ageCategory_name'].'-'.$data['category_age'];
        $categoryAge = $data['category_age'];
        $json_data = json_decode($data['tournamentTemplate']['json_data']);


        //$competationData['name'] = $data['ageCategory_name'].'-'.$group_name;
        $totalRound = count($json_data->tournament_competation_format->format_name);
        $group_name=array();
        $fixture_array = array();
        $fixture_match_detail_array = array();

        for($i=0;$i<$totalRound;$i++){
            // Now here we calculate followng fields
            $rounds = $json_data->tournament_competation_format->format_name[$i]->match_type;
            foreach($rounds as $key=>$round) {
                $val = $key.'-'.$i;
                $group_name[$val]['group_name']=$round->groups->group_name;

                if(isset($round->groups->actual_group_name)) {
                  $group_name[$val]['actual_group_name']=$round->groups->actual_group_name;
                } else {
                  $group_name[$val]['actual_group_name']=$round->groups->group_name;
                }
                
                $group_name[$val]['team_count']=$round->group_count;
                $group_name[$val]['match_type']=$round->name;

                if(isset($round->actual_name)) {
                  $group_name[$val]['actual_name'] = $round->actual_name;
                } else {
                  $group_name[$val]['actual_name'] = $round->name;
                }

                $group_name[$val]['comp_roundd']=$json_data->tournament_competation_format->format_name[$i]->name;
                // Now here For Loop for create Fixture array
                foreach($round->groups->match as $key1=>$matches) {
                    $newVal = $val.'|'.$group_name[$val]['group_name'].'|'.$key1;
                    $fixture_array[$newVal] = $matches->match_number;

                    $fixture_match_detail_array[$newVal] = [
                      'display_match_number' => (isset($matches->display_match_number) ? $matches->display_match_number : null),
                      'display_home_team_placeholder_name' => (isset($matches->display_home_team_placeholder_name) ? $matches->display_home_team_placeholder_name : null),
                      'display_away_team_placeholder_name' => (isset($matches->display_away_team_placeholder_name) ? $matches->display_away_team_placeholder_name : null),
                      'is_final_match' => (isset($matches->is_final_match) ? $matches->is_final_match : 0),
                      'position' => (isset($matches->position) ? $matches->position : null)
                    ];
                }

                if(isset($round->dependent_groups)) {
                  foreach($round->dependent_groups as $key=>$group) {
                    foreach($group->groups->match as $key1=>$matches) {
                      $newVal = $val.'|'.$group_name[$val]['group_name'].'|'.$key.$key1;
                      $fixture_array[$newVal] = $matches->match_number;
                      $fixture_match_detail_array[$newVal] = [
                        'display_match_number' => (isset($matches->display_match_number) ? $matches->display_match_number : null),
                        'display_home_team_placeholder_name' => (isset($matches->display_home_team_placeholder_name) ? $matches->display_home_team_placeholder_name : null),
                        'display_away_team_placeholder_name' => (isset($matches->display_away_team_placeholder_name) ? $matches->display_away_team_placeholder_name : null),
                        'is_final_match' => (isset($matches->is_final_match) ? $matches->is_final_match : 0),
                        'position' => (isset($matches->position) ? $matches->position : null)
                      ];
                    }
                  }
                }
            }
        }
        $competation_array = array();
        $competation_array=$this->ageGroupObj->addCompetations($competationData,$group_name);
        // Now here we insert Fixtures

        $this->ageGroupObj->addFixturesIntoTemp($fixture_array,$competation_array,$fixture_match_detail_array, $categoryAge);
        //exit;

    }
    private function calculateTime($data) {
        // We calculate the Following over here
        // Total Time
        // Total Match
        // display Format Name
        $json_data = json_decode($data['tournamentTemplate']['json_data']);

        // $disp_format_name = $json_data->tournament_teams .' TEAMS,'. $json_data->competation_format;
        $disp_format_name = $json_data->tournament_teams .' teams: '.
        $json_data->competition_group_round.($json_data->competition_round != '' ? ' - '.$json_data->competition_round : '');

        $total_matches = $json_data->total_matches;

        // Now here we calculate total time for a Compeation format For RR
        // Move For loop and take count -1 for round robin
        $totalRound = count($json_data->tournament_competation_format->format_name);
        $total_rr_time = 0; $total_final_time=0;$total_time=0;
        // we use -1 loop for only consider round robin matches
        // TODO: We change logic to Only Consider final Matches

        if(isset($json_data->final_round) && ($json_data->final_round == 'F' || $json_data->final_round == 'F/SMF')) {
          // Its Final Round
          $isFinalMatch = 1;
        } else {
          $isFinalMatch = 0;
        }

        $total_round_match = $isFinalMatch ? $total_matches - 1 : $total_matches;
        // Calculate Game Duration for RR
        $total_rr_time+= $data['game_duration_RR'] * $total_round_match;
        // Calculate  half Time Break for RR
        $total_rr_time+= $data['halftime_break_RR'] * $total_round_match;
        // Calculate Match Interval
        $total_rr_time+= $data['match_interval_RR'] * $total_round_match;

        if($isFinalMatch) {
          // Calculate Game Duration for RR
          $total_final_time+= $data['game_duration_FM'];
          // Calculate  half Time Break for RR
          $total_final_time+= $data['halftime_break_FM'];
          // Calculate Match Interval
          $total_final_time+= $data['match_interval_FM'];
        }

        // Now we sum up round robin and final match
        $total_time = $total_rr_time + $total_final_time;

        return array($total_time,$total_matches,$disp_format_name);
    }
    public function GetCompetationFormat($data) {

      $isMobileUsers = \Request::header('IsMobileUser');
      if( $isMobileUsers != '') {
        $data = $data->all();
        $tournament_arr = array('tournament_id'=>$data['tournament_id']);
        $data = $this->ageGroupObj->getCompeationFormat($tournament_arr);
      }
      else {
          $data = $this->ageGroupObj->getCompeationFormat($data['tournamentData']);
      }
        if ($data) {
            return ['status_code' => '200', 'message' => 'Competation Data', 'data' => $data];
        }
    }
    public function deleteCompetationFormat($data) {

        $data = $this->ageGroupObj->deleteCompeationFormat($data['tournamentCompetationTemplateId']);

        if ($data) {
            return ['status_code' => '200', 'message' => 'Competation Data delete successfully'];
        }
    }

    /**
     * create New AgeGroup.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function create($data)
    {
        $data = $data->all();
        $data = $this->ageGroupObj->create($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit AgeGroup.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        $data = $this->ageGroupObj->edit($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete AgeGroup.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function delete($data)
    {
        $data = $data->all();
        $data = $this->ageGroupObj->delete($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
}
