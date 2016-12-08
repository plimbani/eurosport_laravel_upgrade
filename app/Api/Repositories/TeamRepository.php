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
        echo "<pre>---";print_r($teamData->all());exit();
    }
}
