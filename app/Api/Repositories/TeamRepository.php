<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Team;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;

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
                 ->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo','countries.country_flag as countryFlag',
                    // 'competitions.name as competationName','competitions.id as competationId',
                    'tournament_competation_template.group_name as age_name','tournament_competation_template.category_age as categoryAge')
                 ->get();

    }
    public function getAllFromFilter($data)
    {

          $teamData =  Team::join('countries', function ($join) {
                        $join->on('teams.country_id', '=', 'countries.id');
                    })
                ->join('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                // ->join('competitions','competitions.tournament_competation_template_id','=','teams.age_group_id')
                ->where('teams.tournament_id',$data['tournamentId']);
                if($data['filterValue'] != null && $data['filterValue'] != ''){

                    if($data['filterKey'] == 'age_category') {
                     $teamData =  $teamData->where('teams.age_group_id',$data['filterValue']['id']);

                    } else if($data['filterKey'] == 'country') {
                        $teamData =   $teamData->where('teams.country_id',$data['filterValue']['id']);

                    }else if($data['filterKey'] == 'team') {
                        $teamData =  $teamData->where('teams.name',$data['filterValue']['name']);

                    }
                }
            return $teamData->distinct('teams.id')->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo','countries.country_flag as country_flag',
                    // 'competitions.name as competationName','competitions.id as competationId',
                    'tournament_competation_template.group_name as age_name','tournament_competation_template.category_age as category_age')
                ->get();


                // ->join('competitions','competitions.tournament_competation_template_id','=','teams.age_group_id')
                // if($data[''])
                 // ->where('teams.age_group_id',$ageGroup)
                 // ->where('teams.tournament_id',$tournamentId)
                 // ->select('teams.*','teams.id as team_id', 'countries.name as name','countries.logo as logo',
                 //    // 'competitions.name as competationName','competitions.id as competationId',
                 //    'tournament_competation_template.group_name as age_name')
                 // ->get();
    }
    public function getClubData($tournament_id)
    {
        return  Team::where('tournament_id',$tournament_id)
                ->join('clubs', 'clubs.id', '=', 'teams.club_id')
                ->groupBy('teams.club_id')
                ->select('clubs.id','clubs.name')
                ->get();
            // print_r($clubData->toArray());
    }



    public function getTeambyTeamId($teamId){
        return Team::where('esr_reference',$teamId)->first();
    }
    public function getAllTournamentTeams($tournamentId)
    {

    return  Team::join('countries', function ($join) {
                    $join->on('teams.country_id', '=', 'countries.id');
                })
                ->join('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                ->join('competitions','competitions.tournament_competation_template_id','=','tournament_competation_template.id')
                ->where('teams.tournament_id',$tournamentId)
                ->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo','countries.country_flag as countryFlag',
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
                 ->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo','countries.country_flag',
                    'competitions.name as competationName','competitions.id as competationId',
                    'tournament_competation_template.group_name as age_name')
                 ->get();

    }

    public function create($data)
    {
        $age_group_id = 0;
        $reference_no =  isset($data['teamid']) ? $data['teamid'] : '';
        $teamName =  isset($data['team']) ? $data['team'] : '';
        $place =  isset($data['place']) ? $data['place'] : '';
        $club_id =  isset($data['club_id']) ? $data['club_id'] : '';

        \Log::info($data);
        return Team::create([
            'name' => $teamName,
            'esr_reference' => $reference_no,
            'place' => $place,
            'country_id' => $data['country_id'],
            'tournament_id' => $data->tournamentData['tournamentId'],
            'age_group_id' => $data['age_group_id'],
            'club_id'=>$data['club_id']
            ]);
    }


    public function assignGroup($team_id,$groupName,$data='')
    {
        $team = Team::find($team_id);
        $gname = explode('-',$groupName);

        Team::where('id', $team_id)->update([
            'group_name' => $groupName,
            'assigned_group' => preg_replace('/[0-9]+/', '', $groupName)
            ]);
        TempFixture::where('home_team_name', $gname[1])
            ->where('tournament_id',$data['tournament_id'])
            // ->where('age_group_id',$data['age_group'])
            ->update([
                'home_team_name' => $team->name,
                'home_team' => $team_id,
                'match_number' => DB::raw("REPLACE(match_number, '".$gname[1]."', '".$team->name."')")
            ]);
        TempFixture::where('away_team_name', $gname[1])
            ->where('tournament_id',$data['tournament_id'])
            // ->where('age_group_id',$data['age_group'])
            ->update([
                'away_team_name' => $team->name,
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
    public function getTeamList($data)
    {
      // First get the tournamentId
      $tournamentId = $data['tournament_id'];
      // unset it
      unset($data['tournament_id']);

      $fieldName = key($data);
      $value = $data[$fieldName];

      $url = getenv('S3_URL');

      if($fieldName == 'group_id') {

        return Team::leftJoin('competitions','competitions.tournament_competation_template_id','=','teams.age_group_id')
            ->join('countries','countries.id','=','teams.country_id')
            ->join('tournament_competation_template','tournament_competation_template.id','=','teams.age_group_id')
            ->select('teams.*',
              'countries.id as countryId',
              'countries.name as CountryName',
              'tournament_competation_template.id as ageGroupId',
              'tournament_competation_template.group_name as ageGroupName',
              'tournament_competation_template.category_age as CategoryAge',
              'competitions.id as GroupId',
              'competitions.name as GroupName',
              DB::raw('CONCAT("'.$url.'", countries.logo) AS countryLogo'))
            ->where('competitions.id','=',$value)
            ->where('competitions.tournament_id','=',$tournamentId)->get();
      }

      return Team::where('teams.'.$fieldName,'=',$value)
            ->join('countries','countries.id','=','teams.country_id')
            ->join('tournament_competation_template','tournament_competation_template.id','=','teams.age_group_id')
            ->join('competitions','competitions.tournament_competation_template_id',
              '=','tournament_competation_template.id')
            ->select('teams.*','countries.id as countryId',
              'countries.name as CountryName',
              'tournament_competation_template.id as ageGroupId',
              'tournament_competation_template.group_name as ageGroupName',
              'tournament_competation_template.category_age as CategoryAge',
              'competitions.id as GroupId',
              'competitions.name as GroupName',
              DB::raw('CONCAT("'.$url.'", countries.logo) AS countryLogo'))
            ->where('teams.tournament_id','=',$tournamentId)->get();
    }
}
