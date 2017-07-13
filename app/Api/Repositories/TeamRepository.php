<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Team;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Competition;
use Laraspace\Models\Club;
use DB;

class TeamRepository
{
    public function __construct() {
      $this->AwsUrl = getenv('S3_URL');
    }
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

                if(isset($data['filterValue']) && $data['filterValue'] != null && $data['filterValue'] != ''){

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
          return  Club::where('tournament_id',$tournament_id)
                  ->select('clubs.id','clubs.name')
                  ->get();
      }
    public function getTeamData($tournamentData)
    {
        return Team::where('tournament_id',$tournamentData['tournament_id'])
                    ->where('club_id',$tournamentData['clubId'])
                     ->get();

        // print_r($tournamentData); exit();
    }



    public function getTeambyTeamId($teamId){
        return Team::where('esr_reference',$teamId)->first();
    }
    public function getAllTournamentTeams($tournamentId)
    {

    return Team::join('countries', function ($join) {
                    $join->on('teams.country_id', '=', 'countries.id');
                })
                ->leftJoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                ->leftJoin('competitions','competitions.id','=','teams.competation_id')
                ->where('teams.tournament_id', '=',$tournamentId)

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
        // Now here we get the age_group_id
        $temData = Team::where('id',$team_id)->get();
        $ageGroupId = $temData[0]['age_group_id'];
        // Now query in competation table and find the competationId
         $compData = Competition::leftJoin('tournament_competation_template','tournament_competation_template.id','=','competitions.tournament_competation_template_id')
        ->where('competitions.tournament_id','=',$data['tournament_id'])
        ->select('competitions.id as CompId',
          'competitions.name as compName',
          'tournament_competation_template.group_name','tournament_competation_template.category_age')
        ->where('competitions.tournament_competation_template_id','=',$ageGroupId)
        ->get();
        $competId = 0;
        foreach($compData as $ddata) {
           $asGroup = preg_replace('/[0-9]+/', '', $groupName);
           $cc1 = $ddata['group_name'].'-'.$ddata['category_age'].'-'.$asGroup;
           // if its found then break it
           if($ddata['compName'] == $cc1) {
            $competId = $ddata['CompId'];
            break;
           }
        }

        Team::where('id', $team_id)->update([
            'group_name' => $groupName,
            'assigned_group' => preg_replace('/[0-9]+/', '', $groupName),
            'competation_id' => $competId
            ]);
        // Also Add in MatchStaning table
        // First check if record exist if yes then update else create
        $match_standing = DB::table('match_standing')
        ->where('tournament_id','=',$data['tournament_id'])
        ->where('competition_id','=',$competId)
        ->where('team_id','=',$team_id)->get();
        $matchStandData = array();
        if($match_standing->isEmpty())
        {
          // if empty create it
          $matchStandData['competition_id'] = $competId;
          $matchStandData['tournament_id'] = $data['tournament_id'];
          $matchStandData['team_id'] = $team_id;
          $matchStandData['points'] = 0;$matchStandData['played'] = 0;
          $matchStandData['won'] = 0;$matchStandData['draws'] = 0;
          $matchStandData['lost'] = 0;$matchStandData['goal_for'] = 0;
          $matchStandData['goal_against'] = 0;
          DB::table('match_standing')->insert($matchStandData);

        } else {
          // check if competation id is Same then no need to update it
          if($match_standing[0]->competition_id != $competId) {
            // Update it
            $matchStandData['competition_id'] = $competId;
            // Update it
            DB::table('match_standing')->where('id','=',$match_standing[0]->id)
            ->update($matchStandData);
          }
        }


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

      //$url = getenv('S3_URL');

      if($fieldName == 'group_id') {

        return Team::leftJoin('competitions','competitions.id','=','teams.competation_id')
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
              DB::raw('CONCAT("'.$this->AwsUrl.'", countries.logo) AS countryLogo'))
            ->where('competitions.id','=',$value)
            ->where('competitions.tournament_id','=',$tournamentId)->get();
      }

      return Team::where('teams.'.$fieldName,'=',$value)
            ->join('countries','countries.id','=','teams.country_id')
            ->join('tournament_competation_template','tournament_competation_template.id','=','teams.age_group_id')
            ->join('competitions','competitions.id',
              '=','teams.competation_id')
            ->select('teams.*','countries.id as countryId',
              'countries.name as CountryName',
              'tournament_competation_template.id as ageGroupId',
              'tournament_competation_template.group_name as ageGroupName',
              'tournament_competation_template.category_age as CategoryAge',
              'competitions.id as GroupId',
              'competitions.name as GroupName',
              DB::raw('CONCAT("'.$this->AwsUrl.'", countries.logo) AS countryLogo'))
            ->where('teams.tournament_id','=',$tournamentId)->get();
    }
}
