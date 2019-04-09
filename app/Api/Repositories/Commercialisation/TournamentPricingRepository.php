<?php

namespace Laraspace\Api\Repositories\Commercialisation;

use Laraspace\Traits\AuthUserDetail;
use Laraspace\Models\TournamentPricing;

class TournamentPricingRepository
{
    use AuthUserDetail;

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

        // echo "<pre>";print_r($tournamentPricingsCup);echo "</pre>";exit;
        return $tournamentPricingsCup;
    }

    public function saveTournamentPricingDetail($tournamentPricingData)
    {
        foreach ($tournamentPricingData['tournamentPricingData'] as $data) {
            $tournamentPricing = new TournamentPricing();
            $tournamentPricing->type = $data['type'];
            $tournamentPricing->min_teams = $data['min_teams'];
            $tournamentPricing->max_teams = $data['max_teams'];
            $tournamentPricing->price = $data['basic_price'];
            $tournamentPricing->advanced_price = isset($data['advanced_price']) ? $data['advanced_price'] : null;
            $tournamentPricing->created_by = $this->getCurrentLoggedInUserId();
            $tournamentPricing->save();
        }
    }    
}
