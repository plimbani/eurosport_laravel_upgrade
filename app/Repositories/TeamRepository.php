<?php

namespace Laraspace\Repositories;

use Laraspace\Models\Team;

class TeamRepository
{
    public function getAll()
    {
        return Team::get();
    }

    public function create($data)
    {
        return Team::create($data);
    }

    public function delete($data)
    {
        return Team::find($data['id'])->delete();

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
