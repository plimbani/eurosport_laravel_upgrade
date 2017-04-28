<?php

namespace Laraspace\Api\Contracts;

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
     * @param  array $api_key,$match_id,$data
     * @return response
     */
    public function edit($request);

    /*
     * Delete Match
     *
     * @param  array $api_key,$match_id
     * @return response
     */
    public function deleteMatch($deleteId);
    public function scheduleMatch($matchData);
    public function unscheduleMatch($matchData);
    public function getAllScheduledMatch($matchData);
    public function getMatchDetail($matchData);
    public function removeAssignedReferee($matchData);
    public function assignReferee($matchData);
    public function saveResult($matchData);
    
    

}
