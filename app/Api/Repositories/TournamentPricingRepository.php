<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\TournamentPricing;

class TournamentRepository
{
    /**
     * Get tournament pricing bands
     * @return array
    */
    public function getTournamentPricingBands()
    {
        $tournamentPricingBandsCup = TournamentPricing::where('type', '=', 'cup')->select('tournament_pricings.min_teams', 'tournament_pricings.max_teams', 'tournament_pricings.price', 'tournament_pricings.advanced_price')->get()->toArray();

         $tournamentPricingBandsLeague = TournamentPricing::where('type', '=', 'league')->select('tournament_pricings.min_teams', 'tournament_pricings.max_teams', 'tournament_pricings.price', 'tournament_pricings.advanced_price')->get()->toArray();
         
        $tournamentPricingsCup['cup']['bands'] = $tournamentPricingBandsCup;
        $tournamentPricingsCup['league']['bands'] = $tournamentPricingBandsLeague;
        return json_encode($tournamentPricingsCup);
    }
}
