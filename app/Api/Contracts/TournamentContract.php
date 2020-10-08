<?php

namespace Laraspace\Api\Contracts;

interface TournamentContract
{
    /*
     * Get All Tournaments
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index();

    public function generateReport($request);
}
