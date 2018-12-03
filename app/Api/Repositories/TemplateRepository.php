<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\User;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\TournamentCompetationTemplates;

class TemplateRepository
{
    /*
     * Get all templates
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTemplates($data)
    {
        $templates = TournamentTemplates::leftjoin('users', 'tournament_template.created_by', '=', 'users.id');                                        
        if(isset($data['teamSearch']) && $data['teamSearch'] !== '') {
            $templates->where('total_teams', $data['teamSearch']);
        }
        if(isset($data['createdBySearch']) && $data['createdBySearch'] !== '') {
            $templates->where('created_by', $data['createdBySearch']);
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
        echo "<pre>";print_r($data);echo "</pre>";exit;
        $finalArray = [];
        $finalArray['total_matches'] = 9;
        $finalArray['tournament_id'] = 15;
        $finalArray['tournament_teams'] = $data['templateFormDetail']['stepone']['teams'];
        $finalArray['remark'] = '';
        $finalArray['template_font_color'] = '';
        $finalArray['tournament_name'] = '';
        $finalArray['competition_round'] = '';
        $finalArray['competition_group_round'] = '';
        $finalArray['competation_format'] = '';
        $finalArray['tournament_min_match'] = '';
        $finalArray['avg_game_team'] = '';
        $finalArray['position_type'] = '';
        $finalArray['tournament_competition_ranking']['format_name'] = [];

        $rounds = [];
        foreach ($data['templateFormDetail']['steptwo']['rounds'] as $key => $round) {
            // echo "<pre>";print_r($round);echo "</pre>";exit;
            $finalArray['tournament_competition_ranking']['format_name']['round'] = 'Round ' .$key;
        }
        echo "<pre>";print_r(json_encode($finalArray));echo "</pre>";exit;
    }

    /*
     * Delete template
     *
     * @param  array $id
     * @return response
     */
    public function deleteTemplate($id)
    {
        echo "<pre>";print_r($id);echo "</pre>";exit;
    }
}