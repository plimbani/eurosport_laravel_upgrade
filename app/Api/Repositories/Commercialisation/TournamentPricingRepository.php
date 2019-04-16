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
        $tournamentPricingBandsCup = TournamentPricing::where('type', '=', 'cup')->select('tournament_pricings.id', 'tournament_pricings.type', 'tournament_pricings.min_teams', 'tournament_pricings.max_teams', 'tournament_pricings.price', 'tournament_pricings.advanced_price')->get()->toArray();

         $tournamentPricingBandsLeague = TournamentPricing::where('type', '=', 'league')->select('tournament_pricings.id', 'tournament_pricings.type', 'tournament_pricings.min_teams', 'tournament_pricings.max_teams', 'tournament_pricings.price', 'tournament_pricings.advanced_price')->get()->toArray();
         
        $tournamentPricingsCup['cup']['bands'] = $tournamentPricingBandsCup;
        $tournamentPricingsCup['league']['bands'] = $tournamentPricingBandsLeague;

        return json_encode($tournamentPricingsCup);
    }

    public function saveTournamentPricingDetail($tournamentPricingData)
    {
        $type = $tournamentPricingData['tournamentPricingData']['type'];
        $allPricingData = TournamentPricing::where('type', $type)->pluck('id')->toArray();
        $allUpdatedPricingsDetail = [];
        foreach ($tournamentPricingData['tournamentPricingData']['data'] as $data) {
            if($data['id'] != null) {
                $deletedPriceDetailIds = array_search($data['id'], $allPricingData);
                unset($allPricingData[$deletedPriceDetailIds]);
                $pricing = TournamentPricing::find($data['id']);
                $updatedData = $this->updateTournamentPricingDetail($pricing, $data, $state='update');
            } else {
                $tournamentPricing = new TournamentPricing();
                $updatedData = $this->updateTournamentPricingDetail($tournamentPricing, $data, $state='create') ;
            }

            $allUpdatedPricingsDetail[] = $updatedData;
        }

        if($allPricingData) {
            TournamentPricing::whereIn('id', $allPricingData)->where('type', $type)->delete();
        }

        return $allUpdatedPricingsDetail;
    }

    public function updateTournamentPricingDetail($pricingDetail, $data, $state)
    {
        $pricingDetail->type = $data['type'];
        $pricingDetail->min_teams = $data['min_teams'];
        $pricingDetail->max_teams = $data['max_teams'];
        $pricingDetail->price = $data['price'];
        $pricingDetail->advanced_price = isset($data['advanced_price']) ? $data['advanced_price'] : null;
        $pricingDetail->created_by = $state == 'create' ? $this->getCurrentLoggedInUserId() : null;
        $pricingDetail->updated_by = $state == 'update' ? $this->getCurrentLoggedInUserId() : null;
        $pricingDetail->save();

        return $pricingDetail;
    }
}
