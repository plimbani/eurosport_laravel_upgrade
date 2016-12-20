<?php

namespace App\Api\Contracts;

interface MatchContract
{
    /*
     * Get Match Data
     *
     * @param  array $data
     * @return response
     */

    public function getAllMatches();

    /*
     * Create New Match
     *
     * @param  array $api_key,$data
     * @return response
     */
    public function createMatch($data);

    /*
     * Edit Match
     *
     * @param  array $api_key,$matchId,$data
     * @return response
     */
    public function edit($request, $matchId);

    /*
     * Delete Match
     *
     * @param  array $api_key,$matchId
     * @return response
     */
    public function deleteMatch($matchId);
}
