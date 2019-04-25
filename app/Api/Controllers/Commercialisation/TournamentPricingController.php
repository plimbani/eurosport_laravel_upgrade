<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Illuminate\Http\Request;
use Laraspace\Http\Requests\Commercialisation\Pricing\SaveRequest;
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

    public function saveTournamentPricingDetail(SaveRequest $request) {
    	return $this->tournamentPricingObj->saveTournamentPricingDetail($request->all());
    }
}
