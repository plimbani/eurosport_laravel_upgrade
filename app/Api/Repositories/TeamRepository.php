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
        return Team::create($teamData);
    }

    public function edit($data, $teamId)
    {
        return Team::where('id', $teamId)->update($data);
    }

    public function getTeamFromId($teamId)
    {
        return Team::find($teamId);
    }
}
