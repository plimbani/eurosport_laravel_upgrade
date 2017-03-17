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
        
        list($totalTime,$totalmatch,$dispFormatname) = $this->calculateTime($data);

        $data['total_time'] = $totalTime;
        $data['total_match'] = $totalmatch;
        $data['disp_format_name'] = $dispFormatname;            
        
        $data = $this->ageGroupObj->createCompeationFormat($data);

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }                
    }

    private function calculateTime($data) {
        // We calculate the Following over here
        // Total Time
        // Total Match
        // display Format Name
        
        $json_data = json_decode($data['tournamentTemplate']['json_data']);
        
        $disp_format_name = $json_data->tournament_teams .' TEAMS,'. $json_data->competation_format;
        $total_matches = $json_data->total_matches;

        // Now here we calculate total time for a Compeation format For RR
        // Move For loop and take count -1 for round robin        
        $totalRound = count($json_data->tournament_competation_format->format_name);
        $total_rr_time = 0; $total_final_time=0;$total_time=0;
        for($i=0;$i<$totalRound-1;$i++){
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
        $final_round = array_pop($json_data->tournament_competation_format->format_name);

        // we know that we have only one Final Round Over here      
        $total_final_match = $final_round->match_type[0]->total_match;
        
        $total_final_time  = $data['game_duration_FM']  * $total_final_match;
        $total_final_time += $data['halftime_break_FM'] * $total_final_match;
        $total_final_time += $data['match_interval_FM'] * $total_final_match;
        
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
