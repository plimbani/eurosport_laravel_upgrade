<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\AgeGroup;
use Laraspace\Models\TournamentCompetationTemplates;

class AgeGroupRepository
{
    public function getAll()
    {
        return AgeGroup::get();
    }

    public function create($data)
    {
        return AgeGroup::create($data);
    }

    public function edit($data)
    {
        return AgeGroup::where('id', $data['id'])->update($data);
    }

    public function delete($data)
    {
        return AgeGroup::find($data['id'])->delete();
    }
    public function createCompeationFormat($data){
      // here first we save the Age Group            
      // $ageGroupData['name'] = $data['ageCategory_name'];
      // $ageGroupId = AgeGroup::create($ageGroupData)->id;
      
      // here we save the tournament_competation_template      
      $tournamentCompeationTemplate['group_name'] = $data['ageCategory_name'];
      $tournamentCompeationTemplate['tournament_id'] = $data['tournament_id'];
      $tournamentCompeationTemplate['tournament_template_id'] = $data['tournamentTemplate']['id'];
      $tournamentCompeationTemplate['total_match'] = $data['total_match'];
      $tournamentCompeationTemplate['disp_format_name'] =$data['disp_format_name'];
      $tournamentCompeationTemplate['total_time'] =$data['total_time'];
      $tournamentCompeationTemplate['game_duration_RR'] = $data['game_duration_RR'];
      $tournamentCompeationTemplate['game_duration_FM']= $data['game_duration_FM'];
      $tournamentCompeationTemplate['halftime_break_RR']= $data['halftime_break_RR'];
      $tournamentCompeationTemplate['halftime_break_FM']= $data['halftime_break_FM'];
      $tournamentCompeationTemplate['match_interval_RR']= $data['match_interval_RR'];
      $tournamentCompeationTemplate['match_interval_FM']= $data['match_interval_FM'];

      // Insert value in Database             
      return TournamentCompetationTemplates::create($tournamentCompeationTemplate);  
      // Now here we return the appropriate Data
    }
    /*
      This Function will Fetch Data For tournament_competation_table
      We pass tournamentId
     */
    public function getCompeationFormat($tournamentData) {  
     // print_r($tournamentData);
      $fieldName = key($tournamentData);
      $value = $tournamentData[$fieldName];
      
      return TournamentCompetationTemplates::where($fieldName, $value)->get();
    }
}
