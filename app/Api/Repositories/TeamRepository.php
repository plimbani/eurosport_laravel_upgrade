<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Team;

class TeamRepository
{
    public function getAll($tournamentId)
    {
        return  Team::join('countries', function ($join) {
                        $join->on('teams.country_id', '=', 'countries.id');
                    })
                ->join('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                // ->join('competitions','competitions.tournament_competation_template_id','=','teams.age_group_id')
                 ->where('teams.tournament_id',$tournamentId)
                 ->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo',
                    // 'competitions.name as competationName','competitions.id as competationId',
                    'tournament_competation_template.group_name as age_name')
                 ->get();
        
    }
    public function getAllTournamentTeams($tournamentId)
    {
        return  Team::join('countries', function ($join) {
                        $join->on('teams.country_id', '=', 'countries.id');
                    })
                ->join('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                 ->join('competitions','competitions.tournament_competation_template_id','=','teams.age_group_id')
                 ->where('teams.tournament_id',$tournamentId)
                 ->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo',
                     'competitions.name as competationName','competitions.id as competationId',
                    'tournament_competation_template.group_name as age_name')
                 ->get();
        
    }

    public function create($data)
    {
            return Team::create([
            'name' => $data['team_name'],
            'esr_reference' => $data['reference_no'],
            'country_id' => $data['country_id'],
            'tournament_id' => $data->tournamentData['tournamentId'],
            'age_group_id' => $data->tournamentData['ageCategory']
            ]);
    }
    public function assignGroup($team_id,$groupName) 
    {
        return Team::where('id', $team_id)->update([
            'group_name' => $groupName,
            'assigned_group' => preg_replace('/[0-9]+/', '', $groupName)
            
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
        // dd($tournamentId);
        return Team::where('tournament_id',$tournamentId)->delete();
    }
}
