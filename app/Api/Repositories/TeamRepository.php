<?php

namespace App\Api\Repositories;

use App\Models\Team;
use DB;

class TeamRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('teams');
    }

    public function getAllTeams()
    {
        return Team::all();
    }

    public function createTeam($teamData)
    {
        return $this->dbObj->insert($teamData);
    }
}
