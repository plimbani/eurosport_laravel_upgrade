<?php

namespace App\Api\Contracts;

interface TeamContract
{
   public function createTeam($data);

     /*
     * Edit Team
     *
     * @param  array $teamId,$data
     * @return response
     */
    public function edit($request, $teamId);

/*
     * Delete Team
     *
     * @param  array $teamId
     * @return response
     */
    public function deleteTeam($teamId);
}
