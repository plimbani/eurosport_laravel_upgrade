<?php

namespace Laraspace\Contracts;

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
}
