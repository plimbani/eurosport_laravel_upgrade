<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Illuminate\Http\Request;
use Laraspace\Api\Contracts\Commercialisation\TournamentPricingContract;
use Laraspace\Api\Repositories\Commercialisation\TournamentPricingRepository;

class TournamentPricingController extends BaseController
{   

    public function __construct(TournamentPricingContract $tournamentPricingObj)
    {
        $this->tournamentPricingObj = $tournamentPricingObj;
    }

    public function getTournamentPricingBands() {
        return $this->tournamentPricingObj->getTournamentPricingBands();
    }

    public function saveTournamentPricingDetail(Request $request) {
    	return $this->tournamentPricingObj->saveTournamentPricingDetail($request->all());
    }
}
