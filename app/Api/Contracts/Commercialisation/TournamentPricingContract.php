<?php

namespace Laraspace\Api\Contracts\Commercialisation;

interface TournamentPricingContract
{
    /*
     * Save tournament pricing detail
     *
     * @param  array $api_key,$state,$type
     * @return response
     */	
 	public function saveTournamentPricingDetail($request);   
}