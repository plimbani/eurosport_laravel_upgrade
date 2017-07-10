<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\AgeGroup;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\Competition;
use DB;
class AgeGroupRepository
{
    public function __construct() {
      $this->tournamentLogoUrl = getenv('S3_URL').'/assets/img/tournament_logo/';
    }
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
      $tournamentCompeationTemplate['group_name'] = $data['ageCategory_name'];
      $tournamentCompeationTemplate['tournament_id'] = $data['tournament_id'];
      $tournamentCompeationTemplate['tournament_template_id'] = $data['tournamentTemplate']['id'];
      $tournamentCompeationTemplate['total_match'] = $data['total_match'];
      $tournamentCompeationTemplate['category_age'] = $data['category_age'];
      $tournamentCompeationTemplate['disp_format_name'] =$data['disp_format_name'];
      $tournamentCompeationTemplate['total_time'] =$data['total_time'];

      $tournamentCompeationTemplate['game_duration_RR'] = $data['game_duration_RR'];
      $tournamentCompeationTemplate['game_duration_FM']= $data['game_duration_FM'];
      $tournamentCompeationTemplate['halftime_break_RR']= $data['halftime_break_RR'];
      $tournamentCompeationTemplate['halftime_break_FM']= $data['halftime_break_FM'];
      $tournamentCompeationTemplate['match_interval_RR']= $data['match_interval_RR'];
      $tournamentCompeationTemplate['match_interval_FM']= $data['match_interval_FM'];
      // TODO: Add New Code For more Other Options

      // TODO: Add total_teams and min_matches For particular Age Category
      $tournamentCompeationTemplate['total_teams'] =$data['total_teams'];
      $tournamentCompeationTemplate['min_matches']= $data['min_matches'];
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
      if($fieldName == 'tournament_id') {
        return TournamentCompetationTemplates::
               leftjoin('tournament_template', 'tournament_template.id', '=',
                'tournament_competation_template.tournament_template_id')
               ->leftJoin('tournaments','tournaments.id','=','tournament_competation_template.tournament_id')
               ->select('tournament_competation_template.*','tournament_template.name as template_name',
                 \DB::raw('CONCAT("'.$this->tournamentLogoUrl.'", tournaments.logo) AS tournamentLogo'))
              ->where($fieldName, $value)->get();
      } else {
        return TournamentCompetationTemplates::
               leftjoin('tournament_template', 'tournament_template.id', '=',
                'tournament_competation_template.tournament_template_id')
               ->select('tournament_competation_template.*','tournament_template.name as template_name')
              ->where('tournament_competation_template.'.$fieldName, $value)->get();
      }



      // TODO: here we call function to Display All Templates Related to
      // compeation Format
     /* $reportQuery = DB::table('tournament_competation_template')
                ->leftjoin('tournament_template',
                function($join) {
                  $join->on('tournament_competation_template.total_teams','<=','tournament_template.total_teams');
                  $join->on('tournament_competation_template.min_matches','<=','tournament_template.minimum_matches');
                })
                ->having(DB::raw('COUNT(`tournament_template`.`id`)'),'>=',1)
                ->where('tournament_competation_template.tournament_id',$value)
                ->groupBy('tournament_competation_template.id')
                ->select('tournament_competation_template.*',
                  DB::raw('COUNT(tournament_template.id) as cnt')
                  )
                ->get();
     // Now here we find all Templates And Arrange it By id
     $templData = TournamentTemplates::get();
     $templatesArr = array();

     foreach($reportQuery as $key=>$data) {

        // here we check if count is greater than 2 then change it
        if($data->cnt >= 2)  {
          // We assign Multiple values and Iterate Loop
          // For All templates
          $templatesArr[$key]['id'] = $data->id;
          for($i=0;$i<$data->cnt;$i++) {

          $templatesArr[$key]['templates'][$i]['group_name'] = $data->group_name;
          $templatesArr[$key]['templates'][$i]['disp_format_name'] =
          'DISPFORMAT';
          $templatesArr[$key]['templates'][$i]['total_match'] =
          'TOTALMATCH';
          $templatesArr[$key]['templates'][$i]['total_time'] =
          'TOTALTIME';
          }


        } else {

          // Its Single Value
         $templatesArr[$key]['group_name'] = $data->group_name;
          $templatesArr[$key]['disp_format_name'] = $data->disp_format_name;
          $templatesArr[$key]['total_match'] = $data->total_match;
          $templatesArr[$key]['total_time'] = $data->total_time;
        }
     }
     return $templatesArr;
     print_r($templatesArr);
     exit;

      return  $reportQuery;
      print_r($data->toArray());exit;
      return TournamentCompetationTemplates::where($fieldName, $value)->get();
      */
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
    public function FindTemplate($id) {
     return  DB::table('tournament_template')->where('id',$id)->first();
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
          // $ageGroup = $ageGroup.'-';
          $fixture_n = str_replace('CAT.', $ageGroup.'-',$fixture);
          $teampfixtureTable->insert(
            ['match_number'=>$fixture_n,
            'tournament_id'=>$tournamentId,'competition_id'=>$competationId,
            'home_team_name'=>$homeTeam,'match_result_id'=> 0,
            'created_at'=> new \DateTime(),
            'away_team_name'=>$away_team,'venue_id'=>0,'pitch_id'=>0]
          );
      }
      return true;
    }
}
