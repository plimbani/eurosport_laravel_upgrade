<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\AgeGroupContract;
use Laraspace\Api\Repositories\AgeGroupRepository;

class AgeGroupService implements AgeGroupContract
{
    public function __construct(AgeGroupRepository $ageRepoObj)
    {
        $this->ageGroupObj = $ageRepoObj;
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
        // TODO: Here we set the value for Other Data
        // Impliclityly Add 2 For Multiplication
        if($data['game_duration_RR'] == 'other') {
          $data['game_duration_RR'] = 2 * $data['game_duration_RR_other'];
        }
        if($data['game_duration_FM'] == 'other') {
          $data['game_duration_FM'] = 2 * $data['game_duration_FM_other'];
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

        $id = $this->ageGroupObj->createCompeationFormat($data);


        // here we insert Groups in Competation Formats
        // First we check if its Edit or Update
        if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0)
        {
            // here we check if template data is changed if changed
            // delete all data and insert new one
            // TODO: Here we check if there is change then and then change Data
            if($data['tournament_template_id'] != $data['tournamentTemplate']['id']) {
                // Delete Competation Data
                $this->ageGroupObj->deleteCompetationData($data);
                // Delete temp_fixtures Data

                $id = $data['competation_format_id'];
                $this->addCompetationGroups($id,$data);
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
        $json_data = json_decode($data['tournamentTemplate']['json_data']);


        //$competationData['name'] = $data['ageCategory_name'].'-'.$group_name;
        $totalRound = count($json_data->tournament_competation_format->format_name);
        $group_name=array();
        $fixture_array = array();
        for($i=0;$i<$totalRound;$i++){
            // Now here we calculate followng fields
            $rounds = $json_data->tournament_competation_format->format_name[$i]->match_type;
            foreach($rounds as $key=>$round) {
                $val = $key.'-'.$i;
                $group_name[$val]['group_name']=$round->groups->group_name;
                $group_name[$val]['team_count']=$round->group_count;
                // Now here For Loop for create Fixture array
                foreach($round->groups->match as $key1=>$matches) {
                    $newVal = $val.'|'.$group_name[$val]['group_name'].'|'.$key1;
                    $fixture_array[$newVal] = $matches->match_number;
                }
            }
        }
        $competation_array = array();
        $competation_array=$this->ageGroupObj->addCompetations($competationData,$group_name);
        //print_r($fixture_array);
        //print_r($competation_array);

        // Now here we insert Fixtures
        $this->ageGroupObj->addFixturesIntoTemp($fixture_array,$competation_array);
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
        $json_data->competition_group_round.'-'.$json_data->competition_round;

        $total_matches = $json_data->total_matches;

        // Now here we calculate total time for a Compeation format For RR
        // Move For loop and take count -1 for round robin
        $totalRound = count($json_data->tournament_competation_format->format_name);
        $total_rr_time = 0; $total_final_time=0;$total_time=0;
        // we use -1 loop for only consider round robin matches
        // TODO: We change logic to Only Consider final Matches

        if($json_data->competition_round == 'F') {
          // Its Final Round
          $roundFinal = 1;
        } else {
          $roundFinal = 0;
        }

        for($i=0;$i<$totalRound-$roundFinal;$i++){
            // Now here we calculate followng fields
            $rounds = $json_data->tournament_competation_format->format_name[$i]->match_type;
            // Now here we have to for loop for match_type

            foreach($rounds as $round) {
               $total_round_match = $round->total_match;
               // Calculate Game Duration for RR
               $total_rr_time+= $data['game_duration_RR'] * $total_round_match;
               // Calculate  half Time Break for RR
               $total_rr_time+= $data['halftime_break_RR'] * $total_round_match;
              // Calculate Match Interval
               $total_rr_time+= $data['match_interval_RR'] * $total_round_match;
           }

        }

        // Now we calculate final match time
        if($json_data->competition_round == 'F')
        {
          $final_round = array_pop($json_data->tournament_competation_format->format_name);

          // we know that we have only one Final Round Over here
          $total_final_match = $final_round->match_type[0]->total_match;

          $total_final_time  = $data['game_duration_FM']  * $total_final_match;
          $total_final_time += $data['halftime_break_FM'] * $total_final_match;
          $total_final_time += $data['match_interval_FM'] * $total_final_match;

        } else {
          $total_final_time  = 0;
        }
        // Now we sum up round robin and final match
        $total_time = $total_rr_time + $total_final_time;

        return array($total_time,$total_matches,$disp_format_name);
    }
    public function GetCompetationFormat($data) {
        $data = $this->ageGroupObj->getCompeationFormat($data['tournamentData']);

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
