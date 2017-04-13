<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\AgeGroup;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentTemplate;
use Laraspace\Models\Competition;
use DB;
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
    public function addCompetations($competation_data,$group_data)
    {
      // Now here we have to For Loop to insert all data in competations table
      //print_r($competation_data);
     // print_r($group_data);
      //exit;
      $i=1;
      $competations=array();
      $age_group = $competation_data['age_group_name'];
      
      $cntGroups = count($group_data);
      $competationIds = array();
      foreach($group_data as $groups){

       $competations['tournament_competation_template_id'] = $competation_data['tournament_competation_template_id'];
       $competations['tournament_id'] = $competation_data['tournament_id'];
       $comp_group = $groups['group_name'];
       $competations['name'] = $age_group.'-'.$comp_group;
       $competations['team_size'] = $groups['team_count'];
       // here last group we consider as Final or Elimination Match
       // Means Last one
       if($cntGroups == $i) {
        $competaon_type = 'Elimination'; 
       } else {
        $competaon_type = 'Round Robin'; 
       }

       $competations['competation_type'] = $competaon_type;
       $competationIds[$i]['id'] = Competition::create($competations)->id;   
       $competationIds[$i]['name'] = $comp_group;
       $competationIds[$i]['tournamentId'] = $competation_data['tournament_id'];
       $competationIds[$i]['ageGroup'] = $age_group;
       $i++;
      }
      
     return $competationIds;
    }
    public function createCompeationFormat($data){
      // here first we save the Age Group            
      // $ageGroupData['name'] = $data['ageCategory_name'];
      // $ageGroupId = AgeGroup::create($ageGroupData)->id;
      
      // here we save the tournament_competation_template      
      $tournamentCompeationTemplate = array();
      $tournamentCompeationTemplate['group_name'] = 
      $data['ageCategory_name'];
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
      // here we check value for Edit as Well
      
      if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0){
      return  TournamentCompetationTemplates::where('id', $data['competation_format_id'])->update($tournamentCompeationTemplate);
      } else {      
      //TournamentCompetationTemplates::create($tournamentCompeationTemplate)->id;
      // Here also Save in competations table
      

     	return TournamentCompetationTemplates::create($tournamentCompeationTemplate)->id;    
      }          
      
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
    /*
      This Function will Fetch Data For tournament_competation_table
      We pass tournamentId
     */
    public function deleteCompeationFormat($tournamentCompetationTemplateId) {  
     return TournamentCompetationTemplates::find($tournamentCompetationTemplateId)->delete();
    }

    public function deleteCompetationData($data)
    {
      return Competition::where('tournament_id',$data['tournament_id'])
             ->where('tournament_competation_template_id',$data['competation_format_id'])
             ->delete();

      /*$competationId = array();
      $competationId= Competition::where('tournament_id',$data['tournament_id'])
             ->where('tournament_competation_template_id',$data['competation_format_id'])
             ->select('id')
             ->get()->toArray();*/

     //        ->delete();
      // Here we also Delete the Fixtures which related to it in temp_fixtures
      //$query = DB::table('temp_fixtures')
        //        ->where('')
    } 
    public function addFixturesIntoTemp($fixtureArray,$competationArr)
    {
      $teampfixtureTable=DB::table('temp_fixtures');

      foreach($fixtureArray as $key=>$fixture) {
          
          
          $groupArr = explode('|',$key);
          $groupName = $groupArr[1];
          foreach($competationArr as $group) {
            $tournamentId = $group['tournamentId'];
            $ageGroup = $group['ageGroup'];
            if($groupName == $group['name']) {
              $competationId = $group['id'];
            }
          }
          // Team Assignement
          $fixtu=explode('.',$fixture);
          $teams = explode('-',$fixtu[count($fixtu)-1]);

          $homeTeam = $teams[0];
          $away_team = $teams[1];

          // Todo   column
          // replace Fixture Name with Actual Group Name
          $fixture_n = str_replace('U17', $ageGroup,$fixture);  
          $teampfixtureTable->insert(
            ['match_number'=>$fixture_n,'tournament_id'=>$tournamentId,'competition_id'=>$competationId,'home_team'=>$homeTeam,'match_result_id'=> 0,'away_team'=>$away_team]
          );
      }
      return true;
    }   
}
