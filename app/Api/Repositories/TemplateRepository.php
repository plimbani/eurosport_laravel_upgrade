<?php

namespace Laraspace\Api\Repositories;

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
    public function getTemplates()
    {
       $templates = TournamentTemplates::whereNull('deleted_at')->get();
       return $templates;
    }

    public function getTemplateDetail($data)
    {
        $tournamentTemplates = TournamentCompetationTemplates::where('tournament_template_id', $data['templateData']['id'])
                                                            ->distinct('tournament_id')
                                                            ->get();

        $tournamentTemplates = $tournamentTemplates->groupBy(function($item) {
            return $item->tournament_id;
        });

        return $tournamentTemplates;
    }
}