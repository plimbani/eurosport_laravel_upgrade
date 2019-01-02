<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Referee;
use Laraspace\Models\AgeGroup;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\Competition;
use Laraspace\Models\Position;
use Laraspace\Models\TempFixture;
use DB;
use Carbon\Carbon;

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
      $i=1;
      $competations=array();
      $age_group = $competation_data['age_group_name'];

      $cntGroups = count($group_data);
      $competationIds = array();
      foreach($group_data as $groups){
       $competations['tournament_competation_template_id'] = $competation_data['tournament_competation_template_id'];
       $competations['tournament_id'] = $competation_data['tournament_id'];
       $comp_group = $groups['group_name'];
       $actual_comp_group = $groups['actual_group_name'];
       $competations['name'] = $age_group.'-'.$comp_group;
       $competations['actual_name'] = $age_group.'-'.$actual_comp_group;
       $competations['team_size'] = $groups['team_count'];
       $matchType = explode('-',$groups['match_type']);

       if($matchType[0] == 'PM') {
        $competaon_type = 'Elimination';
       } else {
        $competaon_type = 'Round Robin';
       }

       $actualName = explode('-', $groups['actual_name']);
       
       if($actualName[0] == 'PM') {
         $actualCompetitionType = 'Elimination';
       } else {
         $actualCompetitionType = 'Round Robin';
       }

        $competations['competation_type'] = $competaon_type;
        $competations['actual_competition_type'] = $actualCompetitionType;
        $competations['competation_round_no'] = $groups['comp_roundd'];
        $competationIds[$i]['id'] = Competition::create($competations)->id;
        $competationIds[$i]['name'] = $comp_group;
        $competationIds[$i]['tournamentId'] = $competation_data['tournament_id'];
        $competationIds[$i]['ageGroup'] = $age_group;
        $competationIds[$i]['ageGroupId'] = $competation_data['tournament_competation_template_id'];
        $competationIds[$i]['competation_type'] = $competaon_type;

       $i++;
      }

     return $competationIds;
    }

    public function createCompeationFormat($data){
      $tournamentCompeationTemplate = array();
      $tournamentCompeationTemplate['group_name'] = $data['ageCategory_name'];
      $tournamentCompeationTemplate['comments'] = $data['comments'] != '' ? $data['comments'] : null;
      $tournamentCompeationTemplate['tournament_id'] = $data['tournament_id'];
      $tournamentCompeationTemplate['tournament_template_id'] = $data['tournamentTemplate']['id'];
      $tournamentCompeationTemplate['total_match'] = $data['total_match'];
      $tournamentCompeationTemplate['category_age'] = $data['category_age'];
      $tournamentCompeationTemplate['pitch_size'] = $data['pitch_size'];
      $tournamentCompeationTemplate['category_age_color'] = $data['category_age_color'];
      $tournamentCompeationTemplate['category_age_font_color'] = $data['category_age_font_color'];
      $tournamentCompeationTemplate['disp_format_name'] =$data['disp_format_name'];
      $tournamentCompeationTemplate['total_time'] =$data['total_time'];

      $tournamentCompeationTemplate['game_duration_RR'] = $data['game_duration_RR']/$data['halves_RR'];
      $tournamentCompeationTemplate['halves_RR'] = $data['halves_RR'];
      $tournamentCompeationTemplate['game_duration_FM']= $data['game_duration_FM']/$data['halves_FM'];
      $tournamentCompeationTemplate['halves_FM'] = $data['halves_FM'];

      $tournamentCompeationTemplate['halftime_break_RR']= $data['halftime_break_RR'];
      $tournamentCompeationTemplate['halftime_break_FM']= $data['halftime_break_FM'];
      $tournamentCompeationTemplate['match_interval_RR']= $data['match_interval_RR'];
      $tournamentCompeationTemplate['match_interval_FM']= $data['match_interval_FM'];

      $tournamentCompeationTemplate['total_teams'] = $data['total_teams'];
      $tournamentCompeationTemplate['min_matches'] = $data['min_matches'];
      $tournamentCompeationTemplate['team_interval'] = $data['team_interval'];

      $tournamentCompeationTemplate['win_point']= $data['win_point'];
      $tournamentCompeationTemplate['loss_point']= $data['loss_point'];
      $tournamentCompeationTemplate['draw_point']= $data['draw_point'];
      $tournamentCompeationTemplate['rules']= $data['selectedCategoryRule'];

      $tournamentCompeationTemplate['tournament_format']= $data['tournament_format'];
      $tournamentCompeationTemplate['competition_type']= ($data['tournament_format'] == 'basic' && $data['competition_type']) ? $data['competition_type'] : null;
      $tournamentCompeationTemplate['group_size']= $data['group_size'] ? $data['group_size'] : null;
      $tournamentCompeationTemplate['remarks']= $data['remarks'] ? $data['remarks'] : null;
      $tournamentCompeationTemplate['template_font_color']= $data['template_font_color'] ? $data['template_font_color'] : null;

      if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0){
        $tournamentCompetitionTemplate = TournamentCompetationTemplates::where('id', $data['competation_format_id'])->first();
        // for normal mathches 
        $previousNormalMatchTotalTime = ($tournamentCompetitionTemplate->game_duration_RR * $tournamentCompetitionTemplate->halves_RR) + $tournamentCompetitionTemplate->halftime_break_RR + $tournamentCompetitionTemplate->match_interval_RR;
        $newNormalMatchTotalTime = ($tournamentCompeationTemplate['game_duration_RR'] * $tournamentCompeationTemplate['halves_RR']) + $tournamentCompeationTemplate['halftime_break_RR'] + $tournamentCompeationTemplate['match_interval_RR'];
        $diffInMinutesForNormalMatches = $previousNormalMatchTotalTime - $newNormalMatchTotalTime;
        if($previousNormalMatchTotalTime > $newNormalMatchTotalTime) {
            $tempFixtures = TempFixture::where('age_group_id', $data['competation_format_id'])
                                        ->where('is_scheduled', 1)
                                        ->where('is_final_round_match', 0)
                                        ->where('hometeam_score', '=', NULL)
                                        ->where('awayteam_score', '=', NULL)
                                        ->whereRaw('TIMESTAMPDIFF(MINUTE, match_datetime, match_endtime) > '.$newNormalMatchTotalTime.'')
                                        ->update(['match_endtime' => DB::raw('match_endtime - INTERVAL '.$diffInMinutesForNormalMatches.' Minute')]);
        }
        // for final matches
        $previousFinalMatchTotalTime = ($tournamentCompetitionTemplate->game_duration_FM * $tournamentCompetitionTemplate->halves_FM) + $tournamentCompetitionTemplate->halftime_break_FM + $tournamentCompetitionTemplate->match_interval_FM;
        $newFinalMatchTotalTime = ($tournamentCompeationTemplate['game_duration_FM'] * $tournamentCompeationTemplate['halves_FM']) + $tournamentCompeationTemplate['halftime_break_FM'] + $tournamentCompeationTemplate['match_interval_FM'];
        $diffInMinutesForFinalMatches = $previousFinalMatchTotalTime - $newFinalMatchTotalTime;
        if($previousFinalMatchTotalTime > $newFinalMatchTotalTime) {
          $tempFixture = TempFixture::where('age_group_id', $data['competation_format_id'])
                                      ->where('is_scheduled', 1)
                                      ->where('is_final_round_match', 1)
                                      ->where('hometeam_score', '=', NULL)
                                      ->where('awayteam_score', '=', NULL)
                                      ->whereRaw('TIMESTAMPDIFF(MINUTE, match_datetime, match_endtime) > '.$newFinalMatchTotalTime.'')
                                      ->update(['match_endtime' => DB::raw('match_endtime - INTERVAL '.$diffInMinutesForFinalMatches.' Minute')]);
        }

        $updataArr = array();
        $updataArr['tournament_id'] = $data['tournament_id'];
        $updataArr['age_cat_id'] = $data['competation_format_id'];
        $updataArr['newCatname'] = trim($data['ageCategory_name']."-".$data['category_age']);
        $this->updateAgeCatAndName($updataArr);

        $tournamentCompeationTemplate['rules'] = json_encode($tournamentCompeationTemplate['rules']);
        if(($data['tournament_format'] == 'basic' && $data['competition_type'] == 'league') && ($tournamentCompetitionTemplate->total_teams !== $data['total_teams'])) {
          $tournamentCompeationTemplate['template_json_data'] = $data['tournamentTemplate']['json_data'];
        }

        if(($data['tournament_format'] == 'basic' && $data['competition_type'] == 'knockout') && ($tournamentCompetitionTemplate->total_teams !== $data['total_teams'] || $tournamentCompetitionTemplate->group_size !== $data['group_size'])) {
          $tournamentCompeationTemplate['template_json_data'] = $data['tournamentTemplate']['json_data'];
        }

        return TournamentCompetationTemplates::where('id', $data['competation_format_id'])->update($tournamentCompeationTemplate);
      } else {
        $tournamentCompeationTemplate['template_json_data'] = $data['tournamentTemplate']['json_data'];
        return TournamentCompetationTemplates::create($tournamentCompeationTemplate)->id;
      }
    }
    /**
     *   This Function Used for Update the competaions and temp_fixtures
     *
     */
    private function updateAgeCatAndName($updataArr)
    {
      $tournamenTemplateData = TournamentCompetationTemplates::where('id','=',$updataArr['age_cat_id'])->get();
      $dbCatname = trim($tournamenTemplateData[0]['group_name']."-".$tournamenTemplateData[0]['category_age']);
      $newCatName =trim($updataArr['newCatname']);

      DB::table('competitions')->where('tournament_competation_template_id','=',$updataArr['age_cat_id'])
      ->where('tournament_id','=',$updataArr['tournament_id'])
      ->update([
        'name'=> DB::raw("REPLACE(name, '".$dbCatname."', '".$newCatName."')")
        ]);

      DB::table('temp_fixtures')->where('age_group_id','=',$updataArr['age_cat_id'])
      ->where('tournament_id','=',$updataArr['tournament_id'])
      ->update([
        'match_number'=> DB::raw("REPLACE(match_number, '".$dbCatname."', '".$newCatName."')")
        ]);

    }

    /*
      This Function will Fetch Data For tournament_competation_table
      We pass tournamentId
     */
    public function getCompeationFormat($tournamentData) {
      if(count($tournamentData) > 1)
      {
          $ageGroupIdArray = [];
          $ageGroupIdArray[] = $tournamentData['cat_id'];
          $result= TournamentCompetationTemplates::
                 leftjoin('tournament_template', 'tournament_template.id', '=',
                  'tournament_competation_template.tournament_template_id')
                 ->leftJoin('tournaments','tournaments.id','=','tournament_competation_template.tournament_id')
                 ->select('tournament_competation_template.*','tournament_template.name as template_name',
                   \DB::raw('CONCAT("'.$this->tournamentLogoUrl.'", tournaments.logo) AS tournamentLogo'))
                  ->where('tournament_id', $tournamentData['tournament_id']);
                  if(isset($tournamentData['cat_id']))
                  {
                    $result->whereIn('tournament_competation_template.id',$ageGroupIdArray);
                  }

          return $result->get();
      } else {
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
      }
    }
    /*
      This Function will Fetch Data For tournament_competation_table
      We pass tournamentId
     */
    public function deleteCompeationFormat($tournamentCompetationTemplateId)
    {
      $tournamentCompetationTemplate = TournamentCompetationTemplates::find($tournamentCompetationTemplateId);
      $tournamentId = $tournamentCompetationTemplate->tournament_id;
      $tournamentReferees = Referee::where('tournament_id', $tournamentId)->get();

      foreach ($tournamentReferees as $tournamentReferee) {
        $ageGroupIds = explode(',', $tournamentReferee->age_group_id);
        $index = array_search($tournamentCompetationTemplateId, $ageGroupIds);

        if($index !== false) {
          unset($ageGroupIds[$index]);
        }

        $tournamentReferee->age_group_id = count($ageGroupIds) > 0 ? implode(',', array_values($ageGroupIds)) : null;
        $tournamentReferee->save();
      }

      return $tournamentCompetationTemplate->delete();
    }

    public function deleteCompetationData($data)
    {
      return Competition::where('tournament_id',$data['tournament_id'])
             ->where('tournament_competation_template_id',$data['competation_format_id'])
             ->delete();
    }
    
    public function FindTemplate($id) 
    {
     return  DB::table('tournament_template')->where('id',$id)->first();
    }

    public function addFixturesIntoTemp($fixtureArray,$competationArr,$fixtureMatchDetailArray, $categoryAge)
    {
      foreach($fixtureArray as $key=>$fixture) {
        // echo '1'."<br>";

          $groupArr = explode('|',$key);
          $groupName = $groupArr[1];
          foreach($competationArr as $group) {
            $tournamentId = $group['tournamentId'];
            $ageGroup = $group['ageGroup'];
            $ageGroupId= $group['ageGroupId'];

            if($groupName == $group['name']) {
              $competationId = $group['id'];
              $round = $group['competation_type'];
            }
          }

          // Team Assignement
          $fixtu=explode('.',$fixture);
          $teams = explode('-',$fixtu[count($fixtu)-1]);
          // echo "<pre>"; print_r($teams); echo "</pre>";
          $homeTeam = $teams[0];
          $away_team = $teams[1];

          // Todo   column
          // replace Fixture Name with Actual Group Name
          // $ageGroup = $ageGroup.'-';

          // echo "<pre>"; print_r(1); echo "</pre>";
          $fixture_n = str_replace('CAT.', $ageGroup.'-',$fixture);
          $displayMatchNumber = null;

          if($fixtureMatchDetailArray[$key]['display_match_number'] != null) {
            $displayMatchNumber = str_replace('CAT.', $categoryAge.'.', $fixtureMatchDetailArray[$key]['display_match_number']);
          }      

          $teampfixtureTable=DB::table('temp_fixtures');
          $teampfixtureTable->insert(
            [
              'match_number'=>$fixture_n,
              'position'=>$fixtureMatchDetailArray[$key]['position'],
              'display_match_number'=>$displayMatchNumber,
              'tournament_id'=>$tournamentId,
              'competition_id'=>$competationId,
              'home_team_name'=>$homeTeam,
              'home_team_placeholder_name'=>$homeTeam,
              'display_home_team_placeholder_name'=>$fixtureMatchDetailArray[$key]['display_home_team_placeholder_name'],
              'match_result_id'=> 0,
              'created_at'=> new \DateTime(),
              'round'=>$round,
              'is_final_round_match'=>$fixtureMatchDetailArray[$key]['is_final_match'],
              'age_group_id'=>$ageGroupId,
              'away_team_name'=>$away_team,
              'away_team_placeholder_name'=>$away_team,
              'display_away_team_placeholder_name'=>$fixtureMatchDetailArray[$key]['display_away_team_placeholder_name'],
              'venue_id'=>0,
              'pitch_id'=>0
            ]
          );
      }

      return true;
    }

    public function getPlacingsData($data) 
    {
      $positions = Position::with('team', 'team.country')->where('age_category_id', $data['ageCategoryId'])->get();
      
      $positionData = [];
      foreach ($positions as $key => $position) {
        $positionData[$key]['pos'] = $position->position;
        if(isset($position->team)) {
          $positionData[$key]['team_name'] = $position->team['name'];
          $positionData[$key]['team_flag'] = $position->team->country->country_flag;
          $positionData[$key]['team_logo'] = getenv('S3_URL') . $position->team->country->logo;
        } else {
          $positionData[$key]['team_name'] = '';

        }
      }

      return $positionData;
    }
}
