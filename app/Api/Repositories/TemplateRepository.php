<?php

namespace Laraspace\Api\Repositories;

use DB;
use Auth;
use Laraspace\Models\User;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\TournamentCompetationTemplates;

class TemplateRepository
{
    use AuthUserDetail;

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

        $finalArray = [];
        $finalArray['total_matches'] = '';
        // $finalArray['tournament_id'] = 15;
        $finalArray['tournament_teams'] = $data['templateFormDetail']['stepone']['no_of_teams'];
        $finalArray['remark'] = $data['templateFormDetail']['stepfour']['remarks'];
        $finalArray['template_font_color'] = $data['templateFormDetail']['stepfour']['template_font_color'];
        $finalArray['tournament_name'] = '';
        $finalArray['competition_round'] = '';
        $finalArray['competition_group_round'] = '';
        $finalArray['competation_format'] = '';
        $finalArray['tournament_min_match'] = '';
        $finalArray['avg_game_team'] = '';
        $finalArray['position_type'] = '';
        $finalArray['tournament_competation_format'] = [];
        $finalArray['tournament_competation_format']['format_name'] = [];
        $finalArray['tournament_positions'] = [];

        $rounds = [];
        $groupCount = 0;
        foreach ($data['templateFormDetail']['steptwo']['rounds'] as $roundIndex => $round) {
            $finalArray['tournament_competation_format']['format_name'][$roundIndex]['name'] = 'Round '.($roundIndex+1);

            $firstPlacingMatchIndex = array_search('placing_match', array_column($round['groups'], 'type'));
            foreach ($round['groups'] as $groupIndex => $group) {
                $matches = [];

                $groupData = $group;
                $times = $groupData['teams_play_each_other'];
                $noOfTeams = $groupData['no_of_teams'];
                $totalTimes = $teamsPlayedEachOther[$times];
                $groupName = null;

                if($roundIndex === 0 && $group['type'] === 'round_robin') {
                    foreach ($group['matches'] as $match) {
                        array_push($matches, ['in_between' => $match['in_between'], 'match_number' => $match['match_number'], 'display_match_number' => $match['display_match_number'], 'display_home_team_placeholder_name' => $match['display_home_team_placeholder_name'], 'display_away_team_placeholder_name' => $match['display_away_team_placeholder_name']]);
                    }
                }

                $matchCount = 1;
                if($roundIndex === 0 && $groupIndex === $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
                    for($i=1; $i<=$noOfTeams; $i=$i+2) {
                        $home = $i;
                        $away = $i + 1;
                        $inBetween = $home . "-" . $away;
                        $matchNumber = "CAT.PM" + ($roundIndex+1) + ".G" + $matchCount + "." + $home + "-" + $away;
                        $displayMatchNumber = "CAT." + ($roundIndex+1) + "." + $matchCount + ".@HOME-@AWAY";
                        $displayHomeTeamPlaceholderName = $home;
                        $displayAwayTeamPlaceholderName = $away;

                        array_push($matches, ['in_between' => $inBetween, 'match_number' => $matchNumber, 'display_match_number' => $displayMatchNumber, 'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName, 'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName]);

                        $matchCount++;
                    }
                }

                if($roundIndex === 0 && $groupIndex !== $firstPlacingMatchIndex && $group['type'] === 'placing_match') {
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

                                $matches[] = [
                                    'in_between' => $inBetween,
                                    'match_number' => $matchNumber,
                                    'display_match_number' => $displayMatchNumber,
                                    'display_home_team_placeholder_name' => $displayHomeTeamPlaceholderName,
                                    'display_away_team_placeholder_name' => $displayAwayTeamPlaceholderName,
                                ];

                                $matchCount++;
                            }
                        }
                    }
                }
                
                echo "<pre>";print_r($matches);exit;
                
                $finalGroupCount = 65 + $groupCount + $groupIndex;
                $matchTypeDetail = [
                    'name' => '',
                    'total_match' => $group['no_of_teams'],
                    'group_count' => count($round['groups']),
                    'groups' => ['group_name' => 'Group-' .chr($finalGroupCount), 'match' => $matches]
                ];

                $tournamentsPositionsData = [
                    'position' => '',
                    'dependent_type' => '',
                    'ranking' => ''
                ];

                $finalArray['tournament_competation_format']['format_name'][$roundIndex]['match_type'][] = $matchTypeDetail;
                $finalArray['tournament_positions'][] = $tournamentsPositionsData;
            }
            $groupCount += count($round['groups']);
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
        $tournamentTemplate = new TournamentTemplates();
        $tournamentTemplate->json_data = $templateJson;
        $tournamentTemplate->name = $data['templateFormDetail']['stepone']['templateName'];
        $tournamentTemplate->total_teams = $data['templateFormDetail']['stepone']['no_of_teams'];
        $tournamentTemplate->editor_type = $data['templateFormDetail']['stepone']['editor'];
        $tournamentTemplate->template_form_detail = json_encode($data['templateFormDetail']);
        $tournamentTemplate->version = array_get($data,'version', 1);
        $tournamentTemplate->inherited_from = array_get($data,'inherited_from', NULL);
        $tournamentTemplate->created_by = Auth::user()->id;
        $tournamentTemplate->save();

        // file_put_contents(resource_path('templates') . '/' . 'template555.json', $templateJson);

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

        // file_put_contents(resource_path('templates') . '/' . 'template555.json', $templateJson);

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
}