<?php

namespace Laraspace\Api\Services;

use Laraspace\Models\Tournament;
use Laraspace\Api\Contracts\AgeGroupContract;
use Laraspace\Api\Repositories\AgeGroupRepository;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Team;
use Laraspace\Models\Position;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Traits\TournamentAccess;
use Laraspace\Models\Referee;
use Laraspace\Models\Competition;

class AgeGroupService implements AgeGroupContract
{
  use TournamentAccess;

    public function __construct(AgeGroupRepository $ageRepoObj)
    {
        $this->ageGroupObj = $ageRepoObj;
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
        $this->matchServiceObj = new \Laraspace\Api\Services\MatchService();
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

        // if(($totalCheckTeams > $maximumTeams)) {
        //   return ['status_code' => '403', 'message' => 'This category cannot be added as it exceeds the maximum teams set for this tournament.'];
        // }

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

        if($data['competition_type'] === 'league' || $data['competition_type'] === 'knockout') {
          $data['tournamentTemplate'] = [];
          $data['tournamentTemplate']['id'] = null;
          if($data['competition_type'] === 'league') {
            $data['tournamentTemplate']['json_data'] = $this->generateTemplateJsonForLeague($data['total_teams']);
          } else if($data['competition_type'] === 'knockout') {
            $data['tournamentTemplate']['json_data'] = $this->generateTemplateJsonForKnockout($data['total_teams'], $data['group_size']);
          }
        }

        if($data['tournament_format'] === 'advance' || $data['tournament_format'] === 'festival') {
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
            $mininterval = $tournamentTemplateObj->team_interval;
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
            if($data['tournament_template_id'] != $data['tournamentTemplate']['id'] ) {
                $this->ageGroupObj->deleteCompetationData($data);

                $id = $data['competation_format_id'];
                $this->addCompetationGroups($id,$data);

                // Add positions to template
                $this->insertPositions($data['competation_format_id'], $data['tournamentTemplate']);

            } else {
             
              if($data['team_interval'] != $mininterval) {
               
              $teamsList = Team::where('age_group_id',$data['competation_format_id'])->pluck('id')->toArray();
 
                $tournamentId = $data['tournament_id'];
                $ageGroupId  = $data['competation_format_id'];

                $matchData = array('tournamentId'=>$tournamentId, 'ageGroupId'=>$ageGroupId);
                $matchresult =  $this->matchRepoObj->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);                
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
        // Now here we insert Fixtures
        $this->ageGroupObj->addFixturesIntoTemp($fixture_array,$competation_array,$fixture_match_detail_array, $categoryAge);

        //exit;

    }
    private function calculateTime($json_data, $data) {
        $json_data = json_decode($json_data);

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

    public function generateTemplateJsonForLeague($totalTeams)
    {
      $matches = $this->setTemplateMatches($totalTeams, $timesPlayedEachOther = 2, 'A');
      $totalMatchesCount = count($matches);
      $averageMatches = $totalMatchesCount / ($totalTeams/2);
      $totalRounds = 1;
      $totalGroups = 1;

      $finalArray = [];
      $finalArray['total_matches'] = $totalMatchesCount;
      $finalArray['tournament_id'] = '';
      $finalArray['tournament_teams'] = $totalTeams;
      $finalArray['remark'] = '';
      $finalArray['template_font_color'] = '';
      $finalArray['tournament_name'] = '';
      $finalArray['competition_round'] = 'RR 1-' .$totalTeams;
      $finalArray['competition_group_round'] = '1*' .$totalTeams;
      $finalArray['competation_format'] = '';
      $finalArray['tournament_min_match'] = '';
      $finalArray['avg_game_team'] = $averageMatches;
      $finalArray['position_type'] = 'group_ranking';
      $finalArray['tournament_competition_ranking'] = [];
      $finalArray['tournament_competition_ranking']['format_name'] = [];
      $finalArray['tournament_competition_graphic_view'] = [];
      $finalArray['tournament_competition_graphic_view']['format_name'] = [];
      $finalArray['tournament_competation_format'] = [];
      $finalArray['tournament_competation_format']['format_name'] = [];
      $finalArray['tournament_positions'] = [];

      // for rounds
      for ($rounds = 0; $rounds < $totalRounds ; $rounds++) {
        $finalArray['tournament_competation_format']['format_name'][$rounds]['name'] = 'Round ' .($rounds+1);

        // for groups
        $groupCount = 0;
        for ($groups = 0; $groups < $totalGroups; $groups++) {
          $finalGroupCount = 65 + $groupCount + $groups;
          $matchTypeDetail = [
            'name' => '',
            'total_match' => '',
            'group_count' => '',
            'groups' => ['group_name' => 'Group-' .chr($finalGroupCount), 'match' => $matches]
          ];

          $finalArray['tournament_competation_format']['format_name'][$rounds]['match_type'][] = $matchTypeDetail;
        }        
        $groupCount++;
      }

      $positions = [];
      for ($i=1; $i <= $totalTeams; $i++) {
        $positions[] = ['position' => $i, 'dependent_type' => 'match', 'match_number' => '', 'result_type' => ''];
      }

      $finalArray['tournament_positions'] = $positions;
      // echo "<pre>";print_r($finalArray);echo "</pre>";exit;
      return json_encode($finalArray);
    }

    public function generateTemplateJsonForKnockout($totalTeams, $groupSize)
    {
      $totalGroups = $totalTeams / $groupSize;
      $finalTeams = $totalTeams / $totalGroups;
      $teamsPerGroup = $totalTeams / $totalGroups;
      $teamsForRoundTwo = [];

      $finalArray = [];
      // $finalArray['total_matches'] = $totalMatchesCount;
      $finalArray['tournament_id'] = '';
      $finalArray['tournament_teams'] = $totalTeams;
      $finalArray['remark'] = '';
      $finalArray['template_font_color'] = '';
      $finalArray['tournament_name'] = '';
      $finalArray['competition_round'] = 'RR 1-' .$totalTeams;
      $finalArray['competition_group_round'] = '1*' .$totalTeams;
      $finalArray['competation_format'] = '';
      $finalArray['tournament_min_match'] = '';
      // $finalArray['avg_game_team'] = $averageMatches;
      $finalArray['position_type'] = 'group_ranking';
      $finalArray['tournament_competition_ranking'] = [];
      $finalArray['tournament_competition_ranking']['format_name'] = [];
      $finalArray['tournament_competition_graphic_view'] = [];
      $finalArray['tournament_competition_graphic_view']['format_name'] = [];
      $finalArray['tournament_competation_format'] = [];
      $finalArray['tournament_competation_format']['format_name'] = [];
      $finalArray['tournament_positions'] = [];
      $finalMatches = 0;

      // for 1st round
      $totalRounds = 1;
      for ($round = 0; $round < $totalRounds ; $round++) {
        $finalArray['tournament_competation_format']['format_name'][$round]['name'] = 'Round ' .($round+1);

        // for groups
        $groupCount = 0;
        for ($group = 0; $group < $totalGroups; $group++) {
          $finalGroupCount = chr(65 + $groupCount + $group);
          $matches = $this->setTemplateMatches($finalTeams, $timesPlayedEachOther = 1, $finalGroupCount);
          $matchTypeDetail = [
            'name' => '',
            'total_match' => '',
            'group_count' => '',
            'groups' => ['group_name' => 'Group-' .$finalGroupCount, 'match' => $matches]
          ];

          $finalArray['tournament_competation_format']['format_name'][$round]['match_type'][] = $matchTypeDetail;
        }
        $groupCount++;

        $finalMatches = count($matches) + $finalMatches;

        $knockoutRoundSizeArray = config('config-variables.knockout_round_two_size');
        $roundSizeData = $knockoutRoundSizeArray[$groupSize][$totalTeams];
        $finalRounds = log($roundSizeData, 2);  

        // prepare teams array for round 2        
        for ($group=0; $group <$totalGroups ; $group++) {
          $key = 0;
          for ($i=1; $i < $teamsPerGroup ; $i++) {
            if($key < 2) {
              $teamsForRoundTwo[] = $i .chr(65 + $group);
              $key++;
            }
          }
        }

        if($roundSizeData > sizeof($teamsForRoundTwo)) {
          $remainingTeams = $roundSizeData - sizeof($teamsForRoundTwo);
          for ($group=0; $group <$totalGroups ; $group++) {
            for ($i=3; $i < $teamsPerGroup ; $i++) {
              if($remainingTeams > 0) {
                $teamsForRoundTwo[] = ($group+1). '#' .$i;
                $remainingTeams--;
              }
            }
          }
        }
      }


      // for other rounds
      for ($otherRound = 1; $otherRound < $finalRounds; $otherRound++) {
        $finalArray['tournament_competation_format']['format_name'][$round]['name'] = 'Round ' .($round+1);
        // for groups
        $otherGroupCount = 0;
        for ($otherGroup = 0; $otherGroup < $totalGroups; $otherGroup++) {
          $otherGroupCount = chr(65 + $otherGroupCount + $otherGroup);

          $matchesForOtherRound = $this->setTemplateMatchesForOtherRounds($teamsForRoundTwo, $finalRounds, $finalGroupCount);
          // echo "<pre>";print_r($matchesForOtherRound);echo "</pre>";exit;
          $matchTypeDetailForOtherRound = [
            'name' => '',
            'total_match' => '',
            'group_count' => '',
            'groups' => ['group_name' => 'Group-' .$finalGroupCount, 'match' => $matchesForOtherRound]
          ];
          $finalArray['tournament_competation_format']['format_name'][$round]['match_type'][] = $matchTypeDetailForOtherRound;
        }
        $otherGroupCount++;
      }

      $totalMatchesCount = $finalMatches * $totalGroups;
      $averageMatches = $totalMatchesCount / ($totalTeams/2);
      $finalArray['total_matches'] = $totalMatchesCount;
      $finalArray['avg_game_team'] = $averageMatches;

      $positions = [];
      for ($i=1; $i <= $totalTeams; $i++) {
        $positions[] = ['position' => $i, 'dependent_type' => 'match', 'match_number' => '', 'result_type' => ''];
      }

      $finalArray['tournament_positions'] = $positions;

      echo "<pre>";print_r(json_encode($finalArray));echo "</pre>";exit;

      return json_encode($finalArray);
    }

    public function setTemplateMatches($totalTeams, $times, $currentGroup)
    {
      $a = 1;
      $matches = [];
      for($i=0; $i<$times; $i++){
        for($j=1; $j<=$totalTeams; $j++) {
          for($k=($j+1); $k<=$totalTeams; $k++) {
            $matches[] = ['in-between' => $j. '-' .$k,
                          'match_number' => ($a > 9 ? "CAT.PM2.$a.$currentGroup$j-$currentGroup$k" : "CAT.PM2.0$a.$currentGroup$j-$currentGroup$k"),
                          'display_match_number' => "CAT.1.$a.@HOME-@AWAY",
                          'display_home_team_placeholder_name' => "$currentGroup$j",
                          'display_away_team_placeholder_name' => "$currentGroup$k"
                        ];
            $a++;
          }
        }
      }

      return $matches;
    }

    public function setTemplateMatchesForOtherRounds($teamsInGroups, $finalRounds, $currentGroup) 
    {
      $matches = [];
      for($k=1;$k<=$finalRounds;$k++)
      {
        $currentMatch = 1;
        $currentRound = $k + 1;
        $previousRound= $k - 1;

        if ($k != 1)
        {
          $teamsInGroups = $matches[$k-1];
        }

        list($group1, $group2) = array_chunk($teamsInGroups, ceil(count($teamsInGroups) / 2));

        foreach ($group1 as $key => $value) {
          $incKey = $key + 1;
          $decKey = $key - 1;

          if (sizeof($group1) == 1 || sizeof($group1) == 2)
          {
            if ($k != 1)
            {
              // $matches[$k][] = "Round ".($k-1)." match ".array_search($group1[$key], $matches[$k-1])." winner VS Round ".($k-1)." match ".array_search($group2[$key], $matches[$k-1])." winner";
              
              $previousRoundHomeTeam = $matches[$k-1][$key]['display_home_team_placeholder_name'];
              $previousRoundAwayTeam = $matches[$k-1][$key]['display_away_team_placeholder_name'];

              $previousRoundHomeTeam1 = $matches[$k-1][$key + 2]['display_home_team_placeholder_name'];
              $previousRoundAwayTeam1 = $matches[$k-1][$key + 2]['display_away_team_placeholder_name'];
            
              $matches[$k][] = [
                'in-between' => "CAT.PM$previousRound.G$currentMatch.WR '-' .CAT.PM$previousRound.G".($currentMatch+2). "WR",
                'match_number' => "CAT.PM".$k.".G".$currentMatch.".".$previousRoundHomeTeam."_".$previousRoundAwayTeam."_WR".
                                  '-' .$previousRoundHomeTeam1."_".$previousRoundAwayTeam1."_WR",
                'display_match_number' => "CAT.$currentRound.$currentMatch.wrs.@HOME-@AWAY",
                'display_home_team_placeholder_name' => $previousRoundHomeTeam,
                'display_away_team_placeholder_name' => $previousRoundAwayTeam
              ];

              echo "<pre>";print_r($matches);echo "</pre>";exit;
              $currentMatch++;
            }
            else
            {
              $matches[$k][] = $group1[$key]. ' VS '. $group2[$key];
            }
          }
          else
          {
           if ($key % 2 == 0)
           {
              if ($k != 1)
              {
                $matches[$k][] = "Round ".($k-1)." match ".array_search($group1[$key], $matches[$k-1])." winner VS Round ".($k-1)." match ".array_search($group2[$key+1], $matches[$k-1])." winner";
              }
              else
              {
                // $matches[$k][] = $group1[$key]. ' VS '. $group2[$key+1];
                $matches[$k][] = [
                              'in-between' => $group1[$key]. '-' .$group2[$incKey],
                              'match_number' => "CAT.PM$k.G$currentMatch.$group1[$key]-$group2[$incKey]",
                              'display_match_number' => "CAT.$currentRound.$currentMatch.@HOME-@AWAY",
                              'display_home_team_placeholder_name' => "$group1[$key]",
                              'display_away_team_placeholder_name' => "$group2[$incKey]"
                            ];
                $currentMatch++;
              }
           }
           else
           {    
            if ($k != 1)
            {
              $matches[$k][] = "Round ".($k-1)." match ".array_search($group1[$key], $matches[$k-1])." winner VS Round ".($k-1)." match ".array_search($group2[$key-1], $matches[$k-1])." winner";
            }
            else
            {
              // $matches[$k][] = $group1[$key]. ' VS '. $group2[$key-1];
              $matches[$k][] = [
                            'in-between' => $group1[$key]. '-' .$group2[$decKey],
                            'match_number' => "CAT.PM$k.G$currentMatch.$group1[$key]-$group2[$decKey]",
                            'display_match_number' => "CAT.$currentRound.$currentMatch.@HOME-@AWAY",
                            'display_home_team_placeholder_name' => "$group1[$key]",
                            'display_away_team_placeholder_name' => "$group2[$decKey]"
                          ];
              $currentMatch++;
            }
           }
          }
        }

        $currentRound++;
      }
    }

    public function copyAgeCategory($data)
    {
      $copiedAgeCategory = TournamentCompetationTemplates::where('id', $data['ageCategoryData']['copiedAgeCategoryId'])->first();

      $newCopiedAgeCategory = $copiedAgeCategory->replicate();
      $newCopiedAgeCategory->group_name = $data['ageCategoryData']['competition_format']['ageCategory_name'];
      $newCopiedAgeCategory->category_age = $data['ageCategoryData']['competition_format']['category_age'];
      $newCopiedAgeCategory->pitch_size = $data['ageCategoryData']['competition_format']['pitch_size'];
      $newCopiedAgeCategory->save();

      $newCopiedAgeCategoryDataArray = $newCopiedAgeCategory->toArray();
      $templateData = (array) $this->ageGroupObj->FindTemplate($newCopiedAgeCategoryDataArray['tournament_template_id']);
      $newCopiedAgeCategoryDataArray['ageCategory_name'] = $data['ageCategoryData']['competition_format']['ageCategory_name'];
      $newCopiedAgeCategoryDataArray['tournamentTemplate'] = $templateData;

      $this->addCompetationGroups($newCopiedAgeCategory->id, $newCopiedAgeCategoryDataArray);
      $this->insertPositions($newCopiedAgeCategory->id, $newCopiedAgeCategoryDataArray['tournamentTemplate']);
      
      return ['status_code' => '200', 'data' => $newCopiedAgeCategory, 'message' => 'Data Sucessfully Inserted'];
    }

    public function viewTemplateGraphicImage($data)
    {
        return $this->ageGroupObj->viewTemplateGraphicImage($data);
    }
}
