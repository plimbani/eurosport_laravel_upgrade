<?php

namespace App\Api\Repositories;

use App\Models\Team;
use DB;

class TeamRepository
{
    public function getAllTeams()
    {
        /*  Use Join for Fetch Club Income Data with Club Table */
        return Team::all();
    }

    public function createTeam($teamData)
    {
        print_r($teamData->all());
    }
}
