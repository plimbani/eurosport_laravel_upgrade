<?php

namespace Laraspace\Api\Services\Commercialisation;

use Laraspace\Api\Contracts\Commercialisation\TournamentPricingContract;
use Laraspace\Api\Repositories\Commercialisation\TournamentPricingRepository;

class TournamentPricingService implements TournamentPricingContract {
    
    public function __construct(TournamentPricingRepository $tournamentPricingRepoObj)
    {
        $this->tournamentPricingRepoObj = $tournamentPricingRepoObj;
    }

    public function saveTournamentPricingDetail($data)
    {
    	$data = $this->tournamentPricingRepoObj->saveTournamentPricingDetail($data);
      	return ['data' => $data, 'status_code' => '200']; 
    }
}

