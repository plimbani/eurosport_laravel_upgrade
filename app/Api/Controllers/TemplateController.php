<?php

namespace App\Api\Controllers;

use Illuminate\Support\Str;
use App\Api\Contracts\TemplateContract;
use App\Api\Repositories\TemplateRepository;
use App\Http\Requests\Template\DeleteRequest;
use App\Http\Requests\Template\EditRequest;
use App\Http\Requests\Template\GetTemplatesRequest;
use App\Http\Requests\Template\StoreRequest;
use App\Http\Requests\Template\TemplateDetailRequest;
use App\Http\Requests\Template\UpdateRequest;
use App\Models\TournamentCompetationTemplates;
use App\Models\TournamentTemplates;
use Illuminate\Http\Request;

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
    public function getTemplates(GetTemplatesRequest $request)
    {
        return $this->templateObj->getTemplates($request->all());
    }

    /**
     * Get template detail
     */
    public function getTemplateDetail(TemplateDetailRequest $request)
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
    public function saveTemplateDetail(StoreRequest $request)
    {
        return $this->templateObj->saveTemplateDetail($request->all());
    }

    /**
     * Delete template
     */
    public function deleteTemplate(DeleteRequest $request, $id)
    {
        return $this->templateObj->deleteTemplate($id);
    }

    /**
     * Edit template
     */
    public function editTemplate(EditRequest $request, $id)
    {
        return $this->templateObj->editTemplate($id);
    }

    /**
     * Update template
     */
    public function updateTemplateDetail(UpdateRequest $request)
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

        if ($newTemplateJson['total_matches'] != $oldTemplateJson['total_matches']) {
            $errors[] = '<b>Total matches does not match.</b><br>Old total matches is: '.$oldTemplateJson['total_matches'].'<br>New total matches is: '.$newTemplateJson['total_matches'];
        }
        if ($newTemplateJson['template_font_color'] != $oldTemplateJson['template_font_color']) {
            $errors[] = '<b>Template font color does not match.</b><br>Old Template font color is: '.$oldTemplateJson['template_font_color'].'<br>New Template font color is: '.$newTemplateJson['template_font_color'];
        }
        if ($newTemplateJson['tournament_teams'] != $oldTemplateJson['tournament_teams']) {
            $errors[] = '<b>Tournament teams does not match.</b><br>Old Tournament teams is: '.$oldTemplateJson['tournament_teams'].'<br>New Tournament teams is: '.$newTemplateJson['tournament_teams'];
        }
        if ($newTemplateJson['tournament_min_match'] != $oldTemplateJson['tournament_min_match']) {
            $errors[] = '<b>Tournament min match does not match.</b><br>Old Tournament min match is: '.$oldTemplateJson['tournament_min_match'].'<br>New Tournament teams is: '.$newTemplateJson['tournament_min_match'];
        }
        if ($newTemplateJson['avg_game_team'] != $oldTemplateJson['avg_game_team']) {
            $errors[] = '<b>Average game team does not match.</b><br>Old Average game team is: '.$oldTemplateJson['avg_game_team'].'<br>New Tournament teams is: '.$newTemplateJson['avg_game_team'];
        }
        if ($newTemplateJson['position_type'] != $oldTemplateJson['position_type']) {
            $errors[] = '<b>Position type does not match.</b><br>Old Position type is: '.$oldTemplateJson['position_type'].'<br>New Position type is: '.$newTemplateJson['position_type'];
        }

        // check tournament competition format
        $oldTemplateCompetitionFormat = $oldTemplateJson['tournament_competation_format'];
        $newTemplateCompetitionFormat = $newTemplateJson['tournament_competation_format'];

        if (! isset($newTemplateJson['divisions'])) {
            foreach ($newTemplateCompetitionFormat['format_name'] as $roundIndex => $round) {
                if (isset($round['name']) && $round['name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['name']) {
                    $errors[] = '<b>Round name does not match.</b><br>Old round name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['name'].'<br>New round name is: '.$round['name'];
                }

                foreach ($round['match_type'] as $groupIndex => $group) {
                    // for round robin match
                    if (Str::startsWith($group['name'], 'RR')) {
                        if (isset($group['name']) && $group['name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name']) {
                            $errors[] = '<b>Match type name does not match.</b><br>Type: Round robin<br>Old Match type name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name'].'<br>New Match type name is: '.$group['name'];
                        }

                        if (isset($group['total_match']) && $group['total_match'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match']) {
                            $errors[] = '<b>Total match does not match.</b><br>Type: Round robin<br>Old Total match is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match'].'<br>New Total match is: '.$group['total_match'];
                        }

                        if (isset($group['group_count']) && $group['group_count'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count']) {
                            $errors[] = '<b>Group count does not match.</b><br>Type: Round robin<br>Old Group count is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count'].'<br>New Group count is: '.$group['group_count'];
                        }

                        if (isset($group['groups']['group_name']) && $group['groups']['group_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name']) {
                            $errors[] = '<b>Group name does not match.</b><br>Type: Round robin<br>Old Group name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name'].'<br>New Group name is: '.$group['groups']['group_name'];
                        }

                        // match
                        foreach ($group['groups']['match'] as $matchIndex => $match) {
                            $inBetweenMatchedIndex = collect($oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'])->where('in_between', $match['in_between'])->keys()->toArray();

                            if (isset($match['in_between']) && $match['in_between'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['in_between']) {
                                $errors[] = '<b>In-between does not match.</b><br>Type: Round robin<br>Old in between is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['in_between'].'<br>New in between is: '.$match['in_between'];
                            }

                            if (isset($match['match_number']) && $match['match_number'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['match_number']) {
                                $errors[] = '<b>Match number does not match.</b><br>Type: Round robin<br>Old match number is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['match_number'].'<br>New match number is: '.$match['match_number'];
                            }

                            if (isset($match['display_match_number']) && $match['display_match_number'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_match_number']) {
                                $errors[] = '<b>Display match number does not match.</b><br>Type: Round robin<br>Old display match number is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_match_number'].'<br>New display match number is: '.$match['display_match_number'];
                            }

                            if (isset($match['display_home_team_placeholder_name']) && $match['display_home_team_placeholder_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_home_team_placeholder_name']) {
                                $errors[] = '<b>Display home team placeholder name does not match.</b><br>Type: Round robin<br>Old Display home team placeholder name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_home_team_placeholder_name'].'<br>New Display home team placeholder name is: '.$match['display_home_team_placeholder_name'];
                            }

                            if (isset($match['display_away_team_placeholder_name']) && $match['display_away_team_placeholder_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_away_team_placeholder_name']) {
                                $errors[] = '<b>Display away team placeholder name does not match.</b><br>Type: Round robin<br>Old Display away team placeholder name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][head($inBetweenMatchedIndex)]['display_away_team_placeholder_name'].'<br>New Display away team placeholder name is: '.$match['display_away_team_placeholder_name'];
                            }
                        }
                    }

                    // for placing match
                    if (Str::startsWith($group['name'], 'PM')) {
                        if (isset($group['name']) && $group['name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name']) {
                            $errors[] = '<b>Name does not match.</b><br>Type: Placing match<br>Old name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['name'].'<br>New name is: '.$group['name'];
                        }
                        if (isset($group['total_match']) && $group['total_match'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match']) {
                            $errors[] = '<b>Total match does not match.</b><br>Type: Placing match<br>Old Total match is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['total_match'].'<br>New Total match is: '.$group['total_match'];
                        }
                        if (isset($group['group_count']) && $group['group_count'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count']) {
                            $errors[] = '<b>Group count does not match.</b><br>Type: Placing match<br>Old Group count is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['group_count'].'<br>New Group count is: '.$group['group_count'];
                        }
                        if (isset($group['groups']['group_name']) && $group['groups']['group_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name']) {
                            $errors[] = '<b>Group name does not match.</b><br>Type: Placing match<br>Old Group name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['group_name'].'<br>New Group name is: '.$group['groups']['group_name'];
                        }

                        // match
                        foreach ($group['groups']['match'] as $matchIndex => $match) {

                            if (isset($match['in_between']) && $match['in_between'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['in_between']) {
                                $errors[] = '<b>In-between does not match.</b><br>Type: Placing match<br>Old in between is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['in_between'].'<br>New in between is: '.$match['in_between'];
                            }
                            if (isset($match['match_number']) && $match['match_number'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['match_number']) {
                                $errors[] = '<b>Match number does not match.</b><br>Type: Placing match<br>Old match number is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['match_number'].'<br>New match number is: '.$match['match_number'];
                            }
                            if (isset($match['display_match_number']) && $match['display_match_number'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['display_match_number']) {
                                $errors[] = '<b>Display match number does not match.</b><br>Type: Placing match<br>Old display match number is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['display_match_number'].'<br>New display match number is: '.$match['display_match_number'];
                            }
                            if (isset($match['display_home_team_placeholder_name']) && $match['display_home_team_placeholder_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['display_home_team_placeholder_name']) {
                                $errors[] = '<b>Display home team placeholder name does not match.</b><br>Type: Placing match<br>Old Display home team placeholder name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['display_home_team_placeholder_name'].'<br>New Display home team placeholder name is: '.$match['display_home_team_placeholder_name'];
                            }
                            if (isset($match['display_away_team_placeholder_name']) && $match['display_away_team_placeholder_name'] != $oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['display_away_team_placeholder_name']) {
                                $errors[] = '<b>Display away team placeholder name does not match.</b><br>Type: Placing match<br>Old Display away team placeholder name is: '.$oldTemplateCompetitionFormat['format_name'][$roundIndex]['match_type'][$groupIndex]['groups']['match'][$matchIndex]['display_away_team_placeholder_name'].'<br>New Display away team placeholder name is: '.$match['display_away_team_placeholder_name'];
                            }
                        }
                    }
                }
            }
        }

        // check position
        $oldTemplatePositions = $oldTemplateJson['tournament_positions'];
        $newTemplateJsonPositions = $newTemplateJson['tournament_positions'];
        foreach ($newTemplateJsonPositions as $key => $position) {
            if (isset($position['position']) && $position['position'] != $oldTemplatePositions[$key]['position']) {
                $errors[] = '<b>Position does not match.</b><br>Old position is: '.$oldTemplatePositions[$key]['position'].'<br>New position is: '.$position['position'];
            }
            if (isset($position['dependent_type']) && $position['dependent_type'] != $oldTemplatePositions[$key]['dependent_type']) {
                $errors[] = '<b>Dependent type does not match.</b><br>Old dependent type is: '.$oldTemplatePositions[$key]['dependent_type'].'<br>New dependent type is: '.$position['dependent_type'];
            }
            if (isset($position['match_number']) && $position['match_number'] != $oldTemplatePositions[$key]['match_number']) {
                $errors[] = '<b>Match number does not match.</b><br>Old Match number is: '.$oldTemplatePositions[$key]['match_number'].'<br>New Match number is: '.$position['match_number'];
            }
            if (isset($position['result_type']) && $position['result_type'] != $oldTemplatePositions[$key]['result_type']) {
                $errors[] = '<b>Result type does not match.</b><br>Old Result type is: '.$oldTemplatePositions[$key]['result_type'].'<br>New Result type is: '.$position['result_type'];
            }
            if (isset($position['ranking']) && $position['ranking'] != $oldTemplatePositions[$key]['ranking']) {
                $errors[] = '<b>Ranking does not match.</b><br>Old Ranking is: '.$oldTemplatePositions[$key]['ranking'].'<br>New Ranking is: '.$position['ranking'];
            }
        }

        foreach ($errors as $key => $error) {
            echo '<pre>';
            print_r($error);
            echo '</pre>';
        }
    }

    public function updateTemplateFormDetail(Request $request)
    {
        $templates = TournamentTemplates::where('template_form_detail', '!=', '')->get();
        foreach ($templates as $key => $template) {
            $templateFormDetail = json_decode($template->template_form_detail, true);
            $rounds = $templateFormDetail['steptwo']['rounds'];

            foreach ($rounds as $roundIndex => $round) {
                foreach ($round['groups'] as $groupIndex => $group) {
                    $newMatches = [];
                    $groupTeams = count($group['teams']);
                    $groupMatchesCount = $groupTeams / 2;

                    if ($group['type'] == 'round_robin') {
                        $templateFormDetail['steptwo']['rounds'][$roundIndex]['groups'][$groupIndex]['matches'] = [];
                    }

                    if (count($group['matches']) > $groupMatchesCount) {
                        array_splice($group['matches'], $groupMatchesCount);

                        if ($group['type'] == 'placing_match') {
                            foreach ($group['matches'] as $matchIndex => $match) {
                                $newMatches[]['is_final'] = isset($match['is_final']) ? $match['is_final'] : false;
                            }
                            $templateFormDetail['steptwo']['rounds'][$roundIndex]['groups'][$groupIndex]['matches'] = $newMatches;
                        }
                    }
                }
            }

            $template->template_form_detail = json_encode($templateFormDetail);
            $template->save();
        }

        echo '<pre>';
        print_r('script executed!');
        echo '</pre>';
        exit;
    }

    public function templateJsonUpdateScript()
    {
        $templates = TournamentTemplates::where('template_form_detail', '!=', '')->get();

        foreach ($templates as $template) {
            $templateFormDetail = json_decode($template->template_form_detail, true);
            foreach ($templateFormDetail['stepfour'] as $key => $value) {
                $templateFormDetail['stepone'][$key] = $value;
            }
            if (isset($templateFormDetail['stepone']['imagePath'])) {
                unset($templateFormDetail['stepone']['imagePath']);
            }
            unset($templateFormDetail['stepfour']);
            $template->template_form_detail = json_encode($templateFormDetail);
            $template->save();
        }

        echo '<pre>';
        print_r('script executed.');
        echo '</pre>';
        exit;
    }

    public function scriptForDivisionsAndMinimumMatches()
    {
        $templates = TournamentTemplates::where('template_form_detail', '!=', '')->get();
        foreach ($templates as $template) {
            $jsonData = json_decode($template->json_data, true);
            // for average games
            $jsonData['avg_game_team'] = number_format($jsonData['avg_game_team'], 1);
            $template->json_data = json_encode($jsonData);
            $template->save();

            // $templateFormDetail = json_decode($template->template_form_detail, true);
            // $totalDivisions = isset($jsonData['tournament_competation_format']['divisions']) ? sizeof($jsonData['tournament_competation_format']['divisions']) : 0;
            // $jsonData['tournament_min_match'] = $template->minimum_matches;

            // $template->json_data = json_encode($jsonData);
            // $template->no_of_divisions = $totalDivisions;
            // $template->save();
        }

        echo '<pre>';
        print_r('script executed.');
        echo '</pre>';
        exit;
    }

    public function generateTemplateGraphic(Request $request, $ageCategoryId)
    {
        $tournamentCompetationTemplate = TournamentCompetationTemplates::find($ageCategoryId);
        $templateData = [];
        $templateData['ageCategoryId'] = $ageCategoryId;
        $templateData['templateId'] = $tournamentCompetationTemplate->tournament_template_id;
        $graphicDetails = TemplateRepository::getTemplateGraphic($templateData);

        return view('template.graphicimage', ['graphicHtml' => $graphicDetails['graphicHtml']]);
    }

    /**
     * Get template graphic
     */
    public function getTemplateGraphic(Request $request)
    {
        return $this->templateObj->getTemplateGraphic($request->all());
    }

    /**
     * Get template graphic of league
     */
    public function getTemplateGraphicOfLeague(Request $request)
    {
        return $this->templateObj->getTemplateGraphicOfLeague($request->all());
    }
}
