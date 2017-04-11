<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Team;
use Laraspace\Models\TempFixture;
use DB;

class TeamRepository
{
    public function getAll($tournamentId,$ageGroup='')
    {
        return  Team::join('countries', function ($join) {
                        $join->on('teams.country_id', '=', 'countries.id');
                    })
                ->join('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                // ->join('competitions','competitions.tournament_competation_template_id','=','teams.age_group_id')
                 ->where('teams.age_group_id',$ageGroup)
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
                 ->join('competitions','competitions.tournament_competation_template_id','=','tournament_competation_template.id')
                 ->where('teams.tournament_id',$tournamentId)
                 ->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo',
                     'competitions.name as competationName','competitions.id as competationId',
                    'tournament_competation_template.group_name as age_name')
                 ->get();
        
    }

public function getAllFromTournamentId($tournamentId)
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
    public function assignGroup($team_id,$groupName,$data='') 
    {   
        // dd($data,'hi');
        $team = Team::find($team_id);
        $gname = explode('-',$groupName);
         Team::where('id', $team_id)->update([
            'group_name' => $groupName,
            'assigned_group' => preg_replace('/[0-9]+/', '', $groupName)
            ]);
        TempFixture::where('home_team', $gname[1])
            ->where('tournament_id',$data['tournament_id'])
            // ->where('age_group_id',$data['age_group'])
            ->update([
                'home_team' => $team_id,
                'match_number' => DB::raw("REPLACE(match_number, '".$gname[1]."', '".$team->name."')")
            ]);
        TempFixture::where('away_team', $gname[1])
            ->where('tournament_id',$data['tournament_id'])
            // ->where('age_group_id',$data['age_group'])
            ->update([
            'away_team' => $team_id,
            'match_number' => DB::raw("REPLACE(match_number, '".$gname[1]."', '".$team->name."')")
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
    public function deleteFromTournament($tournamentId,$ageGroup)
    {
        // dd($tournamentId);
        return Team::where('tournament_id',$tournamentId)
                    ->where('age_group_id',$ageGroup)->delete();
    }
}
