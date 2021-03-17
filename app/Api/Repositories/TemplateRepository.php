<?php

namespace Laraspace\Api\Repositories;

use DB;
use Auth;
use Laraspace\Models\Team;
use Laraspace\Models\User;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Api\Services\AgeGroupService;
use Illuminate\Pagination\Paginator;

class TemplateRepository
{
    use AuthUserDetail;
    protected $ageGroupService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AgeGroupService $ageGroupService)
    {
        $this->ageGroupService = $ageGroupService;
    }
    /*
     * Get all templates
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTemplates($data)
    {
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        $templates = TournamentTemplates::leftjoin('users', 'tournament_template.created_by', '=', 'users.id');                                        
        if(isset($data['teamSearch']) && $data['teamSearch'] !== '') {
            $templates->where('total_teams', $data['teamSearch']);
        }
        if(isset($data['createdBySearch']) && $data['createdBySearch'] !== '') {
            $templates->where('created_by', $data['createdBySearch']);
        }

        if($loggedInUser->hasRole('tournament.administrator')) {
            $templates->where('created_by', $loggedInUser->id);
        }

        $templates->whereNull('tournament_template.deleted_at');
        $templates->orderBy('tournament_template.name');
        $templates->select('tournament_template.*', 'users.email as userEmail');
        $templatesData = $templates->get();
        
        $currentPage = $data['currentPage']; 
        Paginator::currentPageResolver(function () use ($currentPage) {

          return $currentPage;
        });
        return $templates->paginate($data['noOfRecords']);
    }

    /*
     * Get template details
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTemplateDetail($templateId)
    {
        $tournamentTemplates = TournamentCompetationTemplates::leftjoin('tournaments', 'tournament_competation_template.tournament_id', '=', 'tournaments.id')
                                                            ->where('tournament_template_id', $templateId)
                                                            ->select('tournament_competation_template.*', 'tournaments.name as templateName')
                                                            ->get();

        $tournamentTemplates = $tournamentTemplates->groupBy(function($item) {
            return $item->templateName;
        });

        return $tournamentTemplates;
    }

    /*
     * Get users for filter
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getUsersForFilter()
    {
        $users = DB::table('users')
                    ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->where('roles.slug', '!=', 'mobile.user')
                    ->where('users.deleted_at', '=', NULL)
                    ->select('users.id', 'users.email')
                    ->get();

        return $users;
    }

    /*
     * Save template detail
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function saveTemplateDetail($data)
    {
        $templateJson = $this->generateTemplateJson($data);
        $templateData = $this->insertTemplate($data, $templateJson);
        return $templateData;
    }

    /*
     * Edit template
     *
     * @param  array $id
     * @return response
     */
    public function editTemplate($id)
    {
        $tournamentTemplate = TournamentTemplates::where('id', $id)->first();
        return $tournamentTemplate;
    }

    /*
     * Update template
     *
     * @param  array $id
     * @return response
     */
    public function updateTemplateDetail($data)
    {
        $templateJson = $this->generateTemplateJson($data);
        $tournamentCompetationTemplateCount = TournamentCompetationTemplates::where('tournament_template_id', $data['editedTemplateId'])->count();
        $tournamentTemplate = TournamentTemplates::findOrFail($data['editedTemplateId']);

        if((json_encode($data['templateFormDetail']) != $tournamentTemplate->template_form_detail) && $tournamentCompetationTemplateCount > 0) {
            $tournamentTemplate->is_latest = 0;
            $tournamentTemplate->save();

            $data['version'] = $tournamentTemplate->version + 1;
            $data['inherited_from'] = $tournamentTemplate->id;
            return $this->insertTemplate($data, $templateJson);
        }

        $templateData = $this->updateTemplate($data, $templateJson);

        return $templateData;
    }

    /*
     * Genereate template JSON
     *
     * @param  array $id
     * @return response
     */
    public function generateTemplateJson($data)
    {
        $teamsPlayedEachOther = [
            'once' => '1',
            'twice' => '2',
            'three_times' => '3',
            'four_times' => '4'
        ];

        $templateFormDetail = $data['templateFormDetail'];
        $totalTeams = $templateFormDetail['stepone']['no_of_teams'];
        $finalArray = [];
        $finalArray['tournament_teams'] = $totalTeams;
        $templateFormDetail['stepone']['remarks'] ? $finalArray['remark'] = $templateFormDetail['stepone']['remarks'] : null;
        $finalArray['template_font_color'] = $templateFormDetail['stepone']['template_font_color'];
        $finalArray['tournament_name'] = $templateFormDetail['stepone']['templateName'];
        $finalArray['tournament_competation_format'] = [];
        $finalArray['tournament_competation_format']['format_name'] = [];
        $finalArray['tournament_positions'] = [];

        $rounds = [];
        $roundGroupCount = 0;
        $placingGroupCount = 0;
        $bothSameTeamTypes = false;
        $tournamentsPositionsData = [];
        $totalMatches = 0;

        $inBetween = '';
        $matchNumber = '';
        $displayMatchNumber = '';
        $displayHomeTeamPlaceholderName = '';
        $displayAwayTeamPlaceholderName = '';

        foreach ($templateFormDetail['steptwo']['rounds'] as $roundIndex => $round) {
            $roundDetail = [];
            $roundDetail['round'] = $round;
            $roundDetail['roundIndex'] = $roundIndex;
            $roundDetail['divisionIndex'] = -1;
            $this->processRound($finalArray, $roundGroupCount, $placingGroupCount, $totalMatches, $roundDetail, null, $templateFormDetail);
        }

        foreach ($templateFormDetail['steptwo']['divisions'] as $divisionIndex => $division) {
            $divisionTeams = $division['teams'];
            $divisionNoOfTeam = $division['no_of_teams'];
            $divisionStartRoundCount = $division['start_round_count'];
            foreach ($division['rounds'] as $roundIndex => $round) {
                $roundDetail = [];
                $divisionDetail = [];
                $roundDetail['round'] = $round;
                $roundDetail['roundIndex'] = $roundIndex;
                $roundDetail['divisionIndex'] = $divisionIndex;
                $divisionDetail['divisionTeams'] = $divisionTeams;
                $divisionDetail['divisionNoOfTeam'] = $divisionNoOfTeam;
                $divisionDetail['divisionStartRoundCount'] = $divisionStartRoundCount;
                $this->processRound($finalArray, $roundGroupCount, $placingGroupCount, $totalMatches, $roundDetail, $divisionDetail, $templateFormDetail);
            }
        }
        
        if($templateFormDetail['stepone']['editor'] !== 'knockout') {
            $placings = $templateFormDetail['stepthree']['placings'];
            foreach($placings as $placingIndex => $placing) {
                $roundGroupPositionArray = explode(',', $placing['position']);
                if((int)$roundGroupPositionArray[0] === -1) {
                    $roundDetail = $finalArray['tournament_competation_format']['format_name'][$roundGroupPositionArray[1]];
                } else {
                    $roundDetail = $finalArray['tournament_competation_format']['divisions'][$roundGroupPositionArray[0]]['format_name'][$roundGroupPositionArray[1]];
                }

                $tournamentsPositionsData[$placingIndex]['position'] = ($placingIndex + 1);
                if($placing['position_type'] == 'winner' || $placing['position_type'] == 'loser') {
                    $groupDetail = $roundDetail['match_type'][$roundGroupPositionArray[2]];
                    $matchNumber = $groupDetail['groups']['match'][$roundGroupPositionArray[3]]['match_number'];
                    $tournamentsPositionsData[$placingIndex]['dependent_type'] = 'match';
                    $tournamentsPositionsData[$placingIndex]['match_number'] = $matchNumber;
                    $tournamentsPositionsData[$placingIndex]['result_type'] = $placing['position_type'];
                }
                if($placing['position_type'] == 'placed') {
                    $tournamentsPositionsData[$placingIndex]['dependent_type'] = 'ranking';
                    if((int)$roundGroupPositionArray[0] === -1) {
                        $roundDataTeam = $templateFormDetail['steptwo']['rounds'][$roundGroupPositionArray[1]];
                    } else {
                        $roundDataTeam = $templateFormDetail['steptwo']['divisions'][$roundGroupPositionArray[0]]['rounds'][$roundGroupPositionArray[1]];
                    }
                    $groupName = $this->getRoundRobinGroupName($roundDataTeam, intval($roundGroupPositionArray[2]));
                    $team = (intval($roundGroupPositionArray[3]) + 1) . $groupName;
                    $tournamentsPositionsData[$placingIndex]['ranking'] = $team;
                }
            }
        }

        if($templateFormDetail['stepone']['editor'] === 'knockout') {
            $this->setRoundTwoOnwardMatchesForKnockout($totalTeams, $templateFormDetail['stepone']['no_of_groups'], $templateFormDetail['stepone']['no_of_teams_in_round_two'], $templateFormDetail['stepfour']['round_two_knockout_teams'], $finalArray, $totalMatches);
        }

        $averageMatches = $this->getAverageMatches($totalMatches, $totalTeams);

        if($templateFormDetail['stepone']['editor'] !== 'knockout') {
            $positionType = $this->getPositionType($tournamentsPositionsData);
            $finalArray['tournament_min_match'] = $templateFormDetail['stepone']['minimum_match'];
            $finalArray['tournament_positions'] = $tournamentsPositionsData;
        }

        $finalArray['total_matches'] = $totalMatches;
        $finalArray['avg_game_team'] = $averageMatches;
        $finalArray['position_type'] = $templateFormDetail['stepone']['editor'] === 'knockout' ? 'final' : $positionType;
        $finalArray['round_schedule'] = $data['templateFormDetail']['stepone']['roundSchedules'];

        if($templateFormDetail['stepone']['editor'] !== 'knockout') {
            foreach($finalArray['tournament_competation_format']['format_name'] as $roundIndex => $round) {
                $templateFormDetailGroup = $templateFormDetail['steptwo']['rounds'][$roundIndex]['groups'];
                $firstPlacingMatchIndex = array_search('placing_match', array_column($templateFormDetailGroup, 'type'));
                $isReorderingRequired = false;
                foreach ($round['match_type'] as $groupIndex => $group) {
                    if($roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex && $templateFormDetailGroup[$groupIndex]['type'] === 'placing_match') {
                        $isReorderingRequired = true;
                        unset($finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][$groupIndex]);
                        // $group['groups']['group_name'] = $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][0]['groups']['group_name'];
                        $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][0]['dependent_groups'][] = $group;
                    }
                }
                if($isReorderingRequired) {
                    $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'] = array_values($finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type']);
                }
            }
        }

        return json_encode($finalArray);
    }


    public function setRoundTwoOnwardMatchesForKnockout($totalTeams, $totalGroups, $noOfTeamsInRoundTwo, $roundTwoKnockoutTeams, &$finalArray, &$totalMatches)
    {
      // $totalGroups = $totalTeams / $groupSize;
      $finalTeams = $totalTeams / $totalGroups;
      $teamsPerGroup = $totalTeams / $totalGroups;
      // $knockoutRoundSizeArray = config('config-variables.knockout_round_two_size');
      // $roundSizeData = $knockoutRoundSizeArray[$groupSize][$totalTeams];
      $finalRounds = log($noOfTeamsInRoundTwo, 2);
      $allRounds = $finalRounds + 1;

      $nextRoundTeams = [];
      $nextRoundMatchesArray = [];
      $matches = [];

      for ($round = 0; $round < $allRounds; $round++) {
        $group1 = [];
        $group2 = [];        
        $finalArray['tournament_competation_format']['format_name'][$round]['name'] = 'Round ' .($round+1);
        if($round == 0) {
          $nextRoundTeams = $this->teamsForRoundTwo($roundTwoKnockoutTeams);
          //shuffle($nextRoundTeams);
        } else {
          $dividedRoundMatches = sizeof($nextRoundTeams) / 2;
          for ($i=0; $i<$dividedRoundMatches; $i++) {
            $group1[] = $nextRoundTeams[$i];
            $group2[] = $nextRoundTeams[$dividedRoundMatches + $i];
          }

          if($round == 1) {
            $group2 = array_reverse($group2);
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
        if($round > 0) {
          $matchTypeDetail = [
            'name' => 'PM-1*' .sizeof($matches[$round]) * 2,
            'total_match' => sizeof($matches[$round]),
            'group_count' => sizeof($matches[$round]) * 2,
            'groups' => ['group_name' => 'Group-PM' .$round, 'match' => $matches[$round]]
          ];
          $finalArray['tournament_competation_format']['format_name'][$round]['match_type'][] = $matchTypeDetail;
          $totalMatches += $matchTypeDetail['total_match'];
        }
      }

      $lastRoundMatches = end($matches);

      $positions = $this->setLastRoundPositionInKnockout($lastRoundMatches);
      $finalArray['tournament_positions'] = $positions;

      return $finalArray;
    }

    public function setLastRoundPositionInKnockout($lastRoundMatches) {
      $positions = [];
      foreach ($lastRoundMatches as $key => $match) {
        if ($key == 0) {
          $positions[0] = ['position' => 1, 'dependent_type' => 'match', 'match_number' => $match['match_number'], 'result_type' => 'winner'];
          $positions[1] = ['position' => 2, 'dependent_type' => 'match', 'match_number' => $match['match_number'], 'result_type' => 'loser'];
        } else {
          $positions[2] = ['position' => 3, 'dependent_type' => 'match', 'match_number' => $match['match_number'], 'result_type' => 'winner'];
          $positions[3] = ['position' => 4, 'dependent_type' => 'match', 'match_number' => $match['match_number'], 'result_type' => 'loser'];
        }
      }
      return $positions;
    }

    public function setTemplateMatchesForSecondRound($group1, $group2, $currentRound)
    {
      $currentRound = $currentRound + 1;
      $secondRoundMatches = [];
      for ($i=0; $i < sizeof($group1); $i++) {
        $currentMatchIndex = $i + 1;

        $matchNumberGroup1 = strpos($group1[$i], '#') > 0 ? str_replace(array('th','st','nd','rd'), array('', '', '', ''), $group1[$i]) : $group1[$i];
        $matchNumberGroup2 = strpos($group2[$i], '#') > 0 ? str_replace(array('th','st','nd','rd'), array('', '', '', ''), $group2[$i]) : $group2[$i];

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

          $loser_match_number = 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.(' .$homeMatchNumber[1]. '_'. $homeMatchNumber[2] .'_LR)-(' .$awayMatchNumber[1]. '_'. $awayMatchNumber[2]. '_LR)';
        } else {

          $nextRoundMatches[] = [
            'in_between' => 'CAT.PM' .$previousRound. '.G' .$homeDisplayMatchNumber[2]. 'WR-CAT.PM' .$previousRound. '.G' .$awayDisplayMatchNumber[2]. 'WR',
            'match_number' => 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.' .$homeMatch. '_WR-' .$awayMatch. '_WR',
            'display_match_number' => 'CAT.' .$currentRound. '.' .$currentMatch. '.wrs.(@HOME-@AWAY)',
            'display_home_team_placeholder_name' => "$previousRound.$homeDisplayMatchNumber[2]",
            'display_away_team_placeholder_name' => "$previousRound.$awayDisplayMatchNumber[2]",
          ];

          $loser_match_number = 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.' .$homeMatch. '_LR-' .$awayMatch. '_LR';
          
        }

        if( sizeof($group1) == 1) {
            $nextRoundMatches[] = [
              'in_between' => 'CAT.PM' .$previousRound. '.G' .$homeDisplayMatchNumber[2]. 'LR-CAT.PM' .$previousRound. '.G' .$awayDisplayMatchNumber[2]. 'LR',
              'match_number' => $loser_match_number,
              'display_match_number' => 'CAT.' .$currentRound. '.' .$currentMatch. '.lrs.(@HOME-@AWAY)',
              'display_home_team_placeholder_name' => "$previousRound.$homeDisplayMatchNumber[2]",
              'display_away_team_placeholder_name' => "$previousRound.$awayDisplayMatchNumber[2]",
            ];
          }
      }

      return $nextRoundMatches;
    }

    public function teamsForRoundTwo($roundTwoKnockoutTeams)
    {
        $teamsForRoundTwo = [];
        foreach ($roundTwoKnockoutTeams as $bestPlaced) {
            for($i = 1; $i <= $bestPlaced['no_of_teams']; $i++){
                $teamsForRoundTwo[] = $this->getOrdinal($i) . '#' . $bestPlaced['position'];
            }
        }

        return $teamsForRoundTwo;
    }

    public function getOrdinal($number) {
      $ends = array('th','st','nd','rd','th','th','th','th','th','th');
      if ((($number % 100) >= 11) && (($number % 100) <= 13))
          return $number. 'th';
      else
          return $number. $ends[$number % 10];
    }

    /*
     * Insert template
     *
     * @param
     * @return response
     */
    public function insertTemplate($data, $templateJson)
    {
        $decodedJson = json_decode($templateJson, true);
        $divisionsCount = isset($decodedJson['tournament_competation_format']['divisions']) ? sizeof($decodedJson['tournament_competation_format']['divisions']) : 0;

        $tournamentTemplate = new TournamentTemplates();
        $tournamentTemplate->json_data = $templateJson;
        $tournamentTemplate->name = $data['templateFormDetail']['stepone']['templateName'];
        $tournamentTemplate->total_teams = $data['templateFormDetail']['stepone']['no_of_teams'];
        $tournamentTemplate->total_groups = $data['templateFormDetail']['stepone']['no_of_groups'] ? $data['templateFormDetail']['stepone']['no_of_groups'] : null;
        $tournamentTemplate->total_teams_in_round_two = $data['templateFormDetail']['stepone']['no_of_teams_in_round_two'] ? $data['templateFormDetail']['stepone']['no_of_teams_in_round_two'] : null;
        $tournamentTemplate->minimum_matches = $data['templateFormDetail']['stepone']['minimum_match'] ? $data['templateFormDetail']['stepone']['minimum_match'] : null;
        $tournamentTemplate->position_type = $decodedJson['position_type'];
        $tournamentTemplate->avg_matches = number_format($decodedJson['avg_game_team'], 1);
        $tournamentTemplate->total_matches = $decodedJson['total_matches'];
        $tournamentTemplate->no_of_divisions = $divisionsCount;
        $tournamentTemplate->editor_type = $data['templateFormDetail']['stepone']['editor'];
        $tournamentTemplate->template_form_detail = json_encode($data['templateFormDetail']);
        $tournamentTemplate->version = array_get($data,'version', 1);
        $tournamentTemplate->inherited_from = array_get($data,'inherited_from', NULL);
        $tournamentTemplate->created_by = Auth::user()->id;
        $tournamentTemplate->save();

        return $tournamentTemplate;
    }

    /*
     * Update template
     *
     * @param  array $id
     * @return response
     */
    public function updateTemplate($data, $templateJson)
    {
        $decodedJson = json_decode($templateJson, true);
        $divisionsCount = isset($decodedJson['tournament_competation_format']['divisions']) ? sizeof($decodedJson['tournament_competation_format']['divisions']) : 0;

        $tournamentTemplate = TournamentTemplates::findOrFail($data['editedTemplateId']);
        $tournamentTemplate->json_data = $templateJson;
        $tournamentTemplate->name = $data['templateFormDetail']['stepone']['templateName'];
        $tournamentTemplate->total_teams = $data['templateFormDetail']['stepone']['no_of_teams'];
        $tournamentTemplate->total_groups = $data['templateFormDetail']['stepone']['no_of_groups'] ? $data['templateFormDetail']['stepone']['no_of_groups'] : null;
        $tournamentTemplate->total_teams_in_round_two = $data['templateFormDetail']['stepone']['no_of_teams_in_round_two'] ? $data['templateFormDetail']['stepone']['no_of_teams_in_round_two'] : null;
        $tournamentTemplate->minimum_matches = $data['templateFormDetail']['stepone']['minimum_match'] ? $data['templateFormDetail']['stepone']['minimum_match'] : null;
        $tournamentTemplate->position_type = $decodedJson['position_type'];
        $tournamentTemplate->avg_matches = number_format($decodedJson['avg_game_team'], 1);
        $tournamentTemplate->total_matches = $decodedJson['total_matches'];
        $tournamentTemplate->no_of_divisions = $divisionsCount;
        $tournamentTemplate->editor_type = $data['templateFormDetail']['stepone']['editor'];
        $tournamentTemplate->template_form_detail = json_encode($data['templateFormDetail']);
        $tournamentTemplate->save();

        return $tournamentTemplate;
    }

    /*
     * Delete template
     *
     * @param  array $id
     * @return response
     */
    public function deleteTemplate($id)
    {
        return TournamentTemplates::find($id)->delete();
    }

    /*
     * Get position type code
     *
     * @param  array $position
     * @return response
     */
    public function getPositionTypeCode($type)
    {
        if($type === 'winner') {
            return 'WR';
        } else if($type === 'loser') {
            return 'LR';
        }
    }

    public function getGroupNameByRoundAndGroupIndex($templateFormDetail, $divisionIndex, $roundIndex, $groupIndex)
    {
        $roundData = null;
        if($divisionIndex === -1) {
            $roundData = $templateFormDetail['steptwo']['rounds'][$roundIndex];
        } else {
            $roundData = $templateFormDetail['steptwo']['divisions'][$divisionIndex]['rounds'][$roundIndex];
        }
        $groupData = $roundData['groups'][$groupIndex];

        if($groupData['type'] === 'round_robin') {
            return 'Group ' + $this->getRoundRobinGroupName($roundData, $groupIndex);
        }

        if($groupData['type'] === 'placing_match') {
            return 'PM ' + $this->getPlacingMatchGroupName($roundData, $groupIndex);
        }
    }

    public function getRoundRobinGroupName($roundData, $groupIndex)
    {
        $currentRoundGroupCount =  $this->getRoundRobinGroupCount($roundData, $groupIndex);
        return chr(65 + $roundData['start_round_group_count'] + $currentRoundGroupCount);
    }

    public function getPlacingMatchGroupName($roundData, $groupIndex)
    {
        $currentPlacingGroupCount =  $this->getPlacingMatchGroupCount($roundData, $groupIndex);
        return ($roundData['start_placing_group_count'] + $currentPlacingGroupCount);
    }

    public function getRoundRobinGroupCount($roundData, $groupIndex)
    {
        return count(array_filter($roundData['groups'], function($o, $index) use($groupIndex) { return ($o['type'] === 'round_robin' && $index < $groupIndex); }, ARRAY_FILTER_USE_BOTH));
    }

    public function getPlacingMatchGroupCount($roundData, $groupIndex)
    {
        return count(array_filter($roundData['groups'], function($o, $index) use($groupIndex) { return ($o['type'] === 'placing_match' && $index <= $groupIndex); }, ARRAY_FILTER_USE_BOTH));
    }

    public function getWinnerOrLooserTeams($teamGroupType, $divisionRoundGroupPosition, $isSamePositionType, $positionType, $startRoundCount, $startMatchCount, $teamType)
    {
        $teamGroupTypeMatchNumber = explode(".", $teamGroupType['match_number']);
        $teamGroupTypeDisplayMatchNumber = explode(".", $teamGroupType['display_match_number']);
        $teams = explode("-", end($teamGroupTypeMatchNumber));

        $teamPlaceholderIndex = intval($divisionRoundGroupPosition[3]) + 1;
        if (strpos($teams[0], 'WR') || strpos($teams[0], 'LR')){
            $groupName = str_replace("-", "_", end($teamGroupTypeMatchNumber));
            $teamMatchNumber = '(' . $teamGroupTypeMatchNumber[1] . '_' . $teamGroupTypeMatchNumber[2] . ($positionType == 'winner' ? '_WR' : '_LR') . ')';
        } else {
            $groupName = str_replace("-", "_", end($teamGroupTypeMatchNumber));
            $teamMatchNumber = $groupName . ($positionType == 'winner' ? '_WR' : '_LR');
        }

        $teamInBetween = $this->getTeamInBetween($teamGroupType, $divisionRoundGroupPosition, $teamPlaceholderIndex, $positionType, $groupName, $startRoundCount, $startMatchCount);
        $teamDisplayMatchNumber =  $this->getTeamDisplayMatchNumber($teamType, $isSamePositionType, $positionType);
        $teamDisplayPlaceholderName = $teamGroupTypeDisplayMatchNumber[1] . '.' . $teamGroupTypeDisplayMatchNumber[2];

        return [
            'teamInBetween' => $teamInBetween,
            'teamMatchNumber' => $teamMatchNumber,
            'teamDisplayMatchNumber' => $teamDisplayMatchNumber,
            'teamDisplayPlaceholderName' => $teamDisplayPlaceholderName
        ];
    }

    public function getTeamDisplayMatchNumber($teamType, $isSamePositionType, $positionType)
    {
        $displayMatchNumber = '';
        if($teamType === 'home' && (($isSamePositionType === true) || ($isSamePositionType === false && $positionType === 'placed'))) {
            $displayMatchNumber = '@HOME';
        }
        if($teamType === 'away' && (($isSamePositionType === true) || ($isSamePositionType === false && $positionType === 'placed'))) {
            $displayMatchNumber = '@AWAY';
        }
        if($teamType === 'home' && $isSamePositionType === false && ($positionType === 'winner' || $positionType === 'loser')) {
            $displayMatchNumber = ($positionType == 'winner' ? 'wrs.' : 'lrs.') . '(@HOME)';
        }
        if($teamType === 'away' && $isSamePositionType === false && ($positionType === 'winner' || $positionType === 'loser')) {
            $displayMatchNumber = ($positionType == 'winner' ? 'wrs.' : 'lrs.') . '(@AWAY)';
        }

        return $displayMatchNumber;
    }

    public function getTeamInBetween($teamGroupType, $divisionRoundGroupPosition, $teamPlaceholderIndex, $positionType, $groupName, $startRoundCount, $startMatchCount)
    {
        $teamGroupTypeMatchNumber = explode(".", $teamGroupType['match_number']);
        if($positionType === 'winner' || $positionType === 'loser') {
            $teamInBetween = $teamGroupTypeMatchNumber[0] . '.' . $teamGroupTypeMatchNumber[1] . '.' . $teamGroupTypeMatchNumber[2] . ($positionType == 'winner' ? 'WR' : 'LR');
        }
        if($positionType === 'placed') {
            $teamInBetween = $teamPlaceholderIndex . $groupName;    
        }

        return $teamInBetween;
    }

    public function getNumberOfTimesFromString($times)
    {
        $teamPlaceEachOther = [
            'once' => 1,
            'twice' => 2,
            'three_times' => 3,
            'four_times' => 4
        ];
        return $teamPlaceEachOther[$times];
    }

    public function getAverageMatches($totalMatches, $numTeams)
    {
       $averageMatches = $totalMatches / ($numTeams / 2);
       return number_format($averageMatches, 1);
    }

    // public function getMinimumMatches($templateFormDetail)
    // {
    //     $minGames = 0;
    //     $rounds = $templateFormDetail['steptwo']['rounds'];
    //     $totalTeams = $templateFormDetail['stepone']['no_of_teams'];

    //     foreach ($rounds as $roundIndex => $round) {
    //         $nGames = [];
    //         if($round['no_of_teams'] < $totalTeams) {
    //             break;
    //         } else {
    //             foreach ($round['groups'] as $groupIndex => $group) {
    //                 if($group['type'] == 'round_robin') {
    //                     $nGames[] = $this->getNumberOfTimesFromString($group['teams_play_each_other']) * ($group['no_of_teams'] - 1);
    //                 }
    //                 if($group['type'] == 'placing_match') {
    //                     $nGames[] = 1;
    //                 }
    //             }
    //         }
    //         $minGames += min($nGames);
    //     }
    //     $minimumMatches = $minGames;

    //     return $minimumMatches;
    // }

    public function getPositionType($positions)
    {
        $positionWithRankingTypeCount = collect($positions)->where('dependent_type', 'ranking')->count();
        $positionWithMatchTypeCount = collect($positions)->where('dependent_type', 'match')->count();
        
        if(sizeof($positions) == $positionWithRankingTypeCount) {
            return 'group_ranking';
        } 
        if(sizeof($positions) == $positionWithMatchTypeCount) {
            return 'final';
        }

        return 'final_and_group_ranking';
    }

    public function processRound(&$finalArray, &$roundGroupCount, &$placingGroupCount, &$totalMatches, $roundDetail, $divisionDetail, $templateFormDetail) {
        $round = $roundDetail['round'];
        $roundIndex = $roundDetail['roundIndex'];
        $divisionIndex = $roundDetail['divisionIndex'];
        $startRoundCount = $divisionIndex >= 0 ? $divisionDetail['divisionStartRoundCount'] : 0;

        $firstPlacingMatchIndex = array_search('placing_match', array_column($round['groups'], 'type'));
        $firstPlacingMatchGroupName = null;
        foreach ($round['groups'] as $groupIndex => $group) {
            $considerInTeamAssignment = false;
            $isPlacingMatchAsRoundRobin = false;
            $matchCount = 1;
            $matches = [];
            $startMatchCount = isset($group['start_match_count']) ? $group['start_match_count'] : 0;

            $groupData = $group;
            $times = $groupData['teams_play_each_other'];
            $noOfTeams = $groupData['no_of_teams'];
            $groupName = null;

            if($group['type'] === "round_robin") {
                $groupName = chr(65 + $roundGroupCount);
            }

            if($group['type'] === "placing_match") {
                $groupName = "PM" . ($placingGroupCount + 1);
            }

            if($divisionIndex === -1 && $roundIndex === 0 && $group['type'] === 'round_robin') {
                // $matches = [];
                $times = $this->getNumberOfTimesFromString($times);
                $fetchRoundMatches = $this->ageGroupService->generateRoundFixturesBaseOnTeam($noOfTeams);

                $matches = $this->ageGroupService->leagueKnockoutJsonMatches($fetchRoundMatches, $roundIndex, $groupName, $times, $startRoundCount);
                $group['matches'] = $matches;
            }

            if($divisionIndex === -1 && $roundIndex === 0 && $groupIndex === $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
                $firstPlacingMatchGroupName = $groupName;
                $considerInTeamAssignment = true;
                for($i=1; $i<=$noOfTeams; $i=$i+2) {
                    $home = $i;
                    $away = $i + 1;
                    $inBetween = $home . "-" . $away;
                    $matchNumber = "CAT.PM" . ($roundIndex+1) . ".G" . $matchCount . "." . $home . "-" . $away;
                    $displayMatchNumber = "CAT." . ($roundIndex+1) . "." . $matchCount . ".@HOME-@AWAY";
                    $displayHomeTeamPlaceholderName = (string) $home;
                    $displayAwayTeamPlaceholderName = (string) $away;

                    array_push($matches, ['in_between' => $inBetween, 'match_number' => $matchNumber, 'display_match_number' => $displayMatchNumber, 'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName, 'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName]);

                    $matchCount++;
                }
            }

            if($divisionIndex === -1 && $roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
                $teams = $group['teams'];
                $groupMatches = $group['matches'];
                
                $totalPlacingMatches = 0;
                $allPlacingMatches = [];
                $prevPlacingMatchesCount = 0;

                foreach($round['groups'] as $index => $o) {
                    if($o['type'] === 'placing_match' && $index < $groupIndex) {
                        $previousMatches  = $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][$index]['groups']['match'];
                        $totalPlacingMatches += count($previousMatches);
                        $allPlacingMatches = array_merge($allPlacingMatches, $previousMatches);
                        $prevPlacingMatchesCount += count($previousMatches);
                    }
                }

                for($i=0; $i<$noOfTeams; $i=$i+2) {
                    if($teams[$i]['position'] !== '' && $teams[$i+1]['position'] !== '') {
                        $divisionRoundGroupPosition1 = explode(',', $teams[$i]['position']);
                        $divisionRoundGroupPosition2 = explode(',', $teams[$i+1]['position']);

                        $position1 = $divisionRoundGroupPosition1[3];
                        $position2 = $divisionRoundGroupPosition2[3];

                        $prevPlacingMatchesCount1 = 0;
                        $prevPlacingMatchesCount2 = 0;

                        foreach($round['groups'] as $index => $o) {
                            if($index < intval($divisionRoundGroupPosition1[2])) {
                                $prevPlacingMatchesCount1 += count($o['matches']);
                            }
                            if($index < intval($divisionRoundGroupPosition2[2])) {
                                $prevPlacingMatchesCount2 += count($o['matches']);
                            }
                        }

                        $positionType1 = $this->getPositionTypeCode($teams[$i]['position_type']);
                        $positionType2 = $this->getPositionTypeCode($teams[$i+1]['position_type']);

                        $homePlaceholder = "CAT.PM" . ($roundIndex + 1) . ".G" . ($prevPlacingMatchesCount1 + intval($position1) + 1);
                        $awayPlaceholder = "CAT.PM" . ($roundIndex + 1) . ".G" . ($prevPlacingMatchesCount2 + intval($position2) + 1);

                        $homePlacingMatch = current(array_filter($allPlacingMatches, function($o) use($homePlaceholder) { return strpos($o['match_number'], $homePlaceholder) !== false; }));
                        $awayPlacingMatch = current(array_filter($allPlacingMatches, function($o) use($awayPlaceholder) { return strpos($o['match_number'], $awayPlaceholder) !== false; }));

                        if($homePlacingMatch && $awayPlacingMatch) {
                            $home = null;
                            $away = null;

                            $teamMatchNumber1 = explode('.', $homePlacingMatch['match_number']);
                            $teamMatchNumber2 = explode('.', $awayPlacingMatch['match_number']);

                            if(strpos($teamMatchNumber1[count($teamMatchNumber1) - 1], 'WR') !== false || strpos($teamMatchNumber1[count($teamMatchNumber1) - 1], 'LR') !== false) {
                                $home = "(" . $teamMatchNumber1[1] . "_" . $teamMatchNumber1[2] . '_' . $positionType1 . ")";
                            } else {
                                $home = str_replace('-', '_', $homePlacingMatch['in_between']) . '_' . $positionType1;
                            }

                            if(strpos($teamMatchNumber2[count($teamMatchNumber2) - 1], 'WR') !== false || strpos($teamMatchNumber2[count($teamMatchNumber2) - 1], 'LR') !== false) {
                                $away = "(" . $teamMatchNumber2[1] . "_" . $teamMatchNumber2[2] . '_' . $positionType2 . ")";
                            } else {
                                $away = str_replace('-', '_', $awayPlacingMatch['in_between']) . '_' . $positionType2;
                            }
                                
                            $inBetween =  $homePlaceholder . $positionType1 . "-" . $awayPlaceholder . $positionType2;
                            $matchNumber = "CAT.PM" . ($roundIndex + 1) . ".G" . ($totalPlacingMatches + $matchCount) . "." . $home . "-" . $away;
                            $displayMatchNumber = null;
                            if($positionType1 === $positionType2 && $positionType1 === 'WR') {
                                $displayMatchNumber = "CAT." . ($roundIndex+1) . "." . ($totalPlacingMatches + $matchCount) . ".wrs.(@HOME-@AWAY)";
                            } elseif($positionType1 === $positionType2 && $positionType1 === 'LR') {
                                $displayMatchNumber = "CAT." . ($roundIndex+1) . "." . ($totalPlacingMatches + $matchCount) . ".lrs.(@HOME-@AWAY)";
                            }

                            $displayHomeTeamPlaceholderName = ($roundIndex + 1) . "." . ($prevPlacingMatchesCount1 + intval($position1) + 1);
                            $displayAwayTeamPlaceholderName = ($roundIndex + 1) . "." . ($prevPlacingMatchesCount2 + intval($position2) + 1);

                            $matchDetailArray = [
                                'in_between' => $inBetween,
                                'match_number' => $matchNumber,
                                'display_match_number' => $displayMatchNumber,
                                'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName,
                                'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName,
                            ];

                            if((int)$groupMatches[($i/2)]['is_final'] === 1) {
                                $matchDetailArray['is_final_match'] = 1;
                            }

                            array_push($matches, $matchDetailArray);

                            $matchCount++;
                        }
                    }
                }
            }

            if(( (($divisionIndex > -1 && $roundIndex === 0) || $roundIndex > 0) && $group['type'] === "round_robin")) {
                $times = $this->getNumberOfTimesFromString($times);
                $fetchRoundMatches = $this->ageGroupService->generateRoundFixturesBaseOnTeam($noOfTeams);

                $teams = $groupData['teams'];
                if($divisionIndex > -1 && $roundIndex === 0) {
                    $teams = [];
                    for ($teamIndex = 0; $teamIndex < count($group['teams']); $teamIndex++) {
                        $teams[$teamIndex] = $divisionDetail['divisionTeams'][$group['teams'][$teamIndex]['position']];
                    }
                }

                for($i=0; $i<$times; $i++){
                    foreach ($fetchRoundMatches as $key => $week) {
                        foreach ($week as $wkey => $match) {
                            $weekNumber = $i*count($fetchRoundMatches) + $key;
                            list($home,$away) = explode('-',$match);

                            $inBetween = null;
                            $matchNumber = null;
                            $displayMatchNumber = null;
                            $displayHomeTeamPlaceholderName = null;
                            $displayAwayTeamPlaceholderName = null;
                            $team1 = $teams[intval($home) - 1];
                            $team2 = $teams[intval($away) - 1];
                            $divisionRoundGroupPosition1 = explode(',', $team1['position']);
                            $divisionRoundGroupPosition2 = explode(',', $team2['position']);
                            $teamRoundData1 = null;
                            $teamGroupType1 = null;
                            $homeTeamData = null;
                            $teamRoundData2 = null;
                            $teamGroupType2 = null;
                            $awayTeamData = null;
                            $teamType = '';
                            $isSamePositionType = ($team1['position_type'] === $team2['position_type']);

                            if($team1['position_type'] === 'placed') {
                                $teamRoundData1 = $this->getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition1, $divisionIndex);
                                $teamGroupType1 = $teamRoundData1['groups'][$divisionRoundGroupPosition1[2]]['type'];
                                $homeTeamData = $this->getRoundRobinTeamData($teamGroupType1, $teamRoundData1, $divisionRoundGroupPosition1, 'home');
                            }
                            if($team1['position_type'] === 'winner' || $team1['position_type'] === 'loser') {
                                $teamRoundData1 = $this->getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition1, $divisionIndex);
                                $teamGroupType1 = $teamRoundData1['match_type'][$divisionRoundGroupPosition1[2]]['groups']['match'][$divisionRoundGroupPosition1[3]];
                                $homeTeamData = $this->getWinnerOrLooserTeams($teamGroupType1, $divisionRoundGroupPosition1, $isSamePositionType, $team1['position_type'], $startRoundCount, $startMatchCount, 'home');
                            }

                            if($team2['position_type'] === 'placed') {
                                $teamRoundData2 = $this->getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition2, $divisionIndex);
                                $teamGroupType2 = $teamRoundData2['groups'][$divisionRoundGroupPosition2[2]]['type'];
                                $awayTeamData = $this->getRoundRobinTeamData($teamGroupType2, $teamRoundData2, $divisionRoundGroupPosition2, 'away');
                            }
                            if($team2['position_type'] === 'winner' || $team2['position_type'] === 'loser') {
                                $teamRoundData2 = $this->getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition2, $divisionIndex);
                                $teamGroupType2 = $teamRoundData2['match_type'][$divisionRoundGroupPosition2[2]]['groups']['match'][$divisionRoundGroupPosition2[3]];
                                $awayTeamData = $this->getWinnerOrLooserTeams($teamGroupType2, $divisionRoundGroupPosition2, $isSamePositionType, $team2['position_type'], $startRoundCount, $startMatchCount, 'away');
                            }

                            if($divisionRoundGroupPosition1[0] == -1) {
                                $teamMatch1 = $finalArray['tournament_competation_format']['format_name'][$divisionRoundGroupPosition1[1]]['match_type'][$divisionRoundGroupPosition1[2]]['groups']['match'][$divisionRoundGroupPosition1[3]]['match_number'];
                            } else {
                                $teamMatch1 = $finalArray['tournament_competation_format']['divisions'][$divisionRoundGroupPosition1[0]]['format_name'][$divisionRoundGroupPosition1[1]]['match_type'][$divisionRoundGroupPosition1[2]]['groups']['match'][$divisionRoundGroupPosition1[3]]['match_number'];
                            }

                            if($divisionRoundGroupPosition2[0] == -1) {
                                $teamMatch2 = $finalArray['tournament_competation_format']['format_name'][$divisionRoundGroupPosition2[1]]['match_type'][$divisionRoundGroupPosition2[2]]['groups']['match'][$divisionRoundGroupPosition2[3]]['match_number'];
                            } else {
                                $teamMatch2 = $finalArray['tournament_competation_format']['divisions'][$divisionRoundGroupPosition2[0]]['format_name'][$divisionRoundGroupPosition2[1]]['match_type'][$divisionRoundGroupPosition2[2]]['groups']['match'][$divisionRoundGroupPosition2[3]]['match_number'];
                            }

                            if(($team1['position_type'] == 'winner' || $team2['position_type'] == 'winner') || ($team1['position_type'] == 'loser' || $team2['position_type'] == 'loser')) {
                                $isPlacingMatchAsRoundRobin = true;
                            }

                            if(($team1['position_type'] == 'winner' && $team2['position_type'] == 'winner') || ($team1['position_type'] == 'loser' && $team2['position_type'] == 'loser')) {
                                $teamType = $team1['position_type'] === 'winner' ? 'wrs.' : 'lrs.';
                            }

                            $inBetween = $homeTeamData['teamInBetween'] . '-' . $awayTeamData['teamInBetween'];
                            $matchNumber = 'CAT.RR'. ($startRoundCount + $roundIndex + 1) . '.' . sprintf('%02d', $weekNumber) . '.' . $homeTeamData['teamMatchNumber'] . '-' . $awayTeamData['teamMatchNumber'];
                            
                            if($teamType !== '') {
                                $displayMatchNumber = 'CAT.'. ($startRoundCount + $roundIndex + 1) . '.' . $weekNumber . '.' . $teamType . '(' . $homeTeamData['teamDisplayMatchNumber'] . '-' . $awayTeamData['teamDisplayMatchNumber'] . ')';
                            } else {
                                $displayMatchNumber = 'CAT.'. ($startRoundCount + $roundIndex + 1) . '.' . $weekNumber . '.' . $homeTeamData['teamDisplayMatchNumber'] . '-' . $awayTeamData['teamDisplayMatchNumber'];
                            }
                            
                            $displayHomeTeamPlaceholderName = $homeTeamData['teamDisplayPlaceholderName'];
                            $displayAwayTeamPlaceholderName = $awayTeamData['teamDisplayPlaceholderName'];

                            array_push($matches, ['in_between' => $inBetween, 'match_number' => $matchNumber, 'display_match_number' => $displayMatchNumber, 'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName, 'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName]);
                        }
                    }
                }
            }

            if(($divisionIndex === -1 && $roundIndex > 0 && $group['type'] === "placing_match") || ($divisionIndex > -1 && $roundIndex >= 0 && $group['type'] === "placing_match")) {
                //dd($divisionDetail);
                $teams = $group['teams'];
                $groupMatches = $group['matches'];
                if($divisionIndex >= -1 && $roundIndex === 0) {
                    $teams = [];
                    for ($teamIndex = 0; $teamIndex < count($group['teams']); $teamIndex++) {
                        $teams[$teamIndex] = $divisionDetail['divisionTeams'][$group['teams'][$teamIndex]['position']];
                    }
                }
                // print_r($teams);
                for ($teamIndex = 0; $teamIndex < count($teams); $teamIndex++) {
                    $position = null;
                    $bothSameTeamTypes = false;
                    
                    $currentRound = $roundIndex + 1;
                    $currentMatch = ($teamIndex/2) + 1;

                    $divisionRoundGroupPosition1 = explode(',', $teams[$teamIndex]['position']);
                    $divisionRoundGroupPosition2 = explode(',', $teams[$teamIndex + 1]['position']);

                    $homePositionType = $teams[$teamIndex]['position_type'];
                    $awayPositionType = $teams[$teamIndex + 1]['position_type'];

                    if(($homePositionType == 'winner' && $awayPositionType == 'winner') || ($homePositionType == 'loser' && $awayPositionType == 'loser')) {
                        $bothSameTeamTypes = true;
                        $isSamePositionType = true;

                        $teamRoundData1 = $this->getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition1, $divisionIndex);
                        $teamGroupType1 = $teamRoundData1['match_type'][$divisionRoundGroupPosition1[2]]['groups']['match'][$divisionRoundGroupPosition1[3]];
                        $homeTeamData = $this->getWinnerOrLooserTeams($teamGroupType1, $divisionRoundGroupPosition1, $isSamePositionType, $homePositionType, $startRoundCount, $startMatchCount, 'home');

                        $teamRoundData2 = $this->getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition2, $divisionIndex);
                        $teamGroupType2 = $teamRoundData2['match_type'][$divisionRoundGroupPosition2[2]]['groups']['match'][$divisionRoundGroupPosition2[3]];
                        $awayTeamData = $this->getWinnerOrLooserTeams($teamGroupType2, $divisionRoundGroupPosition2, $isSamePositionType, $awayPositionType, $startRoundCount, $startMatchCount, 'away');

                        $teamType = $homePositionType === 'winner' ? 'wrs.' : 'lrs.';
                        $inBetween = $homeTeamData['teamInBetween'] . '-' . $awayTeamData['teamInBetween'];
                        $matchNumber = "CAT.PM" . ($startRoundCount + $roundIndex + 1) . ".G" . ($currentMatch + $startMatchCount) . "." . $homeTeamData['teamMatchNumber'] . "-" . $awayTeamData['teamMatchNumber'];
                        $displayMatchNumber = "CAT." . ($startRoundCount + $roundIndex + 1) . "." . ($currentMatch + $startMatchCount) . "." . $teamType . '(' . $homeTeamData['teamDisplayMatchNumber'] . '-' . $awayTeamData['teamDisplayMatchNumber'] . ')';
                        $displayHomeTeamPlaceholderName = $homeTeamData['teamDisplayPlaceholderName'];
                        $displayAwayTeamPlaceholderName = $awayTeamData['teamDisplayPlaceholderName'];
                    }

                    if($homePositionType == 'placed' && $awayPositionType == 'placed') {
                        $bothSameTeamTypes = true;
                        $teamRoundData1 = $this->getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition1, $divisionIndex);
                        $teamGroupType1 = $teamRoundData1['groups'][$divisionRoundGroupPosition1[2]]['type'];
                        $homeTeamData = $this->getRoundRobinTeamData($teamGroupType1, $teamRoundData1, $divisionRoundGroupPosition1, 'home');
                        
                        $teamRoundData2 = $this->getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition2, $divisionIndex);
                        $teamGroupType2 = $teamRoundData2['groups'][$divisionRoundGroupPosition2[2]]['type'];
                        $awayTeamData = $this->getRoundRobinTeamData($teamGroupType2, $teamRoundData2, $divisionRoundGroupPosition2, 'away');

                        $inBetween = $homeTeamData['teamInBetween'] . '-' . $awayTeamData['teamInBetween'];
                        $matchNumber = 'CAT.PM'. ($startRoundCount + $roundIndex + 1) . '.G' . ($currentMatch + $startMatchCount) . '.' . $homeTeamData['teamMatchNumber'] . '-' . $awayTeamData['teamMatchNumber'];
                        $displayMatchNumber = 'CAT.'. ($startRoundCount + $roundIndex + 1) . '.' . ($currentMatch + $startMatchCount) . '.' . $homeTeamData['teamDisplayMatchNumber'] . '-' . $awayTeamData['teamDisplayMatchNumber'];
                        $displayHomeTeamPlaceholderName = $homeTeamData['teamDisplayPlaceholderName'];
                        $displayAwayTeamPlaceholderName = $awayTeamData['teamDisplayPlaceholderName'];
                    }

                    if($bothSameTeamTypes == false) {
                        if($teams[$teamIndex]['position_type'] === 'placed') {
                            $teamRoundData1 = $this->getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition1, $divisionIndex);
                            $teamGroupType1 = $teamRoundData1['groups'][$divisionRoundGroupPosition1[2]]['type'];
                            $homeTeamData = $this->getRoundRobinTeamData($teamGroupType1, $teamRoundData1, $divisionRoundGroupPosition1, 'home');
                        }

                        if($teams[$teamIndex]['position_type'] == 'winner' || $teams[$teamIndex]['position_type'] == 'loser') {
                            $isSamePositionType = false;

                            $teamRoundData1 = $this->getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition1, $divisionIndex);
                            $teamGroupType1 = $teamRoundData1['match_type'][$divisionRoundGroupPosition1[2]]['groups']['match'][$divisionRoundGroupPosition1[3]];
                            $homeTeamData = $this->getWinnerOrLooserTeams($teamGroupType1, $divisionRoundGroupPosition1, $isSamePositionType, $homePositionType, $startRoundCount, $startMatchCount, 'home');
                        }

                        if($teams[$teamIndex + 1]['position_type'] == 'placed') {
                            $teamRoundData2 = $this->getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition2, $divisionIndex);
                            $teamGroupType2 = $teamRoundData2['groups'][$divisionRoundGroupPosition2[2]]['type'];
                            $awayTeamData = $this->getRoundRobinTeamData($teamGroupType2, $teamRoundData2, $divisionRoundGroupPosition2, 'away');
                        }

                        if($teams[$teamIndex + 1]['position_type'] == 'winner' || $teams[$teamIndex + 1]['position_type'] == 'loser') {
                            $isSamePositionType = false;
                            $teamRoundData2 = $this->getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition2, $divisionIndex);
                            $teamGroupType2 = $teamRoundData2['match_type'][$divisionRoundGroupPosition2[2]]['groups']['match'][$divisionRoundGroupPosition2[3]];
                            $awayTeamData = $this->getWinnerOrLooserTeams($teamGroupType2, $divisionRoundGroupPosition2, $isSamePositionType, $awayPositionType, $startRoundCount, $startMatchCount, 'away');
                        }

                        $inBetween = $homeTeamData['teamInBetween'] . '-' . $awayTeamData['teamInBetween'];
                        $matchNumber = "CAT.PM" . ($startRoundCount + $roundIndex + 1) . ".G" . ($currentMatch + $startMatchCount) . "." . $homeTeamData['teamMatchNumber'] . "-" . $awayTeamData['teamMatchNumber'];
                        $displayMatchNumber = "CAT." . ($startRoundCount + $roundIndex + 1) . "." . ($currentMatch + $startMatchCount) . "." . $homeTeamData['teamDisplayMatchNumber'] . '-' . $awayTeamData['teamDisplayMatchNumber'];
                        $displayHomeTeamPlaceholderName = $homeTeamData['teamDisplayPlaceholderName'];
                        $displayAwayTeamPlaceholderName = $awayTeamData['teamDisplayPlaceholderName'];
                    }

                    if( (($divisionIndex === -1 && ($roundIndex+1) === count($templateFormDetail['steptwo']['rounds'])) || ($divisionIndex >= 0 && ($roundIndex+1) === count($templateFormDetail['steptwo']['divisions'][$divisionIndex]['rounds'])))
                        && ($groupIndex+1) === count($round['groups'])
                        && $group['type'] == 'placing_match') {
                        $teamPosition = $divisionIndex. ',' .$roundIndex. ',' .$groupIndex. ',' .($currentMatch-1);
                        $position = $this->getMatchPosition($teamPosition, $templateFormDetail['stepthree']['placings']);
                    }
                    
                    $matchDetail = [
                        'in_between' => $inBetween,
                        'match_number' => $matchNumber,
                        'display_match_number' => $displayMatchNumber,
                        'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName,
                        'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName,
                    ];
                    if((int)$groupMatches[($teamIndex/2)]['is_final'] === 1) {
                        $matchDetail['is_final_match'] = 1;
                    }
                    if($position !== null) {
                        $matchDetail['position'] = $position;
                    }
                    array_push($matches, $matchDetail);

                    $teamIndex++;
                }
            }

            if($divisionIndex === -1 && $roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex && $group['type'] === "placing_match") {
                $groupName = $firstPlacingMatchGroupName;
            }

            $totalMatches += count($matches);
            $matchTypeDetail = [
                'name' => ( ($group['type'] === 'round_robin' && $isPlacingMatchAsRoundRobin === false) ? 'RR-1*' : 'PM-1*') . $group['no_of_teams'],
                'total_match' => count($matches),
                'group_count' => $group['no_of_teams'],
                'groups' => ['group_name' => 'Group-' . $groupName, 'match' => $matches]
            ];

            if($isPlacingMatchAsRoundRobin === true) {
                $matchTypeDetail['actual_name'] = 'RR-1*' . $group['no_of_teams'];
            }

            if($considerInTeamAssignment === true) {
                $matchTypeDetail['consider_in_team_assignment'] = 1;
                $matchTypeDetail['groups']['actual_group_name'] = 'Pos-A';
            }

            if($divisionIndex === -1) {
                $finalArray['tournament_competation_format']['format_name'][$roundIndex]['name'] = 'Round '.($roundIndex+1);
                $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][] = $matchTypeDetail;
            } else if($divisionIndex >= 0) {
                $finalArray['tournament_competation_format']['divisions'][$divisionIndex]['format_name'][$roundIndex]['name'] = 'Round '.($startRoundCount + $roundIndex + 1);
                $finalArray['tournament_competation_format']['divisions'][$divisionIndex]['format_name'][$roundIndex]['match_type'][] = $matchTypeDetail;
            }

            if($group['type'] === "round_robin") {
                $roundGroupCount++;
            }
            if($group['type'] === "placing_match" && !($divisionIndex === -1 && $roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex)) {
                $placingGroupCount++;
            }
        }
    }

    public function getRoundRobinTeamData($teamGroupType, $teamRoundData, $divisionRoundGroupPosition, $teamType)
    {
        $groupName = null;
        if($teamGroupType === 'round_robin') {
            $groupName = $this->getRoundRobinGroupName($teamRoundData, $divisionRoundGroupPosition[2]);
        }

        $team = (intval($divisionRoundGroupPosition[3]) + 1) . $groupName;
        $teamInBetween = $team;
        $teamMatchNumber = $team;
        $teamDisplayMatchNumber =  $teamType === 'home' ? '@HOME' : '@AWAY';
        $teamDisplayPlaceholderName = '#' . $team;

        return [
            'teamInBetween' => $teamInBetween,
            'teamMatchNumber' => $teamMatchNumber,
            'teamDisplayMatchNumber' => $teamDisplayMatchNumber,
            'teamDisplayPlaceholderName' => $teamDisplayPlaceholderName
        ];
    }

    public function getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition, $divisionIndex)
    {
        if($divisionRoundGroupPosition[0] == -1) {
            return $finalArray['tournament_competation_format']['format_name'][$divisionRoundGroupPosition[1]];
        }
        return $finalArray['tournament_competation_format']['divisions'][$divisionRoundGroupPosition[0]]['format_name'][$divisionRoundGroupPosition[1]];
    }

    public function getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition, $divisionIndex)
    {
        if($divisionRoundGroupPosition[0] == -1) {
            return $templateFormDetail['steptwo']['rounds'][$divisionRoundGroupPosition[1]];
        }
        return $templateFormDetail['steptwo']['divisions'][$divisionRoundGroupPosition[0]]['rounds'][$divisionRoundGroupPosition[1]];
    }

    public function getMatchPosition($teamPosition, $positionArray)
    {
        $winnerPosition = collect($positionArray)->where('position', $teamPosition)->where('position_type', 'winner')->keys()->toArray();
        $looserPosition = collect($positionArray)->where('position', $teamPosition)->where('position_type', 'loser')->keys()->toArray();

        $position = null;
        if(!empty($winnerPosition) && !empty($looserPosition)) {
            $position = (head($winnerPosition) + 1) . '-' .(head($looserPosition) + 1);
        }

        return $position;
    }

    public static function getTemplateGraphic($data)
    {
        $ageCategoryId = $data['ageCategoryId'];
        $templateId = $data['templateId'];

        $templateData = [];
        $jsonData = '';
        $tournamentName = '';
        $tempFixtures = [];
        $assignedTeams = [];
        $roundMatches = [];
        $divisionMatches = [];
        $allMatches = [];
        $tournamentCompetitionTemplate = null;

        if($ageCategoryId != null) {
            $tempFixtures = DB::table('temp_fixtures')->where('age_group_id', $ageCategoryId)
                ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
                ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
                ->select(['temp_fixtures.match_number', 'temp_fixtures.display_match_number', 'temp_fixtures.home_team', 'temp_fixtures.home_team_name', 'temp_fixtures.away_team', 'temp_fixtures.away_team_name', 'venues.name as venue_name', 'pitches.pitch_number as pitch_name', 'pitches.size as pitch_size', 'temp_fixtures.is_scheduled as is_scheduled', 'temp_fixtures.match_datetime as match_datetime','temp_fixtures.hometeam_score','temp_fixtures.awayteam_score'])
                ->where('temp_fixtures.deleted_at', NULL)
                ->get()->keyBy('match_number')->toArray();
            $tempFixtures = array_map(function($object){
                return (array) $object;
            }, $tempFixtures);
            $assignedTeams = Team::where('age_group_id', $ageCategoryId)->whereNotNull('competation_id')->get()->toArray();
            $tournamentCompetitionTemplate = TournamentCompetationTemplates::find($ageCategoryId);
        }
        if($templateId != NULL) {
            $tournamentTemplate = TournamentTemplates::find($templateId);
            $jsonData = $tournamentTemplate->json_data;
            $tournamentName = $tournamentTemplate->name;
        } else {
            $jsonData = $tournamentCompetitionTemplate->template_json_data;
            $tournamentName = ucfirst($tournamentCompetitionTemplate->competition_type) . ' - ' . $tournamentCompetitionTemplate->total_teams . ' teams';
        }
        $jsonData = json_decode($jsonData, true);
        $roundMatches = TemplateRepository::getMatches($jsonData['tournament_competation_format']['format_name']);
        if(isset($jsonData['tournament_competation_format']['divisions'])) {
            foreach($jsonData['tournament_competation_format']['divisions'] as $divisionIndex => $division) {
                $matches = TemplateRepository::getMatches($division['format_name']);
                $divisionMatches = array_merge($divisionMatches, $matches);
            }
        }
        $allMatches = array_merge($roundMatches, $divisionMatches);

        $templateData['graphicHtml'] = view('template.graphic', [
            'fixtures' => $tempFixtures,
            'templateData' => $jsonData,
            'assignedTeams' => $assignedTeams,
            'categoryAge' => $tournamentCompetitionTemplate ? $tournamentCompetitionTemplate->category_age : null,
            'groupName' => $tournamentCompetitionTemplate ? $tournamentCompetitionTemplate->group_name : null,
            'allMatches' => $allMatches,
        ])->render();
        $templateData['templateName'] = $tournamentName;

        return $templateData;
    }

    public function getTemplateGraphicOfLeague($request)
    {
        $data['total_teams'] = $request['number_of_teams'];
        $jsonData = $this->ageGroupService->generateTemplateJsonForLeague($data);
        $jsonData = json_decode($jsonData, true);
        $tournamentName = 'League - ' . $data['total_teams'] . ' teams';
        $allMatches = TemplateRepository::getMatches($jsonData['tournament_competation_format']['format_name']);

        $templateData['graphicHtml'] = view('template.graphic', [
            'fixtures' => [],
            'templateData' => $jsonData,
            'assignedTeams' => [],
            'categoryAge' => null,
            'groupName' => null,
            'allMatches' => $allMatches,
        ])->render();

        return $templateData;
    }

    public static function getMatches($rounds)
    {
        $allMatches = [];
        foreach($rounds as $roundIndex => $round) {
            foreach($round['match_type'] as $groupIndex => $group) {
                foreach($group['groups']['match'] as $matchIndex => $match) {
                    $allMatches[] = $match;
                }
            }
        }
        return $allMatches;
    }
}