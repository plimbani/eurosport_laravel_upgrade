<?php

namespace Laraspace\Api\Contracts;

interface TeamContract
{
    /*
     * Get All Teams
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTeams($tournamentId);

    public function create($request);

    public function edit($request);

    public function deleteFromTournament($tournamentId);

    public function delete($request);
}
