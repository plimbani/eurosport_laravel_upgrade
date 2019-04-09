<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Laraspace\Api\Repositories\Commercialisation\TournamentPricingRepository;
use Laraspace\Api\Contracts\Commercialisation\TournamentPricingContract;
use Illuminate\Http\Request;

class TournamentPricingController extends BaseController
{   

    public function __construct(TournamentPricingContract $tournamentPricingObj)
    {
        $this->tournamentPricingObj = $tournamentPricingObj;
        $this->tournamentPricingRepoObj = new TournamentPricingRepository();
    }

    public function getTournamentPricingBands() {
        return $this->tournamentPricingRepoObj->getTournamentPricingBands();
    }

    public function saveTournamentPricingDetail(Request $request) {
    	return $this->tournamentPricingRepoObj->saveTournamentPricingDetail($request->all());
    }
}
