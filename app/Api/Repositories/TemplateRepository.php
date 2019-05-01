<?php

namespace Laraspace\Api\Repositories;

use DB;
use Auth;
use Laraspace\Models\User;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Api\Services\AgeGroupService;


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
        $templates->orderBy('tournament_template.created_at');
        $templates->select('tournament_template.*', 'users.email as userEmail');
        $templates = $templates->get();
        
        return $templates;
    }

    /*
     * Get template details
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTemplateDetail($data)
    {
        $tournamentTemplates = TournamentCompetationTemplates::leftjoin('tournaments', 'tournament_competation_template.tournament_id', '=', 'tournaments.id')
                                                            ->where('tournament_template_id', $data['templateData']['id'])
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
        if($tournamentCompetationTemplateCount > 0) {
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
        $finalArray['remark'] = $templateFormDetail['stepfour']['remarks'];
        $finalArray['template_font_color'] = $templateFormDetail['stepfour']['template_font_color'];
        $finalArray['tournament_name'] = $templateFormDetail['stepone']['templateName'];
        $finalArray['competition_round'] = '';
        $finalArray['competition_group_round'] = '';
        $finalArray['competation_format'] = '';
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
        
        $placings = $templateFormDetail['stepthree']['placings'];
        foreach($placings as $placingIndex => $placing) {
            $roundGroupPositionArray = explode(',', $placing['position']);
            if($roundGroupPositionArray[0] === -1) {
                $roundDetail = $finalArray['tournament_competation_format']['format_name'][$roundGroupPositionArray[1]];
            } else {
                $roundDetail = $finalArray['tournament_competation_format']['divisions'][$roundGroupPositionArray[0]]['format_name'][$roundGroupPositionArray[1]];
            }
            
            $groupDetail = $roundDetail['match_type'][$roundGroupPositionArray[2]];
            $matchNumber = $groupDetail['groups']['match'][$roundGroupPositionArray[3]]['match_number'];

            $tournamentsPositionsData[$placingIndex]['position'] = ($placingIndex + 1);
            if($placing['position_type'] == 'winner' || $placing['position_type'] == 'loser') {
                $tournamentsPositionsData[$placingIndex]['dependent_type'] = 'match';
                $tournamentsPositionsData[$placingIndex]['match_number'] = $matchNumber;
                $tournamentsPositionsData[$placingIndex]['result_type'] = $placing['position_type'];
            }
            if($placing['position_type'] == 'placed') {
                $tournamentsPositionsData[$placingIndex]['dependent_type'] = 'ranking';
                $roundDataTeam = $templateFormDetail['steptwo']['rounds'][$roundGroupPositionArray[1]];
                $groupName = $this->getRoundRobinGroupName($roundDataTeam, intval($roundGroupPositionArray[2]));
                $team = (intval($roundGroupPositionArray[3]) + 1) . $groupName;
                $tournamentsPositionsData[$placingIndex]['ranking'] = $team;
            }
        }

        $averageMatches = $this->getAverageMatches($totalMatches, $totalTeams);
        $minimumMatches = $this->getMinimumMatches($templateFormDetail);
        $positionType = $this->getPositionType($tournamentsPositionsData);

        $finalArray['total_matches'] = $totalMatches;
        $finalArray['tournament_min_match'] = $minimumMatches;
        $finalArray['avg_game_team'] = $averageMatches;
        $finalArray['position_type'] = $positionType;
        $finalArray['tournament_positions'] = $tournamentsPositionsData;

        dd($finalArray);

        return json_encode($finalArray);
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

        $tournamentTemplate = new TournamentTemplates();
        $tournamentTemplate->json_data = $templateJson;
        $tournamentTemplate->name = $data['templateFormDetail']['stepone']['templateName'];
        $tournamentTemplate->total_teams = $data['templateFormDetail']['stepone']['no_of_teams'];
        $tournamentTemplate->minimum_matches = $decodedJson['tournament_min_match'];
        $tournamentTemplate->position_type = $decodedJson['position_type'];
        $tournamentTemplate->avg_matches = $decodedJson['avg_game_team'];
        $tournamentTemplate->total_matches = $decodedJson['total_matches'];
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
        $tournamentTemplate = TournamentTemplates::findOrFail($data['editedTemplateId']);
        $tournamentTemplate->json_data = $templateJson;
        $tournamentTemplate->name = $data['templateFormDetail']['stepone']['templateName'];
        $tournamentTemplate->total_teams = $data['templateFormDetail']['stepone']['no_of_teams'];
        $tournamentTemplate->editor_type = $data['templateFormDetail']['stepone']['editor'];
        $tournamentTemplate->template_form_detail = json_encode($data['templateFormDetail']);
        $tournamentTemplate->created_by = Auth::user()->id;
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
    public function getPositionTypeCode()
    {

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

    public function getWinnerOrLooserTeams($teamGroupType, $divisionRoundGroupPosition, $isSamePositionType, $positionType, $teamType)
    {
        $teamGroupTypeMatchNumber = explode(".", $teamGroupType['match_number']);
        $teams = explode("-", end($teamGroupTypeMatchNumber));

        $teamPlaceholderIndex = intval($divisionRoundGroupPosition[3]) + 1;
        if (strpos($teams[0], 'WR') || strpos($teams[0], 'LR')){
            $groupName = str_replace("-", "_", end($teamGroupTypeMatchNumber));
            $teamMatchNumber = '(PM' . (intval($divisionRoundGroupPosition[1]) + 1) . '_G' . $teamPlaceholderIndex . ($positionType == 'winner' ? '_WR' : '_LR') . ')';
        } else {
            $groupName = str_replace("-", "_", end($teamGroupTypeMatchNumber));
            $teamMatchNumber = $groupName . ($positionType == 'winner' ? '_WR' : '_LR');
        }

        $teamInBetween = $this->getTeamInBetween($divisionRoundGroupPosition, $teamPlaceholderIndex, $positionType, $groupName);
        $teamDisplayMatchNumber =  $this->getTeamDisplayMatchNumber($teamType, $isSamePositionType, $positionType);
        $teamDisplayPlaceholderName = ($divisionRoundGroupPosition[1] + 1) . '.' . $teamPlaceholderIndex;

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
            $displayMatchNumber = ($positionType == 'winner' ? 'wrs.' : 'lrs.') . '.(@HOME)';
        }
        if($teamType === 'away' && $isSamePositionType === false && ($positionType === 'winner' || $positionType === 'loser')) {
            $displayMatchNumber = ($positionType == 'winner' ? 'wrs.' : 'lrs.') . '.(@AWAY)';
        }

        return $displayMatchNumber;
    }

    public function getTeamInBetween($divisionRoundGroupPosition, $teamPlaceholderIndex, $positionType, $groupName)
    {
        if($positionType === 'winner' || $positionType === 'loser') {
            $teamInBetween = 'CAT.PM' .($divisionRoundGroupPosition[1] + 1). '.G' . $teamPlaceholderIndex. ($positionType == 'winner' ? 'WR' : 'LR');    
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
       return $totalMatches / ($numTeams / 2);
    }

    public function getMinimumMatches($templateFormDetail)
    {
        $minGames = 0;
        $rounds = $templateFormDetail['steptwo']['rounds'];
        $totalTeams = $templateFormDetail['stepone']['no_of_teams'];

        foreach ($rounds as $roundIndex => $round) {
            $nGames = [];
            if($round['no_of_teams'] < $totalTeams) {
                break;
            } else {
                foreach ($round['groups'] as $groupIndex => $group) {
                    if($group['type'] == 'round_robin') {
                        $nGames[] = $this->getNumberOfTimesFromString($group['teams_play_each_other']) * ($group['no_of_teams'] - 1);
                    }
                    if($group['type'] == 'placing_match') {
                        $nGames[] = 1;
                    }
                }
            }
            $minGames += min($nGames);
        }
        $minimumMatches = $minGames;

        return $minimumMatches;
    }

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

        $firstPlacingMatchIndex = array_search('placing_match', array_column($round['groups'], 'type'));
        foreach ($round['groups'] as $groupIndex => $group) {
            $matchCount = 1;
            $matches = [];

            $groupData = $group;
            $times = $groupData['teams_play_each_other'];
            $noOfTeams = $groupData['no_of_teams'];
            $groupName = null;

            if($group['type'] === "round_robin") {
                $groupName = chr(65 + $roundGroupCount);
            }

            if($group['type'] === "placing_match") {
                $groupName = $placingGroupCount + 1;
            }

            if($roundIndex === 0 && $group['type'] === 'round_robin') {
                // $matches = [];
                $times = $this->getNumberOfTimesFromString($times);
                $fetchRoundMatches = $this->ageGroupService->generateRoundFixturesBaseOnTeam($noOfTeams);

                $matches = $this->ageGroupService->leagueKnockoutJsonMatches($fetchRoundMatches,$roundIndex,$groupName,$times);
                $group['matches'] = $matches;
            }

            if($divisionIndex === -1 && $roundIndex === 0 && $groupIndex === $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
                for($i=1; $i<=$noOfTeams; $i=$i+2) {
                    $home = $i;
                    $away = $i + 1;
                    $inBetween = $home . "-" . $away;
                    $matchNumber = "CAT.PM" . ($roundIndex+1) . ".G" . $matchCount . "." . $home . "-" . $away;
                    $displayMatchNumber = "CAT." . ($roundIndex+1) . "." . $matchCount . ".@HOME-@AWAY";
                    $displayHomeTeamPlaceholderName = $home;
                    $displayAwayTeamPlaceholderName = $away;

                    array_push($matches, ['in_between' => $inBetween, 'match_number' => $matchNumber, 'display_match_number' => $displayMatchNumber, 'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName, 'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName]);

                    $matchCount++;
                }
            }

            if($divisionIndex === -1 && $roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
                $teams = $group['teams'];
                
                $totalPlacingMatches = 0;
                $allPlacingMatches = [];
                $prevPlacingMatchesCount = 0;

                foreach($round['groups'] as $index => $o) {
                    if($o['type'] === 'placing_match' && $index < $groupIndex) {
                        $totalPlacingMatches += count($o['matches']);
                        $allPlacingMatches = array_merge($allPlacingMatches, $o['matches']);
                    }
                    if(($index+1) < $groupIndex) {
                        $prevPlacingMatchesCount += count($o['matches']);
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

                        $homePlacingMatch = reset(array_filter($allPlacingMatches, function($o, $index) { return strpos($o['match_number'], $homePlaceholder) !== false; }));
                        $awayPlacingMatch = reset(array_filter($allPlacingMatches, function($o, $index) { return strpos($o['match_number'], $awayPlaceholder) !== false; }));

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

                            $displayHomeTeamPlaceholderName = ($roundIndex + 1) . "." . ($prevPlacingMatchesCount1 + parseInt($position1) + 1);
                            $displayAwayTeamPlaceholderName = ($roundIndex + 1) . "." . ($prevPlacingMatchesCount2 + parseInt($position2) + 1);

                            array_push($matches, [
                                'in_between' => $inBetween,
                                'match_number' => $matchNumber,
                                'display_match_number' => $displayMatchNumber,
                                'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName,
                                'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName,
                            ]);



                            $matchCount++;
                        }
                    }
                }
            }

            if(($divisionIndex === -1 && $roundIndex > 0 && $group['type'] === "round_robin")) {
                $times = $this->getNumberOfTimesFromString($times);
                for($i=0; $i<$times; $i++){
                    for($j=1; $j<=$noOfTeams; $j++) {
                        for($k=($j+1); $k<=$noOfTeams; $k++) {
                            $inBetween = null;
                            $matchNumber = null;
                            $displayMatchNumber = null;
                            $displayHomeTeamPlaceholderName = null;
                            $displayAwayTeamPlaceholderName = null;
                            // if($divisionIndex === -1) {
                                $team1 = $groupData['teams'][$j-1];
                                $team2 = $groupData['teams'][$k-1];
                                // if($roundIndex === 0 && $groupData['type'] === "round_robin") {
                                //     $home = $groupName . $j;
                                //     $away = $groupName . $k;
                                //     $inBetween = $j . '-' . $k;
                                //     $matchNumber = "CAT.RR" . ($roundIndex+1) . ".0" . $matchCount . "." . $home . "-" . $away;
                                //     $displayMatchNumber = "CAT." . ($roundIndex+1) . "." . $matchCount . ".@HOME-@AWAY";
                                //     $displayHomeTeamPlaceholderName = $home;
                                //     $displayAwayTeamPlaceholderName = $away;
                                // } else {
                                    $divisionRoundGroupPositionTeam1 = explode(',', $team1['position']);
                                    $divisionRoundGroupPositionTeam2 = explode(',', $team2['position']);
                                    $roundDataTeam1 = null;
                                    $roundDataTeam2 = null;

                                    if($team1['position']) {
                                        if($divisionRoundGroupPositionTeam1[0] === '-1') {
                                            $roundDataTeam1 = $templateFormDetail['steptwo']['rounds'][$divisionRoundGroupPositionTeam1[1]];
                                        } else {
                                            $roundDataTeam1 = $templateFormDetail['steptwo']['divisions'][$divisionRoundGroupPositionTeam1[0]]['rounds'][$divisionRoundGroupPositionTeam1[1]];
                                        }
                                    }
                                    
                                    if($team2['position']) {
                                        if($divisionRoundGroupPositionTeam2[0] === '-1') {
                                            $roundDataTeam2 = $templateFormDetail['steptwo']['rounds'][$divisionRoundGroupPositionTeam2[1]];
                                        } else {
                                            $roundDataTeam2 = $templateFormDetail['steptwo']['divisions'][$divisionRoundGroupPositionTeam2[0]]['rounds'][$divisionRoundGroupPositionTeam2[1]];
                                        }
                                    }

                                    if($roundDataTeam1 && $roundDataTeam2) {
                                        $groupName1 = $this->getRoundRobinGroupName($roundDataTeam1, intval($divisionRoundGroupPositionTeam1[2]));
                                        $groupName2 = $this->getRoundRobinGroupName($roundDataTeam2, intval($divisionRoundGroupPositionTeam2[2]));
                                        $home = (intval($divisionRoundGroupPositionTeam1[3]) + 1) . $groupName1;
                                        $away = (intval($divisionRoundGroupPositionTeam2[3]) + 1) . $groupName2;
                                        $inBetween = $home . '-' . $away;
                                        $matchNumber = "CAT.RR" . ($roundIndex+1) . ".0" . $matchCount . "." . $home . "-" . $away;
                                        $displayMatchNumber = "CAT." . ($roundIndex+1) . "." . $matchCount . ".@HOME-@AWAY";
                                        $displayHomeTeamPlaceholderName = '#' . $home;
                                        $displayAwayTeamPlaceholderName = '#' . $away;
                                    }
                                //}
                            // }
                            // matchCount++;
                            array_push($matches, ['in_between' => $inBetween, 'match_number' => $matchNumber, 'display_match_number' => $displayMatchNumber, 'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName, 'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName]);

                            $matchCount++;

                        }
                    }
                }
            }

            if(($divisionIndex === -1 && $roundIndex > 0 && $group['type'] === "placing_match") || ($divisionIndex > -1 && $roundIndex >= 0 && $group['type'] === "placing_match")) {
                // dd($divisionDetail);
                $teams = $group['teams'];
                if($divisionIndex >= -1 && $roundIndex === 0) {
                    $teams = [];
                    for ($teamIndex = 0; $teamIndex < count($group['teams']); $teamIndex++) {
                        $teams[$teamIndex] = $divisionDetail['divisionTeams'][$group['teams'][$teamIndex]['position']];
                    }
                }
                // dd($teams);
                for ($teamIndex = 0; $teamIndex < count($teams); $teamIndex++) {
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

                        $teamRoundData1 = $finalArray['tournament_competation_format']['format_name'][$divisionRoundGroupPosition1[1]];
                        $teamGroupType1 = $teamRoundData1['match_type'][$groupIndex]['groups']['match'][$divisionRoundGroupPosition1[3]];
                        $homeTeamData = $this->getWinnerOrLooserTeams($teamGroupType1, $divisionRoundGroupPosition1, $isSamePositionType, $homePositionType, 'home');

                        $teamRoundData2 = $finalArray['tournament_competation_format']['format_name'][$divisionRoundGroupPosition2[1]];
                        $teamGroupType2 = $teamRoundData2['match_type'][$groupIndex]['groups']['match'][$divisionRoundGroupPosition2[3]];
                        $awayTeamData = $this->getWinnerOrLooserTeams($teamGroupType2, $divisionRoundGroupPosition2, $isSamePositionType, $awayPositionType, 'away');

                        $teamType = $homePositionType === 'winner' ? 'wrs.' : 'lrs.';
                        $inBetween = $homeTeamData['teamInBetween'] . '-' . $awayTeamData['teamInBetween'];
                        $matchNumber = "CAT.PM" . ($roundIndex+1) . ".G" . $currentMatch . "." . $homeTeamData['teamMatchNumber'] . "-" . $awayTeamData['teamMatchNumber'];
                        $displayMatchNumber = "CAT." . ($roundIndex+1) . "." . $currentMatch . "." . $teamType . '(' . $homeTeamData['teamDisplayMatchNumber'] . '-' . $awayTeamData['teamDisplayMatchNumber'] . ')';
                        $displayHomeTeamPlaceholderName = $homeTeamData['teamDisplayPlaceholderName'];
                        $displayAwayTeamPlaceholderName = $awayTeamData['teamDisplayPlaceholderName'];
                    }

                    if($homePositionType == 'placed' && $awayPositionType == 'placed') {
                        $bothSameTeamTypes = true;
                        $teamRoundData1 = $templateFormDetail['steptwo']['rounds'][$divisionRoundGroupPosition1[1]];
                        $teamGroupType1 = $teamRoundData1['groups'][$divisionRoundGroupPosition1[2]]['type'];
                        $groupName1 = null;
                        if($teamGroupType1 === 'round_robin') {
                            $groupName1 = $this->getRoundRobinGroupName($teamRoundData1, $divisionRoundGroupPosition1[2]);
                        }
                        
                        $teamRoundData2 = $templateFormDetail['steptwo']['rounds'][$divisionRoundGroupPosition2[1]];
                        $teamGroupType2 = $teamRoundData2['groups'][$divisionRoundGroupPosition2[2]]['type'];
                        $groupName2 = null;
                        if($teamGroupType2 === 'round_robin') {
                            $groupName2 = $this->getRoundRobinGroupName($teamRoundData2, $divisionRoundGroupPosition2[2]);
                        }

                        $homeTeam = (intval($divisionRoundGroupPosition1[3]) + 1) . $groupName1;
                        $awayTeam = (intval($divisionRoundGroupPosition2[3]) + 1) . $groupName2;
                        $inBetween = $homeTeam . '-' . $awayTeam;
                        $matchNumber = 'CAT.PM'. ($this->getPlacingMatchGroupCount($round, $groupIndex) + 1) . '.G' . ($teamIndex / 2 + 1) . '.' . $inBetween;
                        $displayMatchNumber = 'CAT.'. ($this->getPlacingMatchGroupCount($round, $groupIndex) + 1) . '.' . ($teamIndex / 2 + 1) . '.' . $inBetween;
                        $displayHomeTeamPlaceholderName = '#' . $homeTeam;
                        $displayAwayTeamPlaceholderName = '#' . $awayTeam;
                    }

                    if($bothSameTeamTypes == false) {
                        if($teams[$teamIndex]['position_type'] === 'placed') {
                            $teamRoundData1 = $templateFormDetail['steptwo']['rounds'][$divisionRoundGroupPosition1[1]];
                            $teamGroupType1 = $teamRoundData1['groups'][$divisionRoundGroupPosition1[2]]['type'];
                            $groupName1 = null;
                            if($teamGroupType1 === 'round_robin') {
                                $groupName1 = $this->getRoundRobinGroupName($teamRoundData1, $divisionRoundGroupPosition1[2]);
                            }                               

                            $homeTeam = (intval($divisionRoundGroupPosition1[3]) + 1) . $groupName1;
                            $homeInBetween = $homeTeam;
                            $homeMatchNumber = 'CAT.PM'. ($this->getPlacingMatchGroupCount($round, $groupIndex) + 1) . '.G' . ($teamIndex / 2 + 1) . '.' . $homeInBetween;
                            $homeDisplayMatchNumber = 'CAT.'. ($this->getPlacingMatchGroupCount($round, $groupIndex) + 1) . '.' . ($teamIndex / 2 + 1) . '.' . $homeInBetween;
                            $homeDisplayHomeTeamPlaceholderName = '#' . $homeTeam;
                        }

                        if($teams[$teamIndex]['position_type'] == 'winner' || $teams[$teamIndex]['position_type'] == 'loser') {
                            $teamRoundData1 = $finalArray['tournament_competation_format']['format_name'][$divisionRoundGroupPosition1[1]];
                            $teamGroupType1 = $teamRoundData1['match_type'][$groupIndex]['groups']['match'][$divisionRoundGroupPosition1[3]];
                            $teamGroupType1MatchNumber = explode(".", $teamGroupType1['match_number']);
                                                        
                            $groupName1 = str_replace("-", "_", end($teamGroupType1MatchNumber));
                            $homeTeamPlaceholderIndex = $divisionRoundGroupPosition1[3] + 1;

                            if($teams[$teamIndex]['position_type'] == 'winner') {
                                $homeInBetween = 'CAT.PM' .($divisionRoundGroupPosition1[1] + 1). '.G' .$homeTeamPlaceholderIndex. 'WR';
                                $homeMatchNumber = 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.' .$groupName1 .'_WR';
                                $homeDisplayMatchNumber = 'CAT.' .$currentRound. '.' .$currentMatch. '.wrs.(@HOME-@AWAY)';
                            }
                            if($teams[$teamIndex]['position_type'] == 'loser') {
                                $homeInBetween = 'CAT.PM' .($divisionRoundGroupPosition1[1] + 1). '.G' .$homeTeamPlaceholderIndex. 'LR';
                                $homeMatchNumber = 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.' .$groupName1 .'_LR';
                                $homeDisplayMatchNumber = 'CAT.' .$currentRound. '.' .$currentMatch. '.lrs.(@HOME-@AWAY)';
                            }

                            $displayHomeTeamPlaceholderName = ($divisionRoundGroupPosition1[1] + 1). '.' .$homeTeamPlaceholderIndex;
                        }

                        if($teams[$teamIndex + 1]['position_type'] == 'placed') {
                            $teamRoundData2 = $templateFormDetail['steptwo']['rounds'][$divisionRoundGroupPosition2[1]];
                            $teamGroupType2 = $teamRoundData2['groups'][$divisionRoundGroupPosition2[2]]['type'];
                            $groupName2 = null;
                            if($teamGroupType2 === 'round_robin') {
                                $groupName2 = $this->getRoundRobinGroupName($teamRoundData2, $divisionRoundGroupPosition2[2]);
                            }

                            $awayTeam = (intval($divisionRoundGroupPosition2[3]) + 1) . $groupName2;
                            $awayInBetween = $awayTeam;
                            $awayMatchNumber = 'CAT.PM'. ($this->getPlacingMatchGroupCount($round, $groupIndex) + 1) . '.G' . ($teamIndex / 2 + 1) . '.' . $awayInBetween;
                            $awayDisplayMatchNumber = 'CAT.'. ($this->getPlacingMatchGroupCount($round, $groupIndex) + 1) . '.' . ($teamIndex / 2 + 1) . '.' . $awayInBetween;
                            $awayDisplayAwayTeamPlaceholderName = '#' . $awayTeam;
                        }

                        if($teams[$teamIndex + 1]['position_type'] == 'winner' || $teams[$teamIndex + 1]['position_type'] == 'loser') {
                            $teamRoundData2 = $finalArray['tournament_competation_format']['format_name'][$divisionRoundGroupPosition2[1]];
                            $teamGroupType2 = $teamRoundData2['match_type'][$groupIndex]['groups']['match'][$divisionRoundGroupPosition2[3]];
                            $teamGroupType2MatchNumber = explode(".", $teamGroupType2['match_number']);
                            $groupName2 = str_replace("-", "_", end($teamGroupType2MatchNumber));
                            $awayTeamPlaceholderIndex = $divisionRoundGroupPosition2[3] + 1;

                            if($teams[$teamIndex]['position_type'] == 'winner') {
                                $awayInBetween = 'CAT.PM' .($divisionRoundGroupPosition1[1] + 1). '.G' .$awayTeamPlaceholderIndex. 'WR';
                                $homeMatchNumber = 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.' .$groupName1 .'_WR';
                                $homeDisplayMatchNumber = 'CAT.' .$currentRound. '.' .$currentMatch. '.wrs.(@HOME-@AWAY)';
                            }
                            if($teams[$teamIndex]['position_type'] == 'loser') {
                                $awayInBetween = 'CAT.PM' .($divisionRoundGroupPosition1[1] + 1). '.G' .$awayTeamPlaceholderIndex. 'LR';
                                $homeMatchNumber = 'CAT.PM' .$currentRound. '.G' .$currentMatch. '.' .$groupName1 .'_LR';
                                $homeDisplayMatchNumber = 'CAT.' .$currentRound. '.' .$currentMatch. '.lrs.(@HOME-@AWAY)';
                            }

                            $displayAwayTeamPlaceholderName = ($divisionRoundGroupPosition2[1] + 1). '.' .$awayTeamPlaceholderIndex;
                        }
                    }
                    
                    array_push($matches, [
                        'in_between' => $inBetween,
                        'match_number' => $matchNumber,
                        'display_match_number' => $displayMatchNumber,
                        'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName,
                        'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName,
                    ]);

                    // array_push($matches, [
                    //     'in_between' => $homeInBetween. '-' .$awayInBetween,
                    //     'match_number' => $homeMatchNumber. '-' .$awayMatchNumber,
                    //     'display_match_number' => $displayMatchNumber,
                    //     'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName,
                    //     'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName,
                    // ]);

                    $teamIndex++;
                }
            }

            $totalMatches += count($matches);
            $matchTypeDetail = [
                'name' => ($group['type'] === 'round_robin' ? 'RR-1*' : 'PM-1*') . $group['no_of_teams'],
                'total_match' => count($matches),
                'group_count' => $group['no_of_teams'],
                'groups' => ['group_name' => 'Group-' . $groupName, 'match' => $matches]
            ];

            if($divisionIndex === -1) {
                $finalArray['tournament_competation_format']['format_name'][$roundIndex]['name'] = 'Round '.($roundIndex+1);
                $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][] = $matchTypeDetail;
            } else if($divisionIndex >= 0) {
                $finalArray['tournament_competation_format']['divisions'][$divisionIndex]['format_name'][$roundIndex]['name'] = 'Round '.($roundIndex+1);
                $finalArray['tournament_competation_format']['divisions'][$divisionIndex]['format_name'][$roundIndex]['match_type'][] = $matchTypeDetail;
            }

            if($group['type'] === "round_robin") {
                $roundGroupCount++;
            }
            if($group['type'] === "placing_match") {
                $placingGroupCount++;
            }
        }
    }
}