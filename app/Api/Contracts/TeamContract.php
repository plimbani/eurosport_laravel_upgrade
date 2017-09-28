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
    public function getTeams($teamData);

    public function create($request);

    public function edit($request);

    public function deleteFromTournament($tournamentId,$ageGroup);

    public function assignTeams($request);
    public function getAllTeamsGroup($request);
    
    public function delete($request);
    public function getAllFromCompetitionId($request);
    public function changeTeamName($request);
    
    
}
