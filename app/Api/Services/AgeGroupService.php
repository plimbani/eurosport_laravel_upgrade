<?php

namespace Laraspace\Api\Services;

use DB;
use PDF;
use URL;
use Laraspace\Models\Tournament;
use Laraspace\Api\Contracts\AgeGroupContract;
use Laraspace\Api\Repositories\AgeGroupRepository;
use Laraspace\Api\Repositories\TemplateRepository;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Team;
use Laraspace\Models\Position;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Traits\TournamentAccess;
use Laraspace\Models\Referee;
use Laraspace\Models\Competition;
use Laraspace\Models\AgeCategoryDivision;

class AgeGroupService implements AgeGroupContract
{
  use TournamentAccess;

    public function __construct(AgeGroupRepository $ageRepoObj)
    {
        $this->ageGroupObj = $ageRepoObj;
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
        $this->matchServiceObj = new \Laraspace\Api\Services\MatchService();
        $this->tournamentLogo =  getenv('S3_URL').'/assets/img/tournament_logo/';
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
        $data = $data['compeationFormatData'];
        // Check if maximum team exceeds
        $totalCheckTeams = 0;

        $tournamentTemplateObj = null;

        // The age category and category name for two or more categories within the tournament cannot be the same.
        $data['ageCategory_name'] = trim($data['ageCategory_name']);
        $sameAgeCategoryExists = TournamentCompetationTemplates::where('tournament_id', $data['tournament_id'])->whereRaw('LOWER(`group_name`) LIKE ?', [strtolower($data['ageCategory_name'])])->where('category_age', $data['category_age']);
        if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0){
          $sameAgeCategoryExists = $sameAgeCategoryExists->where('id', '!=', $data['competation_format_id']);
        }
        $sameAgeCategoryExists = $sameAgeCategoryExists->first();
        if($sameAgeCategoryExists) {
          return ['status_code' => '403', 'message' => 'A competition format with this age category and category name already exists.'];
        }

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

        if($data['competition_type'] === 'league') {
          $data['tournamentTemplate'] = [];
          $data['tournamentTemplate']['id'] = null;
          if($data['competition_type'] === 'league') {
            $data['tournamentTemplate']['json_data'] = $this->generateTemplateJsonForLeague($data);
          }
        }

        if(!($data['tournament_format'] === 'basic' && $data['competition_type'] === 'league')) {
          if(is_int($data['tournamentTemplate'])){
            $nwdata = (array) $this->ageGroupObj->FindTemplate($data['tournamentTemplate']);
            $data['tournamentTemplate'] = $nwdata;
          }
          list($totalTime,$totalmatch,$dispFormatname) = $this->calculateTime($data['tournamentTemplate']['json_data'], $data);
        } else if($data['tournament_format'] == 'basic') {
          list($totalTime,$totalmatch,$dispFormatname) = $this->calculateTime($data['tournamentTemplate']['json_data'], $data);
        }
        
        $data['total_time'] = $totalTime;
        $data['total_match'] = $totalmatch;
        $data['disp_format_name'] = $dispFormatname;

        if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0){
            $tournamentTemplateObj = TournamentCompetationTemplates::where('id', '=', $data['competation_format_id'])->first();
            $mininterval = $tournamentTemplateObj->minimum_team_interval;
            $maxinterval = $tournamentTemplateObj->maximum_team_interval;
        }
        
        $id = $this->ageGroupObj->createCompeationFormat($data);

        $allReferees = Referee::where('tournament_id', $data['tournament_id'])->get()->map(function ($item, $key) use (&$id) {
          if ($item['is_all_age_categories_selected'] == 1) {
            if (!empty($item['age_group_id'])) {
              $item['age_group_id'] .= ',' . $id;
            } else {
              $item['age_group_id'] = $id;
            }
            $item->save();
          }
        });

        // here we insert Groups in Competation Formats
        // First we check if its Edit or Update
        if(isset($data['competation_format_id']) && $data['competation_format_id'] != 0)
        {
            // Delete teams which are related to tournament and age group id
            Team::where('tournament_id', $data['tournament_id'])->where('age_group_id', $data['id'])->forceDelete();

            if($data['tournament_template_id'] != $data['tournamentTemplate']['id'] || $tournamentTemplateObj->tournament_format != $data['tournament_format'] || ($data['tournament_format'] === 'basic' && ($tournamentTemplateObj->competition_type != $data['competition_type'] || $tournamentTemplateObj->total_teams != $data['total_teams']))) {
                $this->ageGroupObj->deleteCompetationData($data);

                $id = $data['competation_format_id'];
                $this->addCompetationGroups($id,$data);

                // Add positions to template
                $this->insertPositions($data['competation_format_id'], $data['tournamentTemplate']);

            } else {
              $tournamentId = $data['tournament_id'];
              $ageGroupId  = $data['competation_format_id'];

              $matchData = array('tournamentId'=>$tournamentId, 'ageGroupId'=>$ageGroupId);
             
              if($data['minimum_team_interval'] != $mininterval) {   
                $matchresult =  $this->matchRepoObj->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);
              }
              if($data['maximum_team_interval'] != $maxinterval) {   
                $this->matchRepoObj->checkMaximumTeamIntervalForMatchesOnCategoryUpdate($matchData);
              }
            }
            $matchPointChangeFlag = 0;
            if ($tournamentTemplateObj->win_point != $data['win_point'] || $tournamentTemplateObj->loss_point != $data['loss_point'] || $tournamentTemplateObj->draw_point != $data['draw_point']) {
              $matchPointChangeFlag = 1;
            }
            $categoryChangeFlag = 0;
            if (json_encode($tournamentTemplateObj->rules) != json_encode($data['selectedCategoryRule'])) {
              $categoryChangeFlag = 1;
            }
            if($matchPointChangeFlag == 1 || $categoryChangeFlag==1) {
              $allCompetitionsIds = Competition::where('tournament_id', '=', $data['tournament_id'])->where('tournament_competation_template_id', '=', $data['competation_format_id'])->pluck('id')->toArray();
              foreach ($allCompetitionsIds as $id) {
                $competitionData = ['tournamentId' => $data['tournament_id'], 'competitionId' => $id];
                $this->matchServiceObj->refreshCompetitionStandings($competitionData);
              }
            }

        } else {
          $this->addCompetationGroups($id,$data);
            // Add positions to template                 
          $this->insertPositions($id, $data['tournamentTemplate']);
        }

        //$competationData['tournament_competation_template_id'] = $data;
        //$competationData['tournament_id'] = $data['tournament_id'];
        //$competationData['name'] = $data['ageCategory_name'].'-'.$group_name;


        // Here also add in competation table data number of groups

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    private function addCompetationGroups($tournament_competation_template_id, $data){
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
            $roundIndex = 0;
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

              $roundIndex++;
            }
        }
        $competation_array = array(); 
        $competation_array=$this->ageGroupObj->addCompetations($competationData,$group_name);

        $this->ageGroupObj->addFixturesIntoTemp($fixture_array,$competation_array,$fixture_match_detail_array, $categoryAge);
        
        // Insert competition for new added division
        $div_display_order = 1;

        if(isset($json_data->tournament_competation_format->divisions)) {
          foreach ($json_data->tournament_competation_format->divisions as $index => $division) {
            // Add division into age_category_divisions table

            $latest_div_id = AgeCategoryDivision::create([
              'name' => "Division " .($index + 1),
              'order' => $div_display_order,
              'tournament_id' => $data['tournament_id'],
              'tournament_competition_template_id' => $tournament_competation_template_id,
            ]);

            $divtotalRound = count($division->format_name);
            $divgroup_name=array();
            $divfixture_array = array();
            $divfixture_match_detail_array = array();

            for($i=0;$i<$divtotalRound;$i++){
                // Now here we calculate followng fields
                $rounds = $division->format_name[$i]->match_type;
                $roundIndex = 0;
                foreach($rounds as $key=>$round) {
                    $val = $key.'-'.$i;
                    $divgroup_name[$val]['group_name']=$round->groups->group_name;
                    $divgroup_name[$val]['age_category_division_id']= $latest_div_id->id;

                    if(isset($round->groups->actual_group_name)) {
                      $divgroup_name[$val]['actual_group_name']=$round->groups->actual_group_name;
                    } else {
                      $divgroup_name[$val]['actual_group_name']=$round->groups->group_name;
                    }
                    
                    $divgroup_name[$val]['team_count']=$round->group_count;
                    $divgroup_name[$val]['match_type']=$round->name;

                    if(isset($round->actual_name)) {
                      $divgroup_name[$val]['actual_name'] = $round->actual_name;
                    } else {
                      $divgroup_name[$val]['actual_name'] = $round->name;
                    }

                    $divgroup_name[$val]['comp_roundd']= $division->format_name[$i]->name;
                    // Now here For Loop for create Fixture array
                    foreach($round->groups->match as $key1=>$matches) {
                        $newVal = $val.'|'.$divgroup_name[$val]['group_name'].'|'.$key1;
                        $divfixture_array[$newVal] = $matches->match_number;

                        $divfixture_match_detail_array[$newVal] = [
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
                          $newVal = $val.'|'.$divgroup_name[$val]['group_name'].'|'.$key.$key1;
                          $divfixture_array[$newVal] = $matches->match_number;
                          $divfixture_match_detail_array[$newVal] = [
                            'display_match_number' => (isset($matches->display_match_number) ? $matches->display_match_number : null),
                            'display_home_team_placeholder_name' => (isset($matches->display_home_team_placeholder_name) ? $matches->display_home_team_placeholder_name : null),
                            'display_away_team_placeholder_name' => (isset($matches->display_away_team_placeholder_name) ? $matches->display_away_team_placeholder_name : null),
                            'is_final_match' => (isset($matches->is_final_match) ? $matches->is_final_match : 0),
                            'position' => (isset($matches->position) ? $matches->position : null)
                          ];
                        }
                      }
                    }

                  $roundIndex++;
                }
            }
            $divcompetation_array = array();

            $divcompetation_array=$this->ageGroupObj->addCompetations($competationData,$divgroup_name);
            $this->ageGroupObj->addFixturesIntoTemp($divfixture_array,$divcompetation_array,$divfixture_match_detail_array, $categoryAge);

            $div_display_order++;
          }
        }
        // End insert competition for new added division
    }
    private function calculateTime($json_data, $data) {
        $json_data = json_decode($json_data);
        $roundScheduleData = join(" - ",$json_data->round_schedule);
        $disp_format_name = $json_data->tournament_teams .' teams: '.$roundScheduleData;
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

      $categoryRules = config('config-variables.category_rules');
      $categoryRulesInfo = config('config-variables.category_rules_info');
      
      if ($data) {
          return ['status_code' => '200', 'message' => 'Competation Data', 'data' => $data, 'category_rules' => $categoryRules, 'category_rules_info' => $categoryRulesInfo];
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

    /**
     * Insert positions.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function insertPositions($ageCategoryId, $template)
    {
      Position::where('age_category_id', $ageCategoryId)->delete();
      $json_data = json_decode($template['json_data'], true);
      $tournamentPositions = isset($json_data['tournament_positions']) ? $json_data['tournament_positions'] : [];

      foreach($tournamentPositions as $tournamentPosition) {
        $position = new Position();
        $position->age_category_id = $ageCategoryId;
        $position->position = $tournamentPosition['position'];
        $position->dependent_type = $tournamentPosition['dependent_type'];
        $position->match_number = isset($tournamentPosition['match_number']) ? $tournamentPosition['match_number'] : null;
        $position->result_type = isset($tournamentPosition['result_type']) ? $tournamentPosition['result_type'] : null;
        $position->ranking = isset($tournamentPosition['ranking']) ? $tournamentPosition['ranking'] : null;
        $position->team_id = null;
        $position->save();
      }
    }

    public function getPlacingsData($data) {
      $data = $this->ageGroupObj->getPlacingsData($data);
      if ($data) {
        return ['data' => $data, 'status_code' => '200', 'message' => 'Data Fetched Successfully'];
      }
    }

    public function ageCategoryData()
    {
      $tournamentTemplates = TournamentTemplates::get()->keyBy('id')->toArray();
      $tournamentCompetationTemplates = TournamentCompetationTemplates::all();
      foreach ($tournamentCompetationTemplates as $tournamentCompetationTemplate) {
        $this->insertPositions($tournamentCompetationTemplate->id, $tournamentTemplates[$tournamentCompetationTemplate->tournament_template_id]);

        $matchPositions = Position::where('age_category_id', $tournamentCompetationTemplate->id)->where('dependent_type', 'match')->get();
        $this->matchServiceObj->updatePlacingMatchPositions($tournamentCompetationTemplate, $matchPositions);
        
        $rankingPositions = Position::where('age_category_id', $tournamentCompetationTemplate->id)->where('dependent_type', 'ranking')->get();
        $this->matchServiceObj->updateGroupRankingPositions($tournamentCompetationTemplate, $rankingPositions);
      }
    }

    public function generateTemplateJsonForLeague($data)
    {
      $totalTeams = $data['total_teams'];
      $competitionRound = 'RR 1-' .$totalTeams;
      $competitionGroupRound = '1x' .$totalTeams;

      $finalArray = [];
      $finalArray['tournament_teams'] = $totalTeams;
      $finalArray['remark'] = isset($data['remarks']) ? $data['remarks'] : '';
      $finalArray['tournament_name'] = $totalTeams. ' team league';
      $finalArray['round_schedule'] = [$competitionGroupRound, $competitionRound];
      $finalArray['tournament_min_match'] = isset($data['min_matches']) ? $data['min_matches'] : '';
      $finalArray['position_type'] = 'group_ranking';
      $finalArray['tournament_competation_format'] = [];
      $finalArray['tournament_competation_format']['format_name'] = [];
      $finalArray['tournament_positions'] = [];

      // for rounds
      $totalRounds = 1;
      $totalGroups = 1;
      for ($round = 0; $round < $totalRounds ; $round++) {
        $finalArray['tournament_competation_format']['format_name'][$round]['name'] = 'Round ' .($round+1);        

        // for groups
        $groupCount = 0;
        for ($groups = 0; $groups < $totalGroups; $groups++) {
          $finalGroupCount = 65 + $groupCount + $groups;
          
          $matches = $this->setTemplateMatchesForLeague($totalTeams, $timesPlayedEachOther = 2, chr($finalGroupCount), $round);
          $totalMatchesCount = count($matches);
          $averageMatches = $totalMatchesCount / ($totalTeams/2);

          $finalArray['total_matches'] = $totalMatchesCount;
          $finalArray['avg_game_team'] = number_format($averageMatches, 1);

          $matchTypeDetail = [
            'name' => 'RR-1*' .$totalTeams,
            'total_match' => $totalMatchesCount,
            'group_count' => $totalTeams,
            'groups' => ['group_name' => 'Group-' .chr($finalGroupCount), 'match' => $matches]
          ];

          $finalArray['tournament_competation_format']['format_name'][$round]['match_type'][] = $matchTypeDetail;
        }
        $groupCount++;
      }

      $positions = [];
      for ($i=1; $i <= $totalTeams; $i++) {
        $positions[] = ['position' => $i, 'dependent_type' => 'ranking', 'ranking' => $i. 'A'];
      }

      $finalArray['tournament_positions'] = $positions;

      return json_encode($finalArray);
    }

    public function generateTemplateJsonForKnockout($data)
    {
      $totalTeams = $data['total_teams'];
      $groupSize = $data['group_size'];
      $totalGroups = $totalTeams / $groupSize;
      $finalTeams = $totalTeams / $totalGroups;
      $teamsPerGroup = $totalTeams / $totalGroups;
      $teamsForRoundTwo = [];

      $knockoutRoundSizeArray = config('config-variables.knockout_round_two_size');
      $roundSizeData = $knockoutRoundSizeArray[$groupSize][$totalTeams];
      $knockoutRounds = log($roundSizeData, 2);

      $competitionRound = 'RR 1-' .$knockoutRounds;
      $competitionRound = $knockoutRounds. ' knockout rounds';
      $competitionGroupRound = $totalGroups. 'x' .$groupSize;

      $finalArray = [];
      $finalArray['tournament_teams'] = $totalTeams;
      $finalArray['tournament_name'] = $totalTeams. ' team knockout';
      $finalArray['round_schedule'] = [$competitionGroupRound, $competitionRound];
      $finalArray['tournament_min_match'] = $data['min_matches'];
      $finalArray['remark'] = $data['remarks'];
      $finalArray['position_type'] = 'final';
      $finalArray['tournament_competation_format'] = [];
      $finalArray['tournament_competation_format']['format_name'] = [];
      $finalArray['tournament_positions'] = [];
      $finalMatches = 0;

      $roundMatches = $this->setRoundMatches($totalTeams, $groupSize, $finalArray);

      $totalMatches = 0;
      foreach ($roundMatches['tournament_competation_format']['format_name'] as $key => $round) {
        foreach ($round['match_type'] as $key => $group) {
          $totalMatches += $group['total_match'];
        }
      }

      $finalMatches = count($roundMatches) + $finalMatches;
      // $totalMatchesCount = $finalMatches * $totalGroups;
      // $averageMatches = $totalMatchesCount / ($totalTeams/2);
      $averageMatches = $totalMatches / ($totalTeams/2);
      $finalArray['total_matches'] = $totalMatches;
      $finalArray['avg_game_team'] = number_format($averageMatches, 1);

      return json_encode($finalArray);
    }

    public function setTemplateMatchesForKnockout($totalTeams, $times, $currentGroup, $round="")
    {
      $fetchRoundMatches = $this->generateRoundFixturesBaseOnTeam($totalTeams);
      $matches = $this->leagueKnockoutJsonMatches($fetchRoundMatches,$round,$currentGroup,$times);
      return $matches;
    }

    public function setTemplateMatchesForLeague($totalTeams, $times, $currentGroup, $currentRound)
    {
      $fetchRoundMatches = $this->generateRoundFixturesBaseOnTeam($totalTeams);
      $matches = $this->leagueKnockoutJsonMatches($fetchRoundMatches,$currentRound,$currentGroup,$times);
      return $matches;
    }    

    public function setTemplateMatchesForSecondRound($group1, $group2, $currentRound)
    {
      $currentRound = $currentRound + 1;
      $secondRoundMatches = [];
      for ($i=0; $i < sizeof($group1); $i++) {
        $currentMatchIndex = $i + 1;
        $matchNumberGroup1 = strpos($group1[$i], '#') === 0 ? str_replace('#', '', $group1[$i]) : $group1[$i];
        $matchNumberGroup2 = strpos($group2[$i], '#') === 0 ? str_replace('#', '', $group2[$i]) : $group2[$i];

        $matchNumberGroup1 = strpos($matchNumberGroup1, '#') > 0 ? str_replace(array('th','st','nd','rd'), array('', '', '', ''), $matchNumberGroup1) : $matchNumberGroup1;
        $matchNumberGroup2 = strpos($matchNumberGroup2, '#') > 0 ? str_replace(array('th','st','nd','rd'), array('', '', '', ''), $matchNumberGroup2) : $matchNumberGroup2;

        $secondRoundMatches[] = [
          'in_between' => $matchNumberGroup1 . "-" . $matchNumberGroup2,
          'match_number' => "CAT.PM" . $currentRound . ".G" . $currentMatchIndex . "." . $matchNumberGroup1 . "-" . $matchNumberGroup2,
          'display_match_number' => "CAT.$currentRound.$currentMatchIndex.@HOME-@AWAY",
          'display_home_team_placeholder_name' => "$group1[$i]",
          'display_away_team_placeholder_name' => "$group2[$i]",
        ];
      }

      return $secondRoundMatches;
    }

    public function copyAgeCategory($data)
    {
      $data['ageCategoryData']['competition_format']['ageCategory_name'] = trim($data['ageCategoryData']['competition_format']['ageCategory_name']);

      $sameAgeCategoryExists = TournamentCompetationTemplates::where('tournament_id', $data['ageCategoryData']['tournament_id'])->whereRaw('LOWER(`group_name`) LIKE ?', [strtolower($data['ageCategoryData']['competition_format']['ageCategory_name'])])->where('category_age', $data['ageCategoryData']['competition_format']['category_age'])->first();
      if($sameAgeCategoryExists) {
        return ['status_code' => '403', 'message' => 'A competition format with this age category and category name already exists.'];
      }

      $copiedAgeCategory = TournamentCompetationTemplates::where('id', $data['ageCategoryData']['copiedAgeCategoryId'])->first();

      $maximumTeams = Tournament::find($data['ageCategoryData']['tournament_id'])->maximum_teams;
      $tournamentTotalTeamSumObj = TournamentCompetationTemplates::where('tournament_id', $data['ageCategoryData']['tournament_id']);
      $tournamentTotalTeamSum = $tournamentTotalTeamSumObj->pluck('total_teams')->sum();
      $totalCheckTeams = $copiedAgeCategory->total_teams + $tournamentTotalTeamSum;
      if(($totalCheckTeams > $maximumTeams)) {
        return ['status_code' => '403', 'message' => 'This category cannot be added as it exceeds the maximum teams set for this tournament.'];
      }

      $newCopiedAgeCategory = $copiedAgeCategory->replicate();
      $newCopiedAgeCategory->group_name = $data['ageCategoryData']['competition_format']['ageCategory_name'];
      $newCopiedAgeCategory->category_age = $data['ageCategoryData']['competition_format']['category_age'];
      $newCopiedAgeCategory->pitch_size = $data['ageCategoryData']['competition_format']['pitch_size'];
      $newCopiedAgeCategory->save();

      $newCopiedAgeCategoryDataArray = $newCopiedAgeCategory->toArray();
      $templateData = (array) $this->ageGroupObj->FindTemplate($newCopiedAgeCategoryDataArray['tournament_template_id']);
      $newCopiedAgeCategoryDataArray['ageCategory_name'] = $data['ageCategoryData']['competition_format']['ageCategory_name'];
      if ($templateData) {
        $newCopiedAgeCategoryDataArray['tournamentTemplate'] = $templateData;
      } else {
        $newCopiedAgeCategoryDataArray['tournamentTemplate']['json_data'] = $newCopiedAgeCategory['template_json_data'];
      }
      $this->addCompetationGroups($newCopiedAgeCategory->id, $newCopiedAgeCategoryDataArray);
      $this->insertPositions($newCopiedAgeCategory->id, $newCopiedAgeCategoryDataArray['tournamentTemplate']);
      
      return ['status_code' => '200', 'data' => $newCopiedAgeCategory, 'message' => 'Data Sucessfully Inserted'];
    }

    public function viewTemplateGraphicImage($data)
    {
      return $this->ageGroupObj->viewTemplateGraphicImage($data);
    }

    public function setRoundMatches($totalTeams, $groupSize, &$finalArray)
    {
      $totalGroups = $totalTeams / $groupSize;
      $finalTeams = $totalTeams / $totalGroups;
      $teamsPerGroup = $totalTeams / $totalGroups;
      $knockoutRoundSizeArray = config('config-variables.knockout_round_two_size');
      $roundSizeData = $knockoutRoundSizeArray[$groupSize][$totalTeams];
      $finalRounds = log($roundSizeData, 2);
      $allRounds = $finalRounds + 1;

      $nextRoundTeams = [];
      $nextRoundMatchesArray = [];
      $matches = [];

      for ($round = 0; $round < $allRounds; $round++) {
        $group1 = [];
        $group2 = [];        
        $finalArray['tournament_competation_format']['format_name'][$round]['name'] = 'Round ' .($round+1);
        if($round == 0) {
          $groupCount = 0;
          for ($group=0; $group<$totalGroups; $group++) {
            $finalGroupCount = chr(65 + $groupCount + $group);
            $matches[$round][$group] = $this->setTemplateMatchesForKnockout($finalTeams, $timesPlayedEachOther = 1, $finalGroupCount, $round);
          }
          $nextRoundTeams = $this->teamsForRoundTwo($totalGroups, $teamsPerGroup, $roundSizeData);
        } else {
          $dividedRoundMatches = sizeof($nextRoundTeams) / 2;
          for ($i=0; $i<$dividedRoundMatches; $i++) {
            $group1[] = $nextRoundTeams[$i];
            $group2[] = $nextRoundTeams[$dividedRoundMatches + $i];
          }
          
          $group2 = array_reverse($group2);

          if($round == 1) {
            $matches[$round] = $this->setTemplateMatchesForSecondRound($group1, $group2, $round);
          } else {
            $previousRound =  $round - 1;
            $matches[$round] = $this->getNextRoundMatches($group1, $group2, $matches[$previousRound], $round);
          }

          $nextRoundTeams = [];
          for ($i=0; $i < sizeof($matches[$round]); $i++) {
            $nextRoundTeams[] = $i;
          }
        }

        // setting up match detail array
        if($round == 0) {
          foreach ($matches[$round] as $key => $value) {
            $finalGroupCountForFirstRound = chr(65 + $key);
            $matchTypeDetail[] = [
              'name' => 'RR-1*' .$teamsPerGroup,
              'total_match' => sizeof($value),
              'group_count' => $teamsPerGroup,
              'groups' => ['group_name' => 'Group-' .$finalGroupCountForFirstRound, 'match' => $value]
            ];

            $finalArray['tournament_competation_format']['format_name'][$round]['match_type'] = $matchTypeDetail;
          }
        } else {
          $matchTypeDetail = [
            'name' => 'PM-1*' .sizeof($matches[$round]) * 2,
            'total_match' => sizeof($matches[$round]),
            'group_count' => sizeof($matches[$round]) * 2,
            'groups' => ['group_name' => 'Group-PM' .$round, 'match' => $matches[$round]]
          ];
        }

        if($round > 0) {
          $finalArray['tournament_competation_format']['format_name'][$round]['match_type'][] = $matchTypeDetail;
        }
      }

      $lastRoundMatches = end($matches);

      $positions = [];
      $positions[0] = ['position' => 1, 'dependent_type' => 'match', 'match_number' => $lastRoundMatches[0]['match_number'], 'result_type' => 'winner'];
      $positions[1] = ['position' => 2, 'dependent_type' => 'match', 'match_number' => $lastRoundMatches[0]['match_number'], 'result_type' => 'loser'];

      $finalArray['tournament_positions'] = $positions;

      return $finalArray;
    }


    public function teamsForRoundTwo($totalGroups, $teamsPerGroup, $roundSizeData)
    {
      $teamsForRoundTwo = [];
      $totalTeams = 0;
      for($i = 0; $i<$totalGroups; $i++){
        $teamsForRoundTwo[] = '#1' .chr(65 + $i);
        $totalTeams++;
      }

      for($i = 2; $i<=$teamsPerGroup; $i++) {
        if($i > 2 || ($totalTeams + $totalGroups) > $roundSizeData) {
          for($j=1; $j<=$totalGroups; $j++) {
            if($totalTeams < $roundSizeData) {
              $teamsForRoundTwo[] = $this->getOrdinal($j). '#' .$i;
              $totalTeams++;
            }
          }
        } else {
          for($j=0; $j<$totalGroups; $j++) {
            $teamsForRoundTwo[] = '#' . $i .chr(65 + $j);
            $totalTeams++;
          }
        }

        if($totalTeams === $roundSizeData) {
          break;
        }
      }

      return $teamsForRoundTwo;
    }

    public function getNextRoundMatches($group1, $group2, $previousRoundMatches, $currentRound)
    {
      $nextRoundMatches = [];
      $currentRound = $currentRound + 1;
      $previousRound = $currentRound - 1;

      for ($i=0; $i < sizeof($group1) ; $i++) {
        $currentMatch = $i + 1;

        $homeMatchArray = $previousRoundMatches[$group1[$i]];
        $homeMatchNumber = explode(".", $homeMatchArray['match_number']);
        $homeDisplayMatchNumber = explode(".", $homeMatchArray['display_match_number']);
        $homeMatch = str_replace("-", "_", end($homeMatchNumber));

        $awayMatchArray = $previousRoundMatches[$group2[$i]];
        $awayMatchNumber = explode(".", $awayMatchArray['match_number']);
        $awayDisplayMatchNumber = explode(".", $awayMatchArray['display_match_number']);
        $awayMatch = str_replace("-", "_", end($awayMatchNumber));

        if (strpos($homeMatchArray['match_number'], 'WR') || strpos($homeMatchArray['match_number'], 'LR')) {
          $nextRoundMatches[] = [
            'in_between' => 'CAT.PM' .$previousRound. '.G' .$homeDisplayMatchNumber[2]. 'WR-CAT.PM' .$previousRound. '.G' .$awayDisplayMatchNumber[2]. 'WR',
            'match_number' => 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.(' .$homeMatchNumber[1]. '_'. $homeMatchNumber[2] .'_WR)-(' .$awayMatchNumber[1]. '_'. $awayMatchNumber[2]. '_WR)',
            'display_match_number' => 'CAT.' .$currentRound. '.' .$currentMatch. '.wrs.(@HOME-@AWAY)',
            'display_home_team_placeholder_name' => "$previousRound.$homeDisplayMatchNumber[2]",
            'display_away_team_placeholder_name' => "$previousRound.$awayDisplayMatchNumber[2]",
          ];
        } else {
          $nextRoundMatches[] = [
            'in_between' => 'CAT.PM' .$previousRound. '.G' .$homeDisplayMatchNumber[2]. 'WR-CAT.PM' .$previousRound. '.G' .$awayDisplayMatchNumber[2]. 'WR',
            'match_number' => 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.' .$homeMatch. '_WR-' .$awayMatch. '_WR',
            'display_match_number' => 'CAT.' .$currentRound. '.' .$currentMatch. '.wrs.(@HOME-@AWAY)',
            'display_home_team_placeholder_name' => "$previousRound.$homeDisplayMatchNumber[2]",
            'display_away_team_placeholder_name' => "$previousRound.$awayDisplayMatchNumber[2]",
          ];
        }
      }

      return $nextRoundMatches;
    }

    public function generateRoundFixturesBaseOnTeam($teamSize)
    {
      $teams = range(1,$teamSize);
      $odd = array_values(array_filter($teams, function($k) { return($k%2 == 1); }));
      $even = array_values(array_filter($teams, function($k) { return($k%2 == 0); }));

      // check teamsize even or odd if odd then add extra bye team
      if ($teamSize % 2 == 1) {
        array_push($even,'bye');
      } else {
        $teamSize--;
      }

      // Generate week round and matches
      $weekRoundMatches = [];

      for( $j=1; $j<=$teamSize; $j++ )
      {
        // generate matches week wise
        foreach ($odd as $key => $value) {
          if ( $value != 'bye' && $even[$key] != 'bye')
          {
            $weekRoundMatches[$j][] = $value.'-'.$even[$key];
          }
        }

        // now swap teams fot next week end matches and get and remove last element of odd array and push to end of even array
        array_push($even, end($odd) );
        array_pop($odd);

        // move first element of even array to second position of odd array
        array_splice($odd, 1, 0, head($even));
        array_shift($even);
      }

      return $weekRoundMatches;
    }

    public function leagueKnockoutJsonMatches($fetchRoundMatches, $round, $currentGroup, $times, $startRoundCount = 0)
    {
      $currentRound = $startRoundCount + $round + 1;
      $matches = [];

      for($i=0; $i<$times; $i++){
        foreach ($fetchRoundMatches as $key => $week) {
          foreach ($week as $wkey => $match) {
            $weekNumber = $i*count($fetchRoundMatches) + $key;
            list($home,$away) = explode('-',$match);

            $matches[] = ['in_between' => $match,
                            'match_number' => "CAT.RR" . $currentRound . '.' . sprintf('%02d',$weekNumber).".$currentGroup$home-$currentGroup$away",
                            'display_match_number' => "CAT." . $currentRound . ".$weekNumber.@HOME-@AWAY",
                            'display_home_team_placeholder_name' => "$currentGroup$home",
                            'display_away_team_placeholder_name' => "$currentGroup$away"
                          ];
          }
        }
      }

      return $matches;
    }

    public function deleteFinalPlacingTeam($data) {
      $data = $this->ageGroupObj->deleteFinalPlacingTeam($data);
      if ($data) {
        return ['data' => $data, 'status_code' => '200', 'message' => 'Teams has been deleted Successfully'];
      }
    }

    public function getOrdinal($number) {
      $ends = array('th','st','nd','rd','th','th','th','th','th','th');
      if ((($number % 100) >= 11) && (($number % 100) <= 13))
          return $number. 'th';
      else
          return $number. $ends[$number % 10];
    }

    public function generateMatchSchedulePrint($templateData)
    {
      $tournamentCompetationTemplate = null;
      $templateId = (isset($templateData['templateId']) && $templateData['templateId']) ? $templateData['templateId'] : null;
      $ageCategoryId = (isset($templateData['ageCategoryId']) && $templateData['ageCategoryId']) ? $templateData['ageCategoryId'] : null;
      if(isset($templateData['ageCategoryId']) && $templateData['ageCategoryId']) {
        $tournamentCompetationTemplate = TournamentCompetationTemplates::find($templateData['ageCategoryId']);
      }
      $tournamentFormat = $tournamentCompetationTemplate ? $tournamentCompetationTemplate->tournament_format : $templateData['tournamentFormat'];
      $competitionType = $tournamentCompetationTemplate ? $tournamentCompetationTemplate->competition_type : (isset($templateData['competitionType']) ? $templateData['competitionType'] : null);
      $numberOfTeams = $tournamentCompetationTemplate ? $tournamentCompetationTemplate->total_teams : $templateData['numberOfTeams'];
      $tournamentId = $templateData['tournamentId'];
      $date = new \DateTime(date('H:i d M Y'));

      $tempFixtures = [];
      $assignedTeams = [];
      $tournamentCompetitionTemplate = null;
      $tournamentTemplateData = [];
      $tournamentTemplateData['json_data'] = '';
      if($ageCategoryId) {
        $tempFixtures = DB::table('temp_fixtures')->where('age_group_id', $ageCategoryId)
          ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
          ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
          ->select(['temp_fixtures.match_number', 'temp_fixtures.display_match_number', 'temp_fixtures.home_team', 'temp_fixtures.home_team_name', 'temp_fixtures.away_team', 'temp_fixtures.away_team_name', 'venues.name as venue_name', 'pitches.pitch_number as pitch_name', 'pitches.size as pitch_size', 'temp_fixtures.is_scheduled as is_scheduled', 'temp_fixtures.match_datetime as match_datetime','temp_fixtures.hometeam_score','temp_fixtures.awayteam_score','temp_fixtures.is_result_override','temp_fixtures.match_winner'])
          ->where('temp_fixtures.deleted_at', NULL)
          ->get()->keyBy('match_number')->toArray();
        $tempFixtures = array_map(function($object){
            return (array) $object;
        }, $tempFixtures);
        $assignedTeams = Team::where('age_group_id', $ageCategoryId)->whereNotNull('competation_id')->get()->toArray();
        $tournamentCompetitionTemplate = TournamentCompetationTemplates::find($ageCategoryId);
      }
      
      $roundMatches = [];
      $divisionMatches = [];
      $allMatches = [];
      $tournamentData = Tournament::where('id', '=', $tournamentId)->select(DB::raw('CONCAT("'.$this->tournamentLogo.'", logo) AS tournamentLogo'))->first();

      if($templateId != NULL) {
          $tournamentTemplate                  = TournamentTemplates::find($templateId);
          $tournamentTemplateData['json_data'] = $tournamentTemplate->json_data;
          $tournamentTemplateData['image']     = $tournamentTemplate->image;
      } else if($tournamentFormat === 'basic' && $competitionType === 'league') {
          $data['total_teams'] = $numberOfTeams;
          $tournamentTemplateData['json_data'] = $this->generateTemplateJsonForLeague($data);
      } else {
          $tournamentTemplateData['json_data'] = $tournamentCompetitionTemplate->template_json_data;
      }
      $jsonData = json_decode($tournamentTemplateData['json_data'], true);
      $roundMatches = TemplateRepository::getMatches($jsonData['tournament_competation_format']['format_name']);
      if(isset($jsonData['tournament_competation_format']['divisions'])) {
          foreach($jsonData['tournament_competation_format']['divisions'] as $divisionIndex => $division) {
              $matches = TemplateRepository::getMatches($division['format_name']);
              $divisionMatches = array_merge($divisionMatches, $matches);
          }
      }
      $allMatches = array_merge($roundMatches, $divisionMatches);

      $pdf = PDF::loadView('age_category.match_schedule_graphic', [
              'fixtures' => $tempFixtures,
              'templateData' => $jsonData,
              'assignedTeams' => $assignedTeams,
              'categoryAge' => $tournamentCompetitionTemplate ? $tournamentCompetitionTemplate->category_age : null,
              'groupName' => $tournamentCompetitionTemplate ? $tournamentCompetitionTemplate->group_name : null,
              'allMatches' => $allMatches,
              'tournamentData' => $tournamentData,
              'date' => $date->format('H:i d M Y'),
          ])
          ->setPaper('a4')
          ->setOption('header-spacing', '0')
          ->setOption('footer-spacing', '0')
          ->setOrientation('landscape')
          ->setOption('footer-html', request()->secure() ? secure_url(URL::route('match.schedule.pdf.footer', [], false)) : route('match.schedule.pdf.footer'))
          ->setOption('margin-left', 5)
          ->setOption('margin-right', 5)
          ->setOption('margin-top', 5)
          ->setOption('margin-bottom', 5);
      return $pdf->inline('Match Schedule – Template '. $jsonData['tournament_name'] . '.pdf');
    }
}
