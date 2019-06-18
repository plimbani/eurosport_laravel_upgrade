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
        $this->getGraphicImagePath = '/assets/img/template_graphic_image/';
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
        $tournamentTemplate->graphic_image = $tournamentTemplate->graphic_image ? getenv('S3_URL').$tournamentTemplate->graphic_image : null;

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
            if((int)$roundGroupPositionArray[0] === -1) {
                $roundDetail = $finalArray['tournament_competation_format']['format_name'][$roundGroupPositionArray[1]];
            } else {
                $roundDetail = $finalArray['tournament_competation_format']['divisions'][$roundGroupPositionArray[0]]['format_name'][$roundGroupPositionArray[1]];
            }
            
            $groupDetail = $roundDetail['match_type'][$roundGroupPositionArray[2]];
            $matchNumber = $groupDetail['groups']['match'][$roundGroupPositionArray[3]]['match_number'];

            $tournamentsPositionsData[$placingIndex]['position'] = ($placingIndex + 1);
            if($placing['position_type'] == 'winner' || $placing['position_type'] == 'looser') {
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
        $finalArray['round_schedule'] = $data['templateFormDetail']['stepfour']['roundSchedules'];

        foreach($finalArray['tournament_competation_format']['format_name'] as $roundIndex => $round) {
            $templateFormDetailGroup = $templateFormDetail['steptwo']['rounds'][$roundIndex]['groups'];
            $firstPlacingMatchIndex = array_search('placing_match', array_column($templateFormDetailGroup, 'type'));
            foreach ($round['match_type'] as $groupIndex => $group) {
                if($roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex && $templateFormDetailGroup[$groupIndex]['type'] === 'placing_match') {
                    unset($finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][$groupIndex]);
                    $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][0]['dependent_groups'][] = $group;
                }
            }
        }

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

        $graphicImageName = NULL;
        if($data['templateFormDetail']['stepfour']['graphic_image']) {
            $graphicImageName = $this->getGraphicImagePath .$data['templateFormDetail']['stepfour']['graphic_image'];
        }

        $tournamentTemplate = new TournamentTemplates();
        $tournamentTemplate->json_data = $templateJson;
        $tournamentTemplate->name = $data['templateFormDetail']['stepone']['templateName'];
        $tournamentTemplate->graphic_image = $graphicImageName;
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
        $decodedJson = json_decode($templateJson, true);
        $graphicImageName = NULL;
        if($data['templateFormDetail']['stepfour']['graphic_image']) {
            $graphicImageName = $this->getGraphicImagePath .$data['templateFormDetail']['stepfour']['graphic_image'];
        }

        $tournamentTemplate = TournamentTemplates::findOrFail($data['editedTemplateId']);
        $tournamentTemplate->json_data = $templateJson;
        $tournamentTemplate->name = $data['templateFormDetail']['stepone']['templateName'];
        $tournamentTemplate->graphic_image = $graphicImageName;
        $tournamentTemplate->total_teams = $data['templateFormDetail']['stepone']['no_of_teams'];
        $tournamentTemplate->minimum_matches = $decodedJson['tournament_min_match'];
        $tournamentTemplate->position_type = $decodedJson['position_type'];
        $tournamentTemplate->avg_matches = $decodedJson['avg_game_team'];
        $tournamentTemplate->total_matches = $decodedJson['total_matches'];
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

    public function getWinnerOrLooserTeams($teamGroupType, $divisionRoundGroupPosition, $isSamePositionType, $positionType, $startRoundCount, $startMatchCount, $teamType)
    {
        $teamGroupTypeMatchNumber = explode(".", $teamGroupType['match_number']);
        $teams = explode("-", end($teamGroupTypeMatchNumber));

        $teamPlaceholderIndex = intval($divisionRoundGroupPosition[3]) + 1;
        if (strpos($teams[0], 'WR') || strpos($teams[0], 'LR')){
            $groupName = str_replace("-", "_", end($teamGroupTypeMatchNumber));
            $teamMatchNumber = '(PM' . ($startRoundCount + intval($divisionRoundGroupPosition[1]) + 1) . '_G' . ($startMatchCount + $teamPlaceholderIndex) . ($positionType == 'winner' ? '_WR' : '_LR') . ')';
        } else {
            $groupName = str_replace("-", "_", end($teamGroupTypeMatchNumber));
            $teamMatchNumber = $groupName . ($positionType == 'winner' ? '_WR' : '_LR');
        }

        $teamInBetween = $this->getTeamInBetween($divisionRoundGroupPosition, $teamPlaceholderIndex, $positionType, $groupName, $startRoundCount, $startMatchCount);
        $teamDisplayMatchNumber =  $this->getTeamDisplayMatchNumber($teamType, $isSamePositionType, $positionType);
        $teamDisplayPlaceholderName = ($startRoundCount + intval($divisionRoundGroupPosition[1]) + 1) . '.' . ($startMatchCount + $teamPlaceholderIndex);

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
        if($teamType === 'home' && $isSamePositionType === false && ($positionType === 'winner' || $positionType === 'looser')) {
            $displayMatchNumber = ($positionType == 'winner' ? 'wrs.' : 'lrs.') . '(@HOME)';
        }
        if($teamType === 'away' && $isSamePositionType === false && ($positionType === 'winner' || $positionType === 'looser')) {
            $displayMatchNumber = ($positionType == 'winner' ? 'wrs.' : 'lrs.') . '(@AWAY)';
        }

        return $displayMatchNumber;
    }

    public function getTeamInBetween($divisionRoundGroupPosition, $teamPlaceholderIndex, $positionType, $groupName, $startRoundCount, $startMatchCount)
    {
        if($positionType === 'winner' || $positionType === 'looser') {
            $teamInBetween = 'CAT.PM' .($startRoundCount + $divisionRoundGroupPosition[1] + 1). '.G' . ($startMatchCount + $teamPlaceholderIndex). ($positionType == 'winner' ? 'WR' : 'LR');
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
       return round($averageMatches, 1);
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
        $startRoundCount = $divisionIndex >= 0 ? $divisionDetail['divisionStartRoundCount'] : 0;

        $firstPlacingMatchIndex = array_search('placing_match', array_column($round['groups'], 'type'));
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

            if($roundIndex === 0 && $group['type'] === 'round_robin') {
                // $matches = [];
                $times = $this->getNumberOfTimesFromString($times);
                $fetchRoundMatches = $this->ageGroupService->generateRoundFixturesBaseOnTeam($noOfTeams);

                $matches = $this->ageGroupService->leagueKnockoutJsonMatches($fetchRoundMatches, $roundIndex, $groupName, $times, $startRoundCount);
                $group['matches'] = $matches;
            }

            if($divisionIndex === -1 && $roundIndex === 0 && $groupIndex === $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
                $considerInTeamAssignment = true;
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
                $groupMatches = $group['matches'];
                
                $totalPlacingMatches = 0;
                $allPlacingMatches = [];
                $prevPlacingMatchesCount = 0;

                foreach($round['groups'] as $index => $o) {
                    if($o['type'] === 'placing_match' && $index < $groupIndex) {
                        $matches  = $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][$index]['groups']['match'];
                        // if($index === 0) {
                        //     $matches  = $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][$index]['groups']['match'];
                        // } else {
                        //     $matches  = $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][0]['dependent_groups'][$index-1]['groups']['match'];
                        // }
                        $totalPlacingMatches += count($matches);
                        $allPlacingMatches = array_merge($allPlacingMatches, $matches);
                    }
                    if(($index+1) < $groupIndex) {
                        $prevPlacingMatchesCount += count($matches);
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

            if(($roundIndex > 0 && $group['type'] === "round_robin")) {
                $times = $this->getNumberOfTimesFromString($times);
                $fetchRoundMatches = $this->ageGroupService->generateRoundFixturesBaseOnTeam($noOfTeams);

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
                            $team1 = $groupData['teams'][intval($home) - 1];
                            $team2 = $groupData['teams'][intval($away) - 1];
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
                            if($team1['position_type'] === 'winner' || $team1['position_type'] === 'looser') {
                                $teamRoundData1 = $this->getTeamRoundDataForPlacing($finalArray, $divisionRoundGroupPosition1, $divisionIndex);
                                $teamGroupType1 = $teamRoundData1['match_type'][$divisionRoundGroupPosition1[2]]['groups']['match'][$divisionRoundGroupPosition1[3]];
                                $homeTeamData = $this->getWinnerOrLooserTeams($teamGroupType1, $divisionRoundGroupPosition1, $isSamePositionType, $team1['position_type'], $startRoundCount, $startMatchCount, 'home');
                            }

                            if($team2['position_type'] === 'placed') {
                                $teamRoundData2 = $this->getTeamRoundDataForRoundRobin($templateFormDetail, $divisionRoundGroupPosition2, $divisionIndex);
                                $teamGroupType2 = $teamRoundData2['groups'][$divisionRoundGroupPosition2[2]]['type'];
                                $awayTeamData = $this->getRoundRobinTeamData($teamGroupType2, $teamRoundData2, $divisionRoundGroupPosition2, 'away');
                            }
                            if($team2['position_type'] === 'winner' || $team2['position_type'] === 'looser') {
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

                            if (($team1['position_type'] == 'winner' && $team2['position_type'] == 'winner') || ($team1['position_type'] == 'looser' && $team2['position_type'] == 'looser')) {
                                $isPlacingMatchAsRoundRobin = true;
                            }

                            if(($team1['position_type'] == 'winner' && $team2['position_type'] == 'winner') || ($team1['position_type'] == 'looser' && $team2['position_type'] == 'looser')) {
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

                    if(($homePositionType == 'winner' && $awayPositionType == 'winner') || ($homePositionType == 'looser' && $awayPositionType == 'looser')) {
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

                        if($teams[$teamIndex]['position_type'] == 'winner' || $teams[$teamIndex]['position_type'] == 'looser') {
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

                        if($teams[$teamIndex + 1]['position_type'] == 'winner' || $teams[$teamIndex + 1]['position_type'] == 'looser') {
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

                    if( (($divisionIndex === -1 && ($roundIndex+1) === count($templateFormDetail['steptwo']['rounds'])) || ($divisionIndex >= 0 && $round === count($templateFormDetail['steptwo']['divisions'][$divisionIndex]['rounds'])))
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
                // if($divisionIndex === -1 && $roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
                //     $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][$firstPlacingMatchIndex]['dependent_groups'][] = $matchTypeDetail;
                // } else {
                    $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][] = $matchTypeDetail;
                // }
            } else if($divisionIndex >= 0) {
                $finalArray['tournament_competation_format']['divisions'][$divisionIndex]['format_name'][$roundIndex]['name'] = 'Round '.($startRoundCount + $roundIndex + 1);
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
        $looserPosition = collect($positionArray)->where('position', $teamPosition)->where('position_type', 'looser')->keys()->toArray();

        $position = '';
        if(!empty($winnerPosition) && !empty($looserPosition)) {
            $position = (head($winnerPosition) + 1) . '-' .(head($looserPosition) + 1);
        }

        return $position;
    }
}