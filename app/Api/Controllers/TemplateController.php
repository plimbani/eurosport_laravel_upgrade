<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Api\Contracts\TemplateContract;

class TemplateController extends BaseController
{
    protected $templateObj;

	public function __construct(TemplateContract $templateObj)
    {
        $this->templateObj = $templateObj;
    }

    /**
     * Get all templates
     */
    public function getTemplates(Request $request)
    {
       return $this->templateObj->getTemplates($request->all());
    }

    /**
     * Get template detail
     */
    public function getTemplateDetail(Request $request)
    {
        return $this->templateObj->getTemplateDetail($request->all());
    }

    /**
     * Get users for filter
     */
    public function getUsersForFilter(Request $request)
    {
        return $this->templateObj->getUsersForFilter();
    }

    /**
     * Save template data
     */
    public function saveTemplateDetail(Request $request)
    {
        return $this->templateObj->saveTemplateDetail($request->all());
    }

    /**
     * Delete template
     */
    public function deleteTemplate(Request $request, $id)
    {
        return $this->templateObj->deleteTemplate($id);
    }

    /**
     * Edit template
     */
    public function editTemplate(Request $request, $id)
    {
        return $this->templateObj->editTemplate($id);
    }

    /**
     * Update template
     */
    public function updateTemplateDetail(Request $request)
    {
        return $this->templateObj->updateTemplateDetail($request->all());
    }

    /**
     * Compare json template
     */
    public function compareJsonTemplate(Request $request, $oldTemplateId, $newTemplateId)
    {
        $errors = [];
        $oldTournamentTemplate = TournamentTemplates::find($oldTemplateId);
        $newTournamentTemplate = TournamentTemplates::find($newTemplateId);

        $oldTemplateJson = json_decode($oldTournamentTemplate->json_data, true);
        $newTemplateJson = json_decode($newTournamentTemplate->json_data, true);

        if($newTemplateJson['total_matches'] != $oldTemplateJson['total_matches']) {
            $errors['total_matches'] = 'Total matches does not match.<br>Old total matches is: ' .$oldTemplateJson['total_matches'] .'<br>New total matches is: ' .$newTemplateJson['total_matches'];
        }
        if($newTemplateJson['tournament_teams'] != $oldTemplateJson['tournament_teams']) {
            $errors['tournament_teams'] = 'Tournament teams does not match.<br>Old Tournament teams is: ' .$oldTemplateJson['tournament_teams'] .'<br>New Tournament teams is: ' .$newTemplateJson['tournament_teams'];
        }        
        if($newTemplateJson['tournament_min_match'] != $oldTemplateJson['tournament_min_match']) {
            $errors['tournament_min_match'] = 'Tournament min match does not match.<br>Old Tournament min match is: ' .$oldTemplateJson['tournament_min_match'] .'<br>New Tournament teams is: ' .$newTemplateJson['tournament_min_match'];
        }
        if($newTemplateJson['avg_game_team'] != $oldTemplateJson['avg_game_team']) {
            $errors['avg_game_team'] = 'Average game team does not match.<br>Old Average game team is: ' .$oldTemplateJson['avg_game_team'] .'<br>New Tournament teams is: ' .$newTemplateJson['avg_game_team'];
        }
        if($newTemplateJson['position_type'] != $oldTemplateJson['position_type']) {
            $errors['position_type'] = 'Position type does not match.<br>Old Position type is: ' .$oldTemplateJson['position_type'] .'<br>New Position type is: ' .$newTemplateJson['position_type'];
        }

        // check tournament competition format
        $oldTemplateCompetitionFormat = $oldTemplateJson['tournament_competation_format'];
        $newTemplateCompetitionFormat = $newTemplateJson['tournament_competation_format'];

        if(!isset($newTemplateJson['divisions'])) {
            foreach ($newTemplateCompetitionFormat['format_name'] as $roundIndex => $round) {
                if(isset($round['name']) && $round['name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['name']) {
                    $errors['round_name'] = 'Round name does not match.<br>Old round name is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['name'] .'<br>New round name is: ' .$round['name'];
                }

                foreach ($round['match_type'] as $groupIndex => $group) {
                    // for round robin match
                    if(starts_with($group['name'], 'RR')) {
                        if(isset($group['name']) &&  $group['name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name']) {
                            $errors['name'] = 'Name does not match.<br>Type: Round robin<br>Old name is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name'] .'<br>New name is: ' .$group['name'];
                        }

                        if(isset($group['total_match']) &&  $group['total_match'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match']) {
                            $errors['total_match'] = 'Total match does not match.<br>Type: Round robin<br>Old Total match is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match'] .'<br>New Total match is: ' .$group['total_match'];
                        }

                        if(isset($group['group_count']) &&  $group['group_count'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count']) {
                            $errors['group_count'] = 'Group count does not match.<br>Type: Round robin<br>Old Group count is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count'] .'<br>New Group count is: ' .$group['group_count'];
                        }

                        if(isset($group['groups']['group_name']) &&  $group['groups']['group_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name']) {
                            $errors['group_name'] = 'Group name does not match.<br>Type: Round robin<br>Old Group name is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name'] .'<br>New Group name is: ' .$group['groups']['group_name'];
                        }

                        // match
                        foreach ($group['groups']['match'] as $matchIndex => $match) {
                            $inBetweenMatchedIndex = collect($oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'])->where('in_between', $match['in_between'])->keys()->toArray();

                            if(isset($match['in_between']) && $match['in_between'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['in_between']) {
                                $errors['in_between'] = 'In-between does not match.<br>Type: Round robin<br>Old in between is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['in_between'] .'<br>New in between is: ' .$match['in_between'];
                            }

                            if(isset($match['match_number']) && $match['match_number'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['match_number']) {
                                $errors['match_number'] = 'Match number does not match.<br>Type: Round robin<br>Old match number is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['match_number'] .'<br>New match number is: ' .$match['match_number'];
                            }

                            if(isset($match['display_match_number']) && $match['display_match_number'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_match_number']) {
                                $errors['display_match_number'] = 'Display match number does not match.<br>Type: Round robin<br>Old display match number is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_match_number'] .'<br>New display match number is: ' .$match['display_match_number'];
                            }

                            if(isset($match['display_home_team_placeholder_name']) && $match['display_home_team_placeholder_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_home_team_placeholder_name']) {
                                $errors['display_home_team_placeholder_name'] = 'Display home team placeholder name does not match.<br>Type: Round robin<br>Old Display home team placeholder name is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_home_team_placeholder_name'] .'<br>New Display home team placeholder name is: ' .$match['display_home_team_placeholder_name'];
                            }

                            if(isset($match['display_away_team_placeholder_name']) && $match['display_away_team_placeholder_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_away_team_placeholder_name']) {
                                $errors['display_away_team_placeholder_name'] = 'Display away team placeholder name does not match.<br>Type: Round robin<br>Old Display away team placeholder name is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_away_team_placeholder_name'] .'<br>New Display away team placeholder name is: ' .$match['display_away_team_placeholder_name'];                                
                            }
                        }
                    }

                    // for placing match
                    if(starts_with($group['name'], 'PM')) {
                        if(isset($group['name']) &&  $group['name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name']) {
                            $errors['name'] = 'Name does not match.<br>Type: Placing match<br>Old name is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name'] .'<br>New name is: ' .$group['name'];
                        }

                        if(isset($group['total_match']) &&  $group['total_match'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match']) {
                            $errors['total_match'] = 'Total match does not match.<br>Type: Placing match<br>Old Total match is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match'] .'<br>New Total match is: ' .$group['total_match'];
                        }

                        if(isset($group['group_count']) &&  $group['group_count'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count']) {
                            $errors['group_count'] = 'Group count does not match.<br>Type: Placing match<br>Old Group count is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count'] .'<br>New Group count is: ' .$group['group_count'];
                        }

                        if(isset($group['groups']['group_name']) &&  $group['groups']['group_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name']) {
                            $errors['group_name'] = 'Group name does not match.<br>Type: Placing match<br>Old Group name is: ' .$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name'] .'<br>New Group name is: ' .$group['groups']['group_name'];
                        }
                    }
                }
            }
        }

        // check position
        $oldTemplatePositions = $oldTemplateJson['tournament_positions'];
        $newTemplateJsonPositions = $newTemplateJson['tournament_positions'];
        foreach ($newTemplateJsonPositions as $key => $position) {
            if(isset($position['position']) && $position['position'] != $oldTemplatePositions[$key]['position']) {
                $errors['position'] = 'Position does not match.<br>Old position is: ' .$oldTemplatePositions[$key]['position'] .'<br>New position is: ' .$position['position'];
            }
            if(isset($position['dependent_type']) && $position['dependent_type'] != $oldTemplatePositions[$key]['dependent_type']) {
                $errors['dependent_type'] = 'Dependent type does not match.<br>Old dependent type is: ' .$oldTemplatePositions[$key]['dependent_type'] .'<br>New dependent type is: ' .$position['dependent_type'];
            }
            if(isset($position['match_number']) && $position['match_number'] != $oldTemplatePositions[$key]['match_number']) {
                $errors['match_number'] = 'Match number does not match.<br>Old Match number is: ' .$oldTemplatePositions[$key]['match_number'] .'<br>New Match number is: ' .$position['match_number'];
            }
            if(isset($position['result_type']) && $position['result_type'] != $oldTemplatePositions[$key]['result_type']) {
                $errors['result_type'] = 'Result type does not match.<br>Old Result type is: ' .$oldTemplatePositions[$key]['result_type'] .'<br>New Result type is: ' .$position['result_type'];
            }
            if(isset($position['ranking']) && $position['ranking'] != $oldTemplatePositions[$key]['ranking']) {
                $errors['ranking'] = 'Ranking does not match.<br>Old Ranking is: ' .$oldTemplatePositions[$key]['ranking'] .'<br>New Ranking is: ' .$position['ranking'];
            }
        }

        foreach ($errors as $key => $error) {
            echo "<pre>";print_r("<b>$key</b>");echo "</pre>";
            echo "<pre>";print_r($error);echo "</pre>";
        }
    }
}