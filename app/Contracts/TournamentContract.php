<?php

namespace Laraspace\Contracts;

interface TournamentContract
{
    /*
     * Get All Tournaments
     *
     * @param  array $state,$type
     * @return response
     */
    public function index();

    /*
     * Create New Tournament
     *
     * @param  array $data
     * @return response
     */

    public function create($request);

    /*
     * Edit Tournament
     *
     * @param  array $tournamentId,$data
     * @return response
     */
    public function edit($request, $tournamentId);

    /*
     * Delete Tournament
     *
     * @param  array $tournamentId
     * @return response
     */

    public function delete($tournamentId);
}
