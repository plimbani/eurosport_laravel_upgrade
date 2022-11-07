<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Team;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Competition;
use Laraspace\Models\Club;
use Laraspace\Models\Country;
use Laraspace\Models\TeamManualRanking;
use Laraspace\Models\Position;
use DB;

class TeamRepository
{
    public function __construct() {
      $this->AwsUrl = getenv('S3_URL');
      $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
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

    $teamData =  Team::leftJoin('countries', 'countries.id', '=', 'teams.country_id')
          ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
          ->join('clubs', 'clubs.id', '=', 'teams.club_id')
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
              'tournament_competation_template.group_name as age_name','tournament_competation_template.category_age as category_age','clubs.name as club_name')
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

    return Team::leftJoin('countries', 'countries.id', '=', 'teams.country_id')
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

    public function create($data, $tournamentId)
    {
        $teamColors = config('config-variables.team_colors');
        $teamColors = array_flip($teamColors);

        $age_group_id = 0;

        if (config('config-variables.current_layout') === 'tmp') {
          $reference_no =  isset($data['teamid']) ? $data['teamid'] : ''; 
          $place =  isset($data['place']) ? $data['place'] : '';
          $country_id = $data['country_id'];
          $team_comment = $data['teamcomment'];
          $shirtColor = (isset($data['shirtcolor']) && $data['shirtcolor']) ? $teamColors[$data['shirtcolor']] : NULL;
          $shortsColor = (isset($data['shortscolor']) && $data['shortscolor']) ? $teamColors[$data['shortscolor']] : NULL;
        } else {
          $teams = Team::orderByDesc('id')->limit(1)->first();
          $reference_no = "Team" . $teams->id;
          $place = NULL;
          $country_id = NULL;
          $team_comment = NULL;
          $shirtColor = NULL;
          $shortsColor = NULL;
        }
        
        $teamName = isset($data['team']) ? $data['team'] : '';        
        $club_id = isset($data['club_id']) ? $data['club_id'] : '';
        
        $team = Team::create([
          'name' => $teamName,
          'esr_reference' => $reference_no,
          'place' => $place,
          'country_id' => $country_id,
          'tournament_id' => $tournamentId,
          'age_group_id' => $data['age_group_id'],
          'club_id' => $club_id,
          'comments' => $team_comment,
          'shirt_color' => $shirtColor,
          'shorts_color' => $shortsColor,
        ]);

        if (config('config-variables.current_layout') === 'commercialisation') { $team->update(['esr_reference' => "Team" . $team->id]); }

        return $team;
    }

    public function getAllUpdatedTeam($teamdata)
    {

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

    public function getAllGroupTeam($teamData) {
      $results =  DB::table('teams')
                  ->whereIn('competation_id',function($q) use ($teamData){
                    $q->from('teams')
                      ->whereIn('id',$teamData)
                      ->select('competation_id')
                      ->get();
                  })
                  ->where('assigned_group', 'LIKE', '%Group%')
                  ->select('id')
                  ->get()->toArray();
      return array_column($results, 'id');
    }
    public function updateMatches($allTeams='',$swapTeam='',$matchData='')
    {
      // dd($teams);
      if(count($allTeams)>0){
        $teams = array_diff($allTeams, $swapTeam);
        $matches = [];
        $matches = DB::table('temp_fixtures')
                ->join('competitions', 'temp_fixtures.competition_id', '=', 'competitions.id')
                ->where('temp_fixtures.tournament_id','=',$matchData['tournament_id'])
                ->where('temp_fixtures.age_group_id','=',$matchData['age_group'])
                ->where('temp_fixtures.is_scheduled',1)
                ->where('competitions.competation_round_no','!=', 'Round 1')
                ->whereIn('temp_fixtures.away_team',$teams)
                ->update([
                    "temp_fixtures.away_team_name" => DB::raw("temp_fixtures.away_team_placeholder_name"),
                    'temp_fixtures.away_team' => 0,
                    'temp_fixtures.hometeam_score' => NULL,
                    'temp_fixtures.awayteam_score' => NULL,

                  ]);

        $matches2 = DB::table('temp_fixtures')
                ->join('competitions', 'temp_fixtures.competition_id', '=', 'competitions.id')
                ->where('temp_fixtures.tournament_id','=',$matchData['tournament_id'])
                ->where('temp_fixtures.age_group_id','=',$matchData['age_group'])
                ->where('temp_fixtures.is_scheduled',1)
                ->where('competitions.competation_round_no','!=', 'Round 1')
                ->whereIn('temp_fixtures.home_team',$teams)
                ->update([
                    "temp_fixtures.home_team_name" => DB::raw("temp_fixtures.home_team_placeholder_name"),
                    'temp_fixtures.home_team' => 0,
                    'temp_fixtures.hometeam_score' => NULL,
                    'temp_fixtures.awayteam_score' => NULL,
                ]);
      } if($swapTeam > 0) {
          $matches3 = DB::table('temp_fixtures')
                ->where('temp_fixtures.tournament_id','=',$matchData['tournament_id'])
                ->where('temp_fixtures.age_group_id','=',$matchData['age_group'])
                ->where('temp_fixtures.is_scheduled',1)
                ->whereIn('temp_fixtures.away_team',$swapTeam)
                ->update([
                    "temp_fixtures.away_team_name" => DB::raw("temp_fixtures.away_team_placeholder_name"),
                    'temp_fixtures.away_team' => 0,
                    'temp_fixtures.hometeam_score' => NULL,
                    'temp_fixtures.awayteam_score' => NULL,
                ]);

          $matches4 = DB::table('temp_fixtures')
                ->where('temp_fixtures.tournament_id','=',$matchData['tournament_id'])
                ->where('temp_fixtures.age_group_id','=',$matchData['age_group'])
                ->where('temp_fixtures.is_scheduled',1)
                ->whereIn('temp_fixtures.home_team',$swapTeam)
                ->update([
                    "temp_fixtures.home_team_name" => DB::raw("temp_fixtures.home_team_placeholder_name"),
                    'temp_fixtures.home_team' => 0,
                    'temp_fixtures.hometeam_score' => NULL,
                    'temp_fixtures.awayteam_score' => NULL,
                  ]);
       }
    }

    public function assignGroup($team_id,$groupName,$data='',$tempFixturesCount, $ageGroupId)
    {
      $team = Team::find($team_id);

      $checkForRoundRobin = strpos($groupName, 'Group');
      if($groupName!='') {
        if ($checkForRoundRobin === false) {
          $gname = explode('-',$groupName)[2];
        } else {
          $gname = explode('-',$groupName)[1];
        }
      }
      
      $competId = NULL;
      
      if($groupName == ''){
        Team::where('id', $team_id)->update([
          'group_name' => null,
          'assigned_group' => null,
          'competation_id' => null
        ]);
        
        if($tempFixturesCount == 0) {
          TempFixture::where('home_team', $team_id)
            ->where('tournament_id',$data['tournament_id'])
            ->where('age_group_id',$ageGroupId)
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
        }
      }
      if($groupName != '') {
        $compData = Competition::leftJoin('tournament_competation_template','tournament_competation_template.id','=','competitions.tournament_competation_template_id')
        ->where('competitions.tournament_id','=',$data['tournament_id'])
        ->select('competitions.id as CompId',
          'competitions.name as compName',
          'competitions.actual_name as actualCompName',
          'tournament_competation_template.group_name','tournament_competation_template.category_age')
        ->where('competitions.tournament_competation_template_id','=',$ageGroupId)
        ->get();
        
        foreach($compData as $ddata) {
          if ($checkForRoundRobin === false) {
            $splitGroupName = explode('-', $groupName);
            $asGroup = $splitGroupName[0] . '-' . $splitGroupName[1];
          } else {
            $asGroup = preg_replace('/[0-9]+/', '', $groupName);
          }
          $cc1 = $ddata['group_name'].'-'.$ddata['category_age'].'-'.$asGroup;

          $compName = $checkForRoundRobin === false ? $ddata['actualCompName'] : $ddata['compName'];
          
          if($compName == $cc1) {
            $competId = $ddata['CompId'];
            break;
          }
        }

        $assignGroup = NULL;
        if($groupName!= NULL){
          $assignGroup = preg_replace('/[0-9]+/', '', $groupName);
        }
        if ($checkForRoundRobin === false) {
          $splitGroupName = explode('-', $groupName);
          $assignGroup = $splitGroupName[0] . '-' . $splitGroupName[1];
        }

        Team::where('id', $team_id)->update([
          'group_name' => $groupName,
          'assigned_group' => $assignGroup,
          'competation_id' => $competId
        ]);

        TempFixture::where('home_team_placeholder_name', $gname)
              ->where('tournament_id',$data['tournament_id'])
              ->where('competition_id',$competId)
               ->update([
                  'home_team_name' => $team->name,
                  'home_team' => $team_id
              ]);
        TempFixture::where('away_team_placeholder_name', $gname)
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
        $teamColors = config('config-variables.team_colors');
        $teamColors = array_flip($teamColors);

        if(isset($data['shirt_color']) && $data['shirt_color']) {
          $data['shirt_color'] = $teamColors[$data['shirt_color']];
        }
        if(isset($data['shorts_color']) && $data['shorts_color']) {
          $data['shorts_color'] = $teamColors[$data['shorts_color']];
        }

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
      $tournamentId = $data['tournament_id'];
      unset($data['tournament_id']);

      $fieldName = key($data);
      $value = $data[$fieldName];

      //$url = getenv('S3_URL');

      if($fieldName == 'group_id') {
        $teams = Team::leftJoin('competitions','competitions.id','=','teams.competation_id')
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
            ->where('competitions.tournament_id','=',$tournamentId);

        if(app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true") {
          $teams = $teams->where(function($q) use($tournamentId) {
            return $q->whereHas('homeFixtures', function($q1) use($tournamentId) {
              $q1->where('is_scheduled', 1)->where('tournament_id', $tournamentId);
            })->orWhereHas('awayFixtures', function($q2) use($tournamentId) {
              $q2->where('is_scheduled', 1)->where('tournament_id', $tournamentId);
            });
          });
        }

        $teams = $teams->get();
        return $teams;
      }

      $teams = Team::where('teams.'.$fieldName,'=',$value)
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
            ->where('teams.tournament_id','=',$tournamentId);

      if(app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true") {
        $teams = $teams->where(function($q) use($tournamentId) {
          return $q->whereHas('homeFixtures', function($q1) use($tournamentId) {
            $q1->where('is_scheduled', 1)->where('tournament_id', $tournamentId);
          })->orWhereHas('awayFixtures', function($q2) use($tournamentId) {
            $q2->where('is_scheduled', 1)->where('tournament_id', $tournamentId);
          });
        });
      }

      $teams = $teams->get();
      return $teams;
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

    public function saveTeamManualRankingFromStandings($tournamentId, $ageGroupId, $teamsList)
    {
      $competationIdArray = array();

      $competationIdArray = DB::table('temp_fixtures')
                  ->where(function($q) use ($teamsList){
                    $q->whereIn('temp_fixtures.away_team', $teamsList)
                      ->orWhereIn('temp_fixtures.home_team', $teamsList);
                  })
                  ->where('age_group_id', $ageGroupId)
                  ->pluck('competition_id')
                  ->toArray();

      $competationIdArray = array_values(array_unique($competationIdArray));

      if(count($competationIdArray) > 0) {
        $matchStandings = DB::table('match_standing')
          ->where('tournament_id','=',$tournamentId)
          ->whereIn('competition_id',$competationIdArray)->delete();
      }
    }

    public function editTeamDetails($teamId)
    { 
      return Team::with('club')->where('id', $teamId)->first();
    }

    public function getAllCountries()
    {
      return $contries = Country::orderBy('name')->get();
    }

    public function getAllTeamColors()
    {
      return config('config-variables.team_colors');
    }

    public function getAllClubs()
    {
      return $clubs = Club::all();
    }

    public function getClubsByTournamentId($tournamentId)
    {
      $tournamentClubs = Team::where('tournament_id', $tournamentId)->pluck('club_id');
      $uniqueClubs = $tournamentClubs->unique();

      return Club::whereIn('id', $uniqueClubs)->get();
    }
    
    public function checkTeamExist($request)
    {
      $teamData = $request->all()['teamData'];
      $team = Team::where('esr_reference',$teamData['esrReference'])->where('age_group_id',$teamData['age_group_id'])->where('id','!=',$teamData['teamId'])->count();
   
      return $team;
    }

    public function updateTeamDetails($request, $teamId)
    {  
      $res =   $request->all();
     
      $team = Team::findOrFail($teamId);
      $clubId = 0;

      $teamHome = TempFixture::where('home_team',$teamId)->where('age_group_id',$res['age_group_id'])
      ->update(['home_team_name' => $res['team_name']]);

      $teamAway = TempFixture::where('away_team',$teamId)->where('age_group_id',$res['age_group_id'])
      ->update(['away_team_name' => $res['team_name']]);
    

      $club = Club::where('name',$res['club_name'])->first();
      if(!$club) {
        $club = new Club();
        $club->user_id = 1;
        $club->name = $res['club_name'];  
        $club->save();

        $clubId = $club->id;
      } else {
        $clubId = $club->id;
      } 
     
      $team->esr_reference = $request['team_id'];
      $team->name = $request['team_name'];
      $team->place = $request['team_place'];
      $team->country_id = $request['team_country'];
      $team->club_id = $clubId;
      $team->comments = $request['comment'];  
      $team->shirt_color = $request['team_shirt_color'];
      $team->shorts_color = $request['team_shorts_color'];
      $team->save();    
    }

    public function resetAllTeams($data)
    {
      $ageCategoryId = $data['ageCategoryId'];
      $tournamentId = $data['tournamentId'];
      $ageCategories = [$ageCategoryId];

      $fixturesWithResultsEnteredForAgeCategory = TempFixture::where('age_group_id', $ageCategoryId)
        ->where(function ($query) {
            $query->whereNotNull('hometeam_score')
              ->orWhereNotNull('awayteam_score');
        })->get();

      if(count($fixturesWithResultsEnteredForAgeCategory) > 0) {
        return ['status' => 'error', 'message' => 'Teams for selected age category can not be deleted as one or more results are entered.'];
      }

      $tempfixtures = TempFixture::where('age_group_id',$ageCategoryId)->update(['home_team' => 0,
        'away_team' => 0, 'is_result_override' => 0, 'match_winner' => null, 'match_status' => null,
        'hometeam_score' => null, 'awayteam_score' => null, 'home_team_name' => null, 'away_team_name' => null]);
      $teamDataReset = Team::where('age_group_id',$ageCategoryId)->delete();  
      $PositionDataReset = Position::where('age_category_id',$ageCategoryId)->update(['team_id' => null]); 
      
      $competationIds = Competition::where('tournament_competation_template_id',$ageCategoryId)
                                    ->pluck('id')->toArray();
     
      $MatchStanding = DB::table('match_standing')->whereIn('competition_id',$competationIds)
                                                  ->delete();
      $competitions = Competition::where('tournament_competation_template_id',$ageCategoryId)
                                   ->update(['is_manual_override_standing'=> 0]);
      $matchData = array('tournamentId'=>$tournamentId, 'ageGroupId'=>$ageCategoryId);
      $matchresult =  $this->matchRepoObj->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);
      $this->matchRepoObj->checkMaximumTeamIntervalForMatchesOnCategoryUpdate($matchData);

      return ['status' => 'success', 'message' => 'Teams has been deleted successfully.'];
    }

    public function getTeamsFairPlayData($data)
    {
      $teamData = Team::join('countries', function ($join) {
        $join->on('teams.country_id', '=', 'countries.id');
      })
      ->join('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
      ->join('clubs', 'clubs.id', '=', 'teams.club_id')
      ->join('temp_fixtures', function($join) {
          $join->on('teams.id', '=', 'temp_fixtures.home_team')->orOn('teams.id', '=', 'temp_fixtures.away_team');
        })
      ->groupBy('teams.id')
      ->where('teams.tournament_id',$data['tournament_id']);

      if(isset($data['sel_ageCategory']) && $data['sel_ageCategory'] != null && $data['sel_ageCategory'] != ''){
        $teamData = $teamData->where('teams.age_group_id',$data['sel_ageCategory']);
      }

      if(isset($data['sort_by']) && $data['sort_by'] != '') {
        switch($data['sort_by']) {
            case 'team_id':
                  $fieldName = 'teams.id';
                  break;
            case 'name':
                  $fieldName = 'teams.name';
                  break;
            case 'club_name':
                  $fieldName = 'clubs.name';
                  break;
            case 'country_name':
                  $fieldName = 'countries.name';
                  break;
            case 'age_name':
                  $fieldName = 'tournament_competation_template.group_name';
                  break;
            case 'total_red_cards':
                  $fieldName = 'total_red_cards';
                  break;
            case 'total_yellow_cards':
                  $fieldName = 'total_yellow_cards';
                  break;
        }
        $teamData = $teamData->orderBy($fieldName, $data['sort_order']);
      }

      $teamData = $teamData->select('teams.*','teams.id as team_id', 'countries.name as country_name',
                            'tournament_competation_template.group_name as age_name','tournament_competation_template.category_age as category_age','clubs.name as club_name', 
                            DB::raw('
                              SUM(CASE
                              WHEN (temp_fixtures.home_team = teams.id) THEN temp_fixtures.home_yellow_cards ELSE temp_fixtures.away_yellow_cards
                              END
                              ) AS total_yellow_cards'),
                            DB::raw('
                              SUM(CASE
                              WHEN (temp_fixtures.home_team = teams.id) THEN temp_fixtures.home_red_cards ELSE temp_fixtures.away_red_cards
                              END
                              ) AS total_red_cards')
                            )
                            ->get();

      return ['teamData' => $teamData];
    }

    public function getTournamentTeamDetails($data)
    {
      // First get the tournamentId and team id and return team detail.
      $tournamentId = $data['tournamentData']['tournament_id'];
      $teamId = $data['tournamentData']['team_id'];

      return Team::where('teams.id','=',$teamId)
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

    public function getAgeCategoryTeams($data)
    {
      $ageCategory = TournamentCompetationTemplates::find($data['ageCategoryId']);
      $ageCategoryTeams = Team::where('age_group_id', $data['ageCategoryId'])->get();
      return ['ageCategory' => $ageCategory, 'ageCategoryTeams' => $ageCategoryTeams];
    }
}
