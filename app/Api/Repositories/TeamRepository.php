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

          if(isset($data['ageCategoryId']) && $data['ageCategoryId'] != null && $data['ageCategoryId'] != '')
          {
            $teamData =  $teamData->where('teams.age_group_id',$data['ageCategoryId']);
          }

          // if(isset($data['filterValue']) && $data['filterValue'] != null && $data['filterValue'] != ''){

          //     if($data['filterKey'] == 'age_category') {
          //      $teamData =  $teamData->where('teams.age_group_id',$data['filterValue']['id']);

          //     } else if($data['filterKey'] == 'country') {
          //         $teamData =   $teamData->where('teams.country_id',$data['filterValue']['id']);

          //     }else if($data['filterKey'] == 'team') {
          //         $teamData =  $teamData->where('teams.name',$data['filterValue']['name']);

          //     }
          // }
          if(isset($data['team_id']) && $data['team_id'] != '') {
             $teamData = $teamData->whereIn('teams.id',explode(",",$data['team_id']));
          }
          return $teamData->distinct('teams.id')->select('teams.*','teams.id as team_id', 'countries.name as country_name','countries.logo as logo','countries.country_flag as country_flag',
              // 'competitions.name as competationName','competitions.id as competationId',
              'tournament_competation_template.group_name as age_name','tournament_competation_template.category_age as category_age')
          ->get();
    }

      public function getClubData($tournamentData)
      {
        return Club::whereHas('tournament', function($q) use ($tournamentData) {
              if(isset($tournamentData['club_id']) && $tournamentData['club_id'] != '') {
                  $q->whereIn('club_id',explode(",",$tournamentData['club_id']));
                }
              $q->where('tournament_id',$tournamentData['tournament_id']);
         })->select('clubs.id','clubs.name')->get();
      }
    public function getTeamData($tournamentData)
    {

        $result =   Team::where('tournament_id',$tournamentData['tournament_id']);
          if(isset($tournamentData['clubId']) && $tournamentData['clubId'] !='') {
               $result ->where('club_id',$tournamentData['clubId']);
          }
        return $result->get();
    }

    public function getTeambyTeamId($teamId,$tournamentId){
        return Team::where('esr_reference',$teamId)
               ->where('tournament_id','=',$tournamentId)
               ->first();
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
                 'competitions.competation_type',
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

    public function getAllFromCompetitionId($competationId)
    {
      return Team::where('competation_id',$competationId)->orderBy('name', 'asc')->get();
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

    public function getAllUpdatedTeam($teamdata)
    {
      // dd( $teamdata['data']);
      $tournamentId = $teamdata['data']['tournament_id'];
      $ageGroupId  = $teamdata['data']['age_group'];
      
       $existGroups = Array();
        foreach ($teamdata['data']['teamdata'] as $key => $data) {
            $teamname = explode('_', $data['name']);
            $existGroups[$teamname[1]] = $data['value'];
        }

        $results = Team::where('tournament_id',$tournamentId)
                  ->where('age_group_id','=',$ageGroupId)->get();
     
        $updatedGroups = Array();
          foreach ($results as $key => $result) {
                     $updatedGroups[$result->id] = $result->group_name;
          }     
        // echo "<pre>"; print_r($arr); echo "</pre>";exit();
          $differentResult = array_diff_assoc($existGroups,$updatedGroups);
          $keyResults = array_keys($differentResult); 
          return $keyResults;   
    }  
    public function updateMatches($teams='',$matchData='')
    {
      // dd($teams);
      if(count($teams)>0){
        // dd('as');
        $matches = [];
        $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$matchData['tournament_id'])
                ->where('age_group_id','=',$matchData['age_group'])
                 ->where('is_scheduled',1)
                ->where(function ($query) use ($matchData,$teams)  {
                  // if($matchData['teamId']){
                    $query ->whereIn('away_team',$teams)
                         ->orWhereIn('home_team',$teams);
                 
                })  
                ->update([
                    "temp_fixtures.home_team_name" => DB::raw("temp_fixtures.home_team_placeholder_name"),
                    "temp_fixtures.away_team_name" => DB::raw("temp_fixtures.away_team_placeholder_name"),
                    'home_team' => 0,
                    'away_team' => 0,
                    'hometeam_score' => NULL,
                    'awayteam_score' => NULL,

                  ]);
                // dd($matches);
      }
    }
    public function assignGroup($team_id,$groupName,$data='')
    {
      $team = Team::find($team_id);
      $gname = explode('-',$groupName);
      $temData = Team::where('id',$team_id)->get();
      $ageGroupId = $temData[0]['age_group_id'];

      // for group assignment validation
      // $tempFixtures = TempFixture::where('tournament_id', $data['tournament_id'])
      //                             ->where('age_group_id', $ageGroupId)
      //                             ->where(function($query){
      //                               $query->orWhereNotNull('hometeam_score')
      //                                     ->orWhereNotNull('awayteam_score');
      //                             })->get()->count();


      $compData = Competition::leftJoin('tournament_competation_template','tournament_competation_template.id','=','competitions.tournament_competation_template_id')
        ->where('competitions.tournament_id','=',$data['tournament_id'])
        ->select('competitions.id as CompId',
          'competitions.name as compName',
          'tournament_competation_template.group_name','tournament_competation_template.category_age')
        ->where('competitions.tournament_competation_template_id','=',$ageGroupId)
        ->get();

      $competId = NULL;
      foreach($compData as $ddata) {
        $asGroup = preg_replace('/[0-9]+/', '', $groupName);
        $cc1 = $ddata['group_name'].'-'.$ddata['category_age'].'-'.$asGroup;
        if($ddata['compName'] == $cc1) {
        $competId = $ddata['CompId'];
          break;
        }
      }
      
      $assignGroup = NULL;
      if($groupName!= NULL){
        $assignGroup = preg_replace('/[0-9]+/', '', $groupName);
      }
      
      if($groupName == ''){
        Team::where('id', $team_id)->update([
          'group_name' => null,
          'assigned_group' => null,
          'competation_id' => null
        ]);
        
        TempFixture::where('home_team', $team_id)
          ->where('tournament_id',$data['tournament_id'])
          ->where('age_group_id',$ageGroupId) //1409
          ->update([
            'home_team_name' => '@^^@',
            'home_team' => 0
        ]);

        TempFixture::where('away_team', $team_id)
          ->where('tournament_id',$data['tournament_id'])
          ->where('age_group_id',$ageGroupId)
          ->update([
            'away_team_name' => '@^^@',
            'away_team' => 0
        ]);
      } else {

      Team::where('id', $team_id)->update([
        'group_name' => $groupName,
        'assigned_group' => $assignGroup,
        'competation_id' => $competId
      ]);

      TempFixture::where('home_team_placeholder_name', $gname[1])
        ->where('tournament_id',$data['tournament_id'])
        ->where('competition_id',$competId)
        ->update([
            'home_team_name' => $team->name,
            'home_team' => $team_id
        ]);

      TempFixture::where('away_team_placeholder_name', $gname[1])
        ->where('tournament_id',$data['tournament_id'])
        ->where('competition_id',$competId)
        ->update([
            'away_team_name' => $team->name,
            'away_team' => $team_id
        ]);
      }
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

    public function changeTeamName($data)
    {
      $teamId = $data['team_id'];
      $teamName = $data['team_name'];
      Team::where('id', $teamId)->update(['name' => $teamName]);
      TempFixture::where('home_team', $teamId)->update(['home_team_name' => $teamName]);
      TempFixture::where('away_team', $teamId)->update(['away_team_name' => $teamName]);
    }

    public function getAllCompetitionTeamsFromFixture($competationId)
    {
      $competitionFixtures = TempFixture::where('competition_id',$competationId);
      $homeTeams = $competitionFixtures->pluck('home_team')->toArray();
      $awayTeams = $competitionFixtures->pluck('away_team')->toArray();
      $competitionTeams = array_values(array_unique(array_merge($homeTeams, $awayTeams)));
      $teams = [];
      $teamSize = Competition::find($competationId)->team_size;
      if(count($competitionTeams) > 0) {
        $teams = Team::whereIn('id', $competitionTeams)->orderBy('name', 'asc')->get();
      }
      return ['teams' => $teams, 'teamSize' => $teamSize];
    }
}
