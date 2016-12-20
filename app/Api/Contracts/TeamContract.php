<?php

namespace App\Api\Contracts;

interface TeamContract
{
    /*
     * Get User Data
     *
     * @param  array $data,$affiliateList
     * @return response
     */

    public function getAllTeams();

    public function createTeam($data);
     /*
     * Edit Team
     *
     * @param  array $team_id,$data
     * @return response
     */
    public function edit($request,$team_id);

/*
     * Delete Team
     *
     * @param  array $team_id
     * @return response
     */
    public function deleteTeam($team_id);
}
