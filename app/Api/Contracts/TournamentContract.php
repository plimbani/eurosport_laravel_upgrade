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

    /*
     * Create New Tournament
     *
     * @param  array $api_key,$data
     * @return response
     */

    public function create($request);

    /*
     * Edit Tournament
     *
     * @param  array $api_key,$tournament_id,$data
     * @return response
     */
    public function edit($request);

    /*
     * Delete Tournament
     *
     * @param  array $api_key,$tournament_id
     * @return response
     */

    public function delete($request);

    public function generateReport($request);
}
