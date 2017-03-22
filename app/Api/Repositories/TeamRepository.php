<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Team;

class TeamRepository
{
    public function getAll($tournamentId)
    {
        return Team::join('countries', function ($join) {
                        $join->on('teams.country_id', '=', 'countries.id');
                    })
                 ->where('tournament_id',$tournamentId)
                 ->select('teams.*','teams.id as team_id', 'countries.name as country_name')
                 ->get();
    }

    public function create($data)
    {
        return Team::create([
            'name' => $data['team_name'],
            'esr_reference' => $data['reference_no'],
            'country_id' => $data['country_id'],
            'tournament_id' => $data->tournamentData['tournamentId']
            ]);
    }

    public function edit($data)
    {
        return Team::where('id', $data['id'])->update($data);
    }

    public function delete($data)
    {
        return Team::find($data['id'])->delete();
    }
    public function deleteFromTournament($tournamentId)
    {
        return Team::where('tournament_id',$tournamentId)->delete();
    }
}
