<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\MatchResult;
use Laraspace\Models\Competition;
use Laraspace\Models\Fixture;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Pitch;
use Laraspace\Models\PitchUnavailable;
use Laraspace\Models\Team;
use Laraspace\Models\Referee;
use DB;
use Carbon\Carbon;

class MatchRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('match_results');
        $this->getAWSUrl = getenv('S3_URL');
    }

    public function getAllMatches()
    {
        return MatchResult::all();
    }

    public function createMatch($matchData)
    {
        return MatchResult::create($matchData);
    }

    public function edit($data)
    {
        return MatchResult::where('id', $data['id'])->update($data);
    }

    public function getMatchFromId($matchId)
    {
        return MatchResult::find($matchId);
    }

    public function getDraws($tournamentData) {
         //$data = Competition::where('tournament_id',$tournamentId)->get();
      $tournamentId = $tournamentData['tournamentId'];
      //$tournamentId = $tournamentData['tournament_id'];
      $reportQuery = DB::table('competitions')
                     ->leftjoin('tournament_competation_template','tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id');
      if(isset($tournamentData['competationFormatId']) && $tournamentData['competationFormatId'] != '') {
        $reportQuery->where('competitions.tournament_competation_template_id','=',
          $tournamentData['competationFormatId']);
      }
      $reportQuery->where('competitions.tournament_id', $tournamentId);
     $reportQuery->select('competitions.*','tournament_competation_template.group_name');

      return $reportQuery->get();
    }
    public function getFixtures($tournamentData) {

        // dd($tournamentData);
         $reportQuery = DB::table('temp_fixtures')
            // ->Join('tournament', 'fixture.tournament_id', '=', 'tournament.id')
            ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
            ->leftjoin('teams as home_team', function ($join) {
                $join->on('home_team.id', '=', 'temp_fixtures.home_team');
            })
            ->leftjoin('teams as away_team', function ($join) {
                $join->on('away_team.id', '=', 'temp_fixtures.away_team');
            })
            ->leftjoin('countries as HomeFlag', 'home_team.country_id', '=',
                'HomeFlag.id')
            ->leftjoin('countries as AwayFlag', 'away_team.country_id', '=',
                'AwayFlag.id')
            ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
            ->leftjoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
            ->leftjoin('tournament_competation_template',
                'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
            ->leftjoin('match_results', 'temp_fixtures.match_result_id', '=', 'match_results.id')
            ->leftjoin('referee', 'referee.id', '=', 'match_results.referee_id')
            ->groupBy('temp_fixtures.id')
            ->select('temp_fixtures.id as fid','temp_fixtures.match_number as match_number' ,'temp_fixtures.round' ,'competitions.name as competation_name' , 'competitions.team_size as team_size','temp_fixtures.match_datetime',
                'venues.id as venueId', 'competitions.id as competitionId',
                'tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name','referee.last_name as referee_last_name',
                'home_team.name as HomeTeam','away_team.name as AwayTeam',
                'temp_fixtures.home_team as Home_id','temp_fixtures.away_team as Away_id','HomeFlag.logo as HomeFlagLogo','AwayFlag.logo as AwayFlagLogo',
                'HomeFlag.country_flag as HomeCountryFlag',
                'AwayFlag.country_flag as AwayCountryFlag',
                'temp_fixtures.hometeam_score as homeScore',
                'temp_fixtures.awayteam_score as AwayScore',
                'temp_fixtures.pitch_id as pitchId',
                'home_team.name as HomeTeam','away_team.name as AwayTeam',
                DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game')
                )
            ->where('temp_fixtures.tournament_id', $tournamentData['tournamentId']);

          if(isset($tournamentData['tournamentDate']) && $tournamentData['tournamentDate'] !== '' )
          {
            //echo 'Hello Date is'.$tournamentData['tournamentDate'];
            $dd1= date('Y-m-d',strtotime($tournamentData['tournamentDate']));
            //echo $dd1;
            $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime',
              '=',$dd1);
          }

          if(isset($tournamentData['pitchId']) && $tournamentData['pitchId'] !== '' )
          {
            $reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$tournamentData['pitchId']);

          }

          if(isset($tournamentData['teamId']) && $tournamentData['teamId'] !== '')
          {

            $reportQuery = $reportQuery->where('temp_fixtures.home_team',$tournamentData['teamId'])
                ->orWhere('temp_fixtures.away_team',$tournamentData['teamId']);
          }
          if(isset($tournamentData['competitionId']) && $tournamentData['competitionId'] !== '')
          {

            $reportQuery = $reportQuery->where('temp_fixtures.competition_id',
                $tournamentData['competitionId']);
          }

        return $reportQuery->get();
    }

    public function checkTeamIntervalforMatches($matchData) {
      // dd($matchData);
      if(count($matchData['teams'])>0){
        $matches = [];
        $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$matchData['tournamentId'])
                ->where('age_group_id','=',$matchData['ageGroupId'])
                 ->where('is_scheduled',1)
                ->where(function ($query) use ($matchData)  {
                  if($matchData['teamId']){

                    $query ->whereIn('away_team',$matchData['teams'])
                         ->orWhereIn('home_team',$matchData['teams']);
                  }else{
                    $query ->whereIn('away_team_placeholder_name',$matchData['teams'])
                         ->orWhereIn('home_team_placeholder_name',$matchData['teams']);
                  }
                })
                ->get()->toArray();
                // dd($matches);

          return  $this->findMatchInterval($matches);

      }

    }

    public function checkTeamIntervalForMatchesOnCategoryUpdate($matchData) {
      $matches = [];
      $matches = DB::table('temp_fixtures')
              ->where('tournament_id','=',$matchData['tournamentId'])
              ->where('age_group_id','=',$matchData['ageGroupId'])
               ->where('is_scheduled',1)
              ->get()->toArray();

        return  $this->findMatchInterval($matches);
    }

    public function findMatchInterval($matches='') {
      if(count($matches) > 0){
        $setFlag=array();
        $unsetFlag=array();
        foreach ($matches as $key => $match) {
          if($this->setFlagFixture($match)){
            $setFlag[] = $match->id;
          }else{
            $unsetFlag[] = $match->id;
          }
        }
        // echo "<pre>"; print_r($unsetFlag); echo "</pre>";
          // dd($setFlag,$unsetFlag);
        TempFixture::whereIn('id',$setFlag)->update(['minimum_team_interval_flag' => 1]);
        TempFixture::whereIn('id',$unsetFlag)->update(['minimum_team_interval_flag' => 0]);
         return true;
      }
    }

    public function setFlagFixture($data='') {
     // $data = array($data);
     // dd($data);
      $teamData = TempFixture::join('tournament_competation_template','temp_fixtures.age_group_id','tournament_competation_template.id')->where('temp_fixtures.id',$data->id)->select('tournament_competation_template.team_interval','temp_fixtures.*')->first()->toArray();
      $team_interval =  $teamData['team_interval'];

      if($team_interval == 0) {
        return false;
      }

      $startTime =  Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes($team_interval);
      $endTime =  Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes(0);

      if($teamData['home_team']!=0 && $teamData['away_team']!=0) {
        $teamId = true;
        $teams = array($teamData['home_team'],$teamData['away_team'] );
      }else {
        $teamId = false;
        $teams = array($teamData['home_team_placeholder_name'],$teamData['away_team_placeholder_name'] );
      }

      $matchResultCount = TempFixture::where('tournament_id',$teamData['tournament_id'])
        ->where('id','!=',$data->id)
        ->where('is_scheduled',1)
        ->where('age_group_id',$teamData['age_group_id'])
        ->where(function($query1) use ($teams,$teamId) {
          if($teamId){
            $query1->whereIn('home_team',$teams)
            ->orWhereIn('away_team',$teams) ;
          } else {
            $query1->whereIn('home_team_placeholder_name',$teams)
            ->orWhereIn('away_team_placeholder_name',$teams) ;
          }

        })

        ->where(function($query) use ($team_interval,$startTime,$endTime,$data) {
            $edStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_endtime)->addMinutes(0);
            $edEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_endtime)->addMinutes($team_interval);
            $sdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes($team_interval);
            $sdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes(0);
            $query->where(function($query2) use ($sdStartTime,$sdEndTime) {
              $query2->where('match_endtime','>',$sdStartTime)->where('match_endtime','<=',$sdEndTime);
            });
            $query->orWhere(function($query3) use ($edStartTime,$edEndTime) {
               $query3->where('match_datetime','>=',$edStartTime)->where('match_datetime','<',$edEndTime);
            });
            $query->orWhere(function($query4) use ($data) {
              $query4->where('match_datetime','>',$data->match_datetime)->where('match_datetime','<',$data->match_endtime);
            });
            $query->orWhere(function($query5) use ($data) {
              $query5->where('match_datetime','>=',$data->match_datetime)->where('match_datetime','<',$data->match_endtime);
            });
            $query->orWhere(function($query6) use ($data) {
              $query6->where('match_endtime','>',$data->match_datetime)->where('match_endtime','<=',$data->match_endtime);
            });
         })
        ->get();
      // dd($matchResultCount->count());
      // dd($matchResultCount->count());
      if($matchResultCount->count() >0){
        return true ;
      } else {
        return false;
      }
    }

    public function getTempFixtures($tournamentData) {
      $reportQuery = DB::table('temp_fixtures')
          // ->Join('tournament', 'fixture.tournament_id', '=', 'tournament.id')
          ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
          ->leftjoin('teams as home_team', function ($join) {
              $join->on('home_team.id', '=', 'temp_fixtures.home_team');
          })
          ->leftjoin('teams as away_team', function ($join) {
              $join->on('away_team.id', '=', 'temp_fixtures.away_team');
          })
          ->leftjoin('teams as match_winner', function ($join) {
            $join->on('match_winner.id', '=', 'temp_fixtures.match_winner');
          })
          ->leftjoin('countries as HomeFlag', 'home_team.country_id', '=',
              'HomeFlag.id')
          ->leftjoin('countries as AwayFlag', 'away_team.country_id', '=',
              'AwayFlag.id')
          ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
          ->leftjoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
          ->leftjoin('tournament_competation_template',
              'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')

          ->leftjoin('match_results', 'temp_fixtures.match_result_id', '=', 'match_results.id')
          ->leftjoin('referee', 'referee.id', '=', 'temp_fixtures.referee_id')
          ->groupBy('temp_fixtures.id')
          ->select('temp_fixtures.id as fid','temp_fixtures.match_number as match_number' , 'temp_fixtures.display_match_number as displayMatchNumber', 'competitions.competation_type as round', 'competitions.actual_competition_type as actual_round', 'competitions.name as competation_name','competitions.actual_name as competition_actual_name','competitions.competation_round_no','competitions.color_code as competation_color_code', 'competitions.team_size as team_size','temp_fixtures.match_datetime','temp_fixtures.match_endtime','temp_fixtures.match_status','temp_fixtures.age_group_id','temp_fixtures.match_winner',
            'match_winner.name as MatchWinner',
            'temp_fixtures.display_home_team_placeholder_name',
              'venues.id as venueId', 'competitions.id as competitionId',
              'venues.venue_coordinates as venueCoordinates',
              'pitches.type as pitchType','venues.address1 as venueaddress',
              'venues.state as venueState','venues.county as venueCounty',
              'venues.city as venueCity','venues.country as venueCountry',
              'venues.postcode as venuePostcode',
              'tournament_competation_template.group_name as group_name',
              'tournament_competation_template.category_age_color as category_age_color','tournament_competation_template.category_age_font_color as category_age_font_color','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name','temp_fixtures.referee_id as referee_id','referee.first_name as first_name','referee.last_name as last_name','home_team.name as HomeTeam','away_team.name as AwayTeam',
              'temp_fixtures.home_team as Home_id','temp_fixtures.away_team as Away_id','temp_fixtures.minimum_team_interval_flag as min_interval_flag',
              DB::raw('CONCAT("'.$this->getAWSUrl.'", HomeFlag.logo) AS HomeFlagLogo'),
              DB::raw('CONCAT("'.$this->getAWSUrl.'", AwayFlag.logo) AS AwayFlagLogo'),
              'HomeFlag.country_flag as HomeCountryFlag',
              'AwayFlag.country_flag as AwayCountryFlag',
              'HomeFlag.name as HomeCountryName',
              'AwayFlag.name as AwayCountryName',
              'temp_fixtures.hometeam_score as homeScore',
              'temp_fixtures.awayteam_score as AwayScore',
              'temp_fixtures.pitch_id as pitchId',
              'temp_fixtures.is_scheduled',
              'temp_fixtures.is_final_round_match',
              'home_team.name as HomeTeam','away_team.name as AwayTeam',
              'tournament_competation_template.halves_RR',
              'temp_fixtures.home_team_name as homeTeamName',
              'temp_fixtures.away_team_name as awayTeamName',
              'temp_fixtures.home_team_placeholder_name as homePlaceholder',
              'display_home_team_placeholder_name as displayHomeTeamPlaceholderName',
              'temp_fixtures.away_team_placeholder_name as awayPlaceholder',
              'display_away_team_placeholder_name as displayAwayTeamPlaceholderName',
              'temp_fixtures.position as position',
              'tournament_competation_template.game_duration_RR',
              'tournament_competation_template.halves_FM',
              'tournament_competation_template.game_duration_FM',
              'tournament_competation_template.halftime_break_RR',
              'tournament_competation_template.halftime_break_FM',
              'tournament_competation_template.match_interval_RR',
              'tournament_competation_template.match_interval_FM',
              'tournament_competation_template.id as tid',
              DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game')
              )
          ->where('temp_fixtures.tournament_id', $tournamentData['tournamentId']);

        if(isset($tournamentData['tournamentDate']) && $tournamentData['tournamentDate'] !== '' && $tournamentData['tournamentDate'] !== 'all')
        {

          $dd1 = \DateTime::createFromFormat('d/m/Y H:i:s', $tournamentData['tournamentDate'].' 00:00:00');
          $mysql_date_string = $dd1->format('Y-m-d H:i:s');

          //echo $dd1;
           $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime', '=',$mysql_date_string);
        }

        if(isset($tournamentData['pitchId']) && $tournamentData['pitchId'] !== '' )
        {
          $reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$tournamentData['pitchId']);
        }
        if(isset($tournamentData['club_id']) && $tournamentData['club_id'] !== '' )
        {
          $getTeamId = $this->getTeamsForClub($tournamentData['club_id'], $tournamentData['tournamentId']);

          $reportQuery =  $reportQuery->whereIn('temp_fixtures.home_team',$getTeamId)
              ->orWhereIn('temp_fixtures.away_team',$getTeamId);
          //$reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$tournamentData['pitchId']);
        }

        if(isset($tournamentData['teamId']) && $tournamentData['teamId'] !== '')
        {

          $reportQuery = $reportQuery->where('temp_fixtures.home_team',$tournamentData['teamId'])
              ->orWhere('temp_fixtures.away_team',$tournamentData['teamId']);
        }


        if(isset($tournamentData['competitionId']) && $tournamentData['competitionId'] !== '')
        {

          $reportQuery = $reportQuery->where('temp_fixtures.competition_id',
              $tournamentData['competitionId']);
        }
        if(isset($tournamentData['is_scheduled']) && $tournamentData['is_scheduled'] !== '')
        {
          // TODO: add constraint to only Show which are Scheduled
          $reportQuery =  $reportQuery->where('temp_fixtures.is_scheduled','=',$tournamentData['is_scheduled']);
        }
        if(isset($tournamentData['fixture_date']))
        {
          $reportQuery =  $reportQuery->whereDate('temp_fixtures.match_datetime','=',$tournamentData['fixture_date']);
        }

        // Todo Added Condition For Filtering Purpose on Pitch Planner
        if(isset($tournamentData['fiterEnable'])){
          if(isset($tournamentData['filterKey']) && $tournamentData['filterKey'] !='') {
            switch($tournamentData['filterKey']) {
              case 'location':
                $reportQuery =  $reportQuery->where('temp_fixtures.venue_id','=',$tournamentData['filterValue']);
                break;
              case 'age_category':
                $reportQuery =  $reportQuery->where('tournament_competation_template.id','=',$tournamentData['filterValue']);
                if(isset($tournamentData['filterDependentKey']) && $tournamentData['filterDependentKey'] !='' && isset($tournamentData['filterDependentValue']) && $tournamentData['filterDependentValue'] !='') {
                  $reportQuery =  $reportQuery->where('temp_fixtures.competition_id','=',$tournamentData['filterDependentValue']);
                }
                break;
              case 'team':
                $team = $tournamentData['filterValue'];
                $reportQuery = $reportQuery->where(function ($query) use($team)
                    {
                      $query->where('temp_fixtures.home_team',$team)
                            ->orWhere('temp_fixtures.away_team',$team);
                    }
                  );
                break;
              case 'competation_group':
                $reportQuery =  $reportQuery->where('temp_fixtures.competition_id','=',$tournamentData['filterValue']);
              break;
              case 'competation_group_age':
                $reportQuery =  $reportQuery->where('temp_fixtures.age_group_id','=',$tournamentData['filterValue']);
              break;
            }
          }
        }
      $resultData = $reportQuery->get();
      $updatedArray =[];

      foreach($resultData as $key=>$res) {
          $updatedArray[$key] = $res;
          // echo "<pre>"; print_r($res); echo "</pre>"; exit;
          if($res->Home_id == 0 ) {
            $preset = '';
              if(strpos($res->displayHomeTeamPlaceholderName,"." ) != false) {
                if(strpos($res->displayMatchNumber,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($res->displayMatchNumber,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
              
            $updatedArray[$key]->displayHomeTeamPlaceholderName= $preset.$res->displayHomeTeamPlaceholderName;

          }
          if($res->Away_id == 0 ) {
            $preset = '';

              if(strpos($res->displayAwayTeamPlaceholderName,"." ) != false) {
                if(strpos($res->displayMatchNumber,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($res->displayMatchNumber,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
              
            $updatedArray[$key]->displayAwayTeamPlaceholderName= $preset.$res->displayAwayTeamPlaceholderName;

          } 
        }
        return $updatedArray;
    }
    private function getTeamsForClub($club_id, $tournamentId)
    {
      return Team::where('club_id','=',$club_id)->where('tournament_id','=',$tournamentId)->pluck('teams.id')->toArray();
    }
    public function getStanding($tournamentData)
    {
        $reportQuery = DB::table('match_standing')
          ->leftjoin('teams', 'match_standing.team_id', '=', 'teams.id')
          ->leftjoin('countries', 'teams.country_id', '=', 'countries.id')
          ->leftjoin('competitions', 'match_standing.competition_id', '=', 'competitions.id')
          // ->join('temp_fixtures', 'match_standing.competition_id', '=', 'competitions.id')
          ->select('match_standing.*','teams.*',

            DB::raw('match_standing.goal_for - match_standing.goal_against as GoalDifference'),

            DB::raw('CONCAT("'.$this->getAWSUrl.'", countries.logo) AS teamFlag'),
            'countries.country_flag as teamCountryFlag');

          if(isset($tournamentData['competationId']) && $tournamentData['competationId'] !== '')
          {
            $reportQuery = $reportQuery->where('match_standing.competition_id',$tournamentData['competationId']);
          }
          if(isset($tournamentData['competitionId']) && $tournamentData['competitionId'] !== '')
          {
            $reportQuery = $reportQuery->where('match_standing.competition_id',$tournamentData['competitionId']);
          }
          if(isset($tournamentData['tournamentId']) &&  $tournamentData['tournamentId'] !== '')
          {
          $reportQuery = $reportQuery->where('match_standing.tournament_id', $tournamentData['tournamentId']);
          }

          $reportQuery->orderBy('match_standing.manual_order','asc')
                      ->orderBy('match_standing.points','desc')
                      ->orderBy('GoalDifference','desc')
                      ->orderBy('match_standing.goal_for','desc')
                      ->orderBy('match_standing.team_id','asc');

          $tempFixtures = DB::table('temp_fixtures')
                          ->leftjoin('competitions', 'temp_fixtures.competition_id', '=', 'competitions.id')
                          ->where('temp_fixtures.tournament_id', $tournamentData['tournamentId'])
                          ->where('temp_fixtures.competition_id', $tournamentData['competitionId'])
                          ->where('competitions.actual_competition_type', 'Round Robin') 
                         ->select(
                            'temp_fixtures.id as fixtureId',
                            DB::raw('CONCAT(temp_fixtures.home_team_placeholder_name, "-", temp_fixtures.away_team_placeholder_name) AS teamsPlaceHolderName'),
                            'temp_fixtures.display_home_team_placeholder_name as homeTeamPlaceholderName',
                            'temp_fixtures.display_away_team_placeholder_name as awayTeamPlaceholderName',
                            'temp_fixtures.display_match_number',
                            'temp_fixtures.home_team as homeTeam',
                            'temp_fixtures.away_team as awayTeam',
                            'temp_fixtures.home_team_name as homeTeamName',
                            'temp_fixtures.away_team_name as awayTeamName',
                            'temp_fixtures.age_group_id as ageGroupId')

                          ->get();

          // below code for standing data of dis-selected teams

          $home_team_placeholder_name_array = [];
          $away_team_placeholder_name_array = [];
          $otherTeams = [];

          foreach($tempFixtures as $fixture) {
            if($fixture->homeTeam == 0 ) {
              $preset = '';
              if(strpos($fixture->homeTeamPlaceholderName,"." ) != false) {
                if(strpos($fixture->display_match_number,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($fixture->display_match_number,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
              $home_team_placeholder_name_array[] = $preset.$fixture->homeTeamPlaceholderName;
            }
            if($fixture->awayTeam == 0 ) {
              $preset = '';
              if(strpos($fixture->awayTeamPlaceholderName,"." ) != false) {
                if(strpos($fixture->display_match_number,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($fixture->display_match_number,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
              $away_team_placeholder_name_array[] = $preset.$fixture->awayTeamPlaceholderName;
            }
            if($fixture->homeTeam == 0 || $fixture->awayTeam == 0) {
              if($fixture->homeTeam != 0) {
                $otherTeams[] = $fixture->homeTeam;
              }
              if($fixture->awayTeam != 0) {
                $otherTeams[] = $fixture->awayTeam;
              }
            }
          }

          $teamPlaceholderList = array_unique(array_merge($home_team_placeholder_name_array, $away_team_placeholder_name_array));
          $standingTeams = $reportQuery->pluck('team_id')->toArray();
          $differentTeams = array_diff(array_unique($otherTeams), $standingTeams);
          $otherTeams = array_values($differentTeams);

          $otherTeamsData = DB::table('teams')
            ->leftjoin('countries', 'teams.country_id', '=', 'countries.id')
            ->whereIn('teams.id', $otherTeams)
            ->select('teams.*',
              DB::raw('CONCAT("'.$this->getAWSUrl.'", countries.logo) AS teamFlag'),
              'countries.country_flag as teamCountryFlag')
            ->get();

          $allStandingDataArray = [];

          foreach ($otherTeamsData as $key => $team) {
              $standingData = [
                'id'=>null, 'tournament_id'=>$tournamentData['tournamentId'], 'competition_id'=>$tournamentData['competitionId'],
                'team_id'=>null, 'points'=>0, 'played'=>0, 'won'=>0, 'draws'=>0, 'lost'=>0, 'goal_for'=>0, 'goal_against'=>0,
                'manual_order'=>null, 'created_at'=>null, 'updated_at'=>null, 'deleted_at'=>null, 'assigned_group'=>null,
                'user_id'=>null, 'age_group_id'=>null, 'club_id'=>null, 'group_name'=>null, 'name'=>$team->name,
                'place'=>null, 'website'=>null, 'facebook'=>null, 'twitter'=>null, 'shirt_color'=>null, 'esr_reference'=>null,
                'country_id'=>null, 'GoalDifference'=>null, 'teamFlag'=>$team->teamFlag, 'teamCountryFlag'=>$team->teamCountryFlag
              ];

              $allStandingDataArray[] = $standingData;
          }

          foreach ($teamPlaceholderList as $key => $teamPlaceholder) {
              $standingData = [
                'id'=>null, 'tournament_id'=>$tournamentData['tournamentId'], 'competition_id'=>$tournamentData['competitionId'],
                'team_id'=>null, 'points'=>0, 'played'=>0, 'won'=>0, 'draws'=>0, 'lost'=>0, 'goal_for'=>0, 'goal_against'=>0,
                'manual_order'=>null, 'created_at'=>null, 'updated_at'=>null, 'deleted_at'=>null, 'assigned_group'=>null,
                'user_id'=>null, 'age_group_id'=>null, 'club_id'=>null, 'group_name'=>null, 'name'=>$teamPlaceholder,
                'place'=>null, 'website'=>null, 'facebook'=>null, 'twitter'=>null, 'shirt_color'=>null, 'esr_reference'=>null,
                'country_id'=>null, 'GoalDifference'=>null, 'teamFlag'=>null, 'teamCountryFlag'=>null
              ];

              $allStandingDataArray[] = $standingData;
          }

          $holdingTeamStandings = collect($allStandingDataArray);
          $competitionStandings = $reportQuery->get();
          // dd($competitionStandings);
          $mergedStandings = $competitionStandings->merge($holdingTeamStandings);

          // end
          return $mergedStandings;
    }
    public function getDrawTable($tournamentData){

       $isMatchExist = false;
       $totalMatches = DB::table('temp_fixtures')
                ->where('temp_fixtures.tournament_id',$tournamentData['tournamentId'])
                ->where('temp_fixtures.competition_id',$tournamentData['competationId'])
                // ->where('temp_fixtures.is_scheduled','=',1)

                ->select(
                DB::raw('CONCAT(temp_fixtures.hometeam_score, "-", temp_fixtures.awayteam_score) AS scoresFix'),
                DB::raw('CONCAT(temp_fixtures.home_team, "-", temp_fixtures.away_team) AS teamsFix'),
                DB::raw('CONCAT(temp_fixtures.home_team_placeholder_name, "-", temp_fixtures.away_team_placeholder_name) AS teamsPlaceHolderName'),
                'temp_fixtures.match_datetime as matchDateTime',
                'temp_fixtures.home_team as homeTeam',
                'temp_fixtures.away_team as awayTeam',
                'temp_fixtures.display_match_number',
                'temp_fixtures.home_team_name as homeTeamName',
                'temp_fixtures.away_team_name as awayTeamName',
                'temp_fixtures.display_home_team_placeholder_name as homeTeamPlaceholderName',
                'temp_fixtures.display_away_team_placeholder_name as awayTeamPlaceholderName'
                  ) ->get();

      $matchArr = array();
      $matchDate = array();

      if(!$totalMatches->isEmpty() && $totalMatches->count() > 0)
      {
        $isMatchExist = true;
        foreach($totalMatches as $data) {
          $homeTeam = null;
          $awayTeam = null;

          if($data->homeTeam == 0 ) {
            $preset = '';
              if(strpos($data->homeTeamPlaceholderName,"." ) != false) {
                if(strpos($data->display_match_number,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($data->display_match_number,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
              
            $homeTeam = $preset.$data->homeTeamPlaceholderName;
          } else {
            $homeTeam = $data->homeTeam;
          }

          if($data->awayTeam == 0 ) {
             $preset = '';
              if(strpos($data->awayTeamPlaceholderName,"." ) != false) {
                if(strpos($data->display_match_number,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($data->display_match_number,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
            $awayTeam = $preset.$data->awayTeamPlaceholderName;
          } else {
            $awayTeam = $data->awayTeam;
          }

          $key = $homeTeam . '-' . $awayTeam;
          $matchArr[$key] = $data->scoresFix;
          $matchDate[$key] = $data->matchDateTime;
        }
      }  else {
          $errorMsg= 'No Matches';
      }

        $comp = DB::table('temp_fixtures')
                    ->join('competitions','competitions.id','temp_fixtures.competition_id')
                    // ->where('competitions.actual_competition_type', 'Round Robin')
                          // ->where('competitions.competation_round_no', 'Round 1')
                    
                    ->where('temp_fixtures.tournament_id','=',$tournamentData['tournamentId'])
                    ->where('temp_fixtures.competition_id','=',$tournamentData['competationId'])
                    ->select('temp_fixtures.home_team','temp_fixtures.away_team', 'temp_fixtures.home_team_name as homeTeamName','temp_fixtures.display_match_number', 'temp_fixtures.away_team_name as awayTeamName', 'temp_fixtures.display_home_team_placeholder_name as homeTeamPlaceholderName', 'temp_fixtures.display_away_team_placeholder_name as awayTeamPlaceholderName','competitions.competation_round_no as comp_round_no')->orderBy('homeTeamPlaceholderName')->orderBy('awayTeamPlaceholderName')->get();

        $competition = Competition::find($tournamentData['competationId']);
        $splittedCompetitionActualName = explode('-', $competition->actual_name);
        $inititalOfHolidingName = isset($splittedCompetitionActualName[2]) ? $splittedCompetitionActualName[2] . '-' : '';

        $home_team_arr = [];
        $away_team_arr = [];
        $team_placeholder_name_arr_all = [];
        $all_competition_placeholders = [];

        foreach ($comp as $key => $value) {
          if($value->home_team == 0 ) {
            // if($value->comp_round_no != 'Round 1' ){
            if(strpos($value->homeTeamPlaceholderName,"." ) != false) {
                if(strpos($value->display_match_number,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($value->display_match_number,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
              $team_placeholder_name_arr_all[] = $inititalOfHolidingName . $preset.$value->homeTeamPlaceholderName;  
            // }
            
          }
          if($value->away_team == 0  ) {
             // if($value->comp_round_no != 'Round 1' ){ 
             $preset = '';
              if(strpos($value->awayTeamPlaceholderName,"." ) != false) {
                if(strpos($value->display_match_number,"wrs" ) != false) {
                  $preset = 'wrs.';
                }
                if(strpos($value->display_match_number,"lrs" ) != false) {
                  $preset = 'lrs.';
                }
              }
              $team_placeholder_name_arr_all[] = $inititalOfHolidingName . $preset.$value->awayTeamPlaceholderName;
             // }
            
          }

          if( $value->home_team != 0 ) {
            $home_team_arr[] = $value->home_team;
            $team_placeholder_name_arr_all[] = $value->home_team;
          }
          if( $value->away_team != 0 ) {
            $away_team_arr[] = $value->away_team;
            $team_placeholder_name_arr_all[] = $value->away_team;
          }
        }

        $teamList = array_unique(array_merge($home_team_arr,$away_team_arr));
        $teamPlaceholderList = array_values(array_unique($team_placeholder_name_arr_all));

        $teamData = DB::table('teams')
                    ->leftjoin('countries', 'teams.country_id', '=', 'countries.id')
                    ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                    ->leftjoin('competitions', 'competitions.id', '=', 'teams.competation_id')
                    ->select('teams.id as TeamId','teams.name as TeamName','competitions.*','countries.logo as TeamLogo',
                      'countries.country_flag as TeamCountryFlag'
                      )->whereIn('teams.id',$teamList)->get();

      $teamDetails=array();

      if(!$teamData->isEmpty() && $teamData->count() > 0)
      {

        foreach($teamData as $Tdata) {
          //$numTeamsArray[]=$Tdata->TeamId;
          $teamDetails[$Tdata->TeamId]['TeamName']=$Tdata->TeamName;
          $teamDetails[$Tdata->TeamId]['TeamFlag']=$Tdata->TeamLogo;
          $teamDetails[$Tdata->TeamId]['TeamCountryFlag']=$Tdata->TeamCountryFlag;
        }
      } else if (count($team_placeholder_name_arr_all) == 0){

        $errorMsg= 'No Team Assigned Yet';
        return $errorMsg;
      }

      $htmlData='';
      $arr1=array();
      $matchNotExist=false;
      for($i=0;$i<count($teamPlaceholderList);$i++)
      {
        $rowKey = null;

        if (in_array($teamPlaceholderList[$i], $teamList)) {
          $teamId = $teamPlaceholderList[$i];
          $rowKey = $teamId;
          $arr1[$i]['id'] = $teamId;
          $arr1[$i]['TeamName'] = $teamDetails[$teamId]['TeamName'];
          $arr1[$i]['TeamFlag'] = $teamDetails[$teamId]['TeamFlag'];
          $arr1[$i]['TeamCountryFlag'] = $teamDetails[$teamId]['TeamCountryFlag'];
        } else {
          $rowKey = explode('-', $teamPlaceholderList[$i])[1];
          $arr1[$i]['id'] = null;
          $arr1[$i]['TeamName'] = str_replace('Group-', '', $teamPlaceholderList[$i]);
          $arr1[$i]['TeamFlag'] = null;
          $arr1[$i]['TeamCountryFlag'] = null;
        }
        // dd($arr1,$teamPlaceholderList);
        for($j=0;$j<count($teamPlaceholderList);$j++)
        {
          $colKey = null;
          if (in_array($teamPlaceholderList[$j], $teamList)) {
            $colKey = $teamPlaceholderList[$j];
          } else {
            $colKey = explode('-', $teamPlaceholderList[$j])[1];
          }

          if($isMatchExist ==  true)
          {
            if($i==$j)
            {
              $arr1[$i]['matches'][$j] ='Y';
            }
            else
            {
              if(array_key_exists($rowKey.'-'.$colKey, $matchArr)) {
                $arr1[$i]['matches'][$j]['score']= $matchArr[$rowKey.'-'.$colKey];
                $arr1[$i]['matches'][$j]['date']= $matchDate[$rowKey.'-'.$colKey];
                $arr1[$i]['matches'][$j]['home']= $rowKey;
                $arr1[$i]['matches'][$j]['away']= $colKey;
              } else {
                $arr1[$i]['matches'][$j]= 'X';
              }
            }
          } else {
            $matchNotExist=true;
            $arr1[$i]['matches'][$j]['score'] = '';
          }
        }
      }

      if($matchNotExist == true) {
        return array();
      }
      return $arr1;

    }
    //  below are dummy implemetation
    public function getDrawTable12($tournamentData)
    {
      // Now here we have to Arrange Table Team wise
      $teamData = DB::table('teams')
                    ->leftjoin('countries', 'teams.country_id', '=', 'countries.id')
                    ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')

                    ->leftjoin('competitions', 'competitions.tournament_competation_template_id', '=', 'tournament_competation_template.id')

                    ->select('teams.id as TeamId','teams.name as TeamName','competitions.*','countries.logo as TeamLogo')
                    ->where('teams.tournament_id',2)
                    ->where('competitions.id',5)
                    ->get();
      // we get Matches for group
      $matches =  DB::table('temp_fixtures')
                    ->where('temp_fixtures.tournament_id',2)
                    ->where('temp_fixtures.competition_id',5)
                    ->get()
                    ->toArray();

    // Here we get Teams For Groups Now we have to Iterate it for matches
    $teamArray = array();
    //$matchArray = (array) $matches;

    //print_r($teamData);exit;
    foreach($teamData as $key=>$team) {

      $teamArray[$key]['TeamId']= $team->TeamId;
      $teamArray[$key]['Teamname']= $team->TeamName;
      $teamArray[$key]['TeamFlag']= $team->TeamLogo;

      // Now here we find match for it in for that team
      $param = $team->TeamId;

      $ids = array_map(
      function($item) use ($param) {
         // Here we get Single Match Record And insert in Team Array
         $tmmatchArray = array();


         $homeScore=0;
         $awayScore=0;

         if($param == $item->home_team ) {
        //  echo 'HomeTeam id'.$item->home_team;
            // Its His Match
            //$tmmatchArray['home']['home_score'] = $item->hometeam_score;
            //$tmmatchArray['home']['away_score'] = $item->awayteam_score;
            $homeScore = $item->hometeam_score.'-'.$item->awayteam_score;
          }
         else if($param == $item->away_team)
         {

          $awayScore = $item->awayteam_score.'-'.$item->hometeam_score;
         // $tmmatchArray['away']['home_score'] = $item->hometeam_score;
         // $tmmatchArray['away']['away_score'] = $item->awayteam_score;
         }

         // Also Append Status by comma

         //$result_h = ($homeScore > $awayScore) ? 'Loss' : 'Won';
         //$result_a = ($awayScore > $homeScore) ? 'Won' : 'Loss';

        //$tmmatchArray['score_h'] = $homeScore.','.$result_h;
        //$tmmatchArray['score_a'] = $awayScore.','.$result_a;

        $tmmatchArray['score_h'] = $homeScore;
        $tmmatchArray['score_a'] = $awayScore;

      // $tmmatchArray['result_h'] =$result_h;
      // $tmmatchArray['result_a'] = $result_a;
         //$tmmatchArray['score_a'] = $homescore;

      return $tmmatchArray;


      },$matches);


      // Now here we have to Set it for Particular Match
      $aw=array();

      foreach($ids as $key1=>$elm) {


        if(is_array($elm) && count($elm) > 0) {

          if($elm['score_h'] == 0 )
            $aw[] = $elm['score_a'];
          if($elm['score_a'] == 0 )
            $aw[] = $elm['score_h'];

        }
      }

       // Remove zero Element
       $remove = array(0);

       $result = array_diff($aw, $remove);
       // hre we append the zero the value

       //exit;

       $teamArray[$key]['matches'] = $result;
       $teamArray[$key]['matches']['tId'] = $param;
    }

      return $teamArray;
    }

    public function setMatchSchedule($data, $allowSchedulingForcefully = false)
    {
      $teamData = TempFixture::join('tournament_competation_template','temp_fixtures.age_group_id','tournament_competation_template.id')->where('temp_fixtures.id',$data['matchId'])->select('tournament_competation_template.team_interval','tournament_competation_template.pitch_size','temp_fixtures.*')->first()->toArray();
      $team_interval =   $teamData['team_interval'];

      $pitchData = Pitch::find($data['pitchId']);
      $pitchSize = $pitchData->size;
      $ageCategoryPitchSize = $teamData['pitch_size'];
      $setFlag = 0;

      if( $allowSchedulingForcefully == false && $pitchSize!=$ageCategoryPitchSize ) {
        return -2;
      }

      $startTime =  Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes($team_interval);
      $endTime =  Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes(0);
      if($teamData['home_team'] != 0 && $teamData['away_team'] != 0 ) {
        $teams = array($teamData['home_team'],$teamData['away_team'] );
        $teamId = true;
      } else{
        $teams = array($teamData['home_team_placeholder_name'],$teamData['away_team_placeholder_name'] );
        $teamId = false;
      }

      $matchResultCount = TempFixture::where('tournament_id',$data['tournamentId'])
                ->where('id','!=',$data['matchId'])
                ->where('is_scheduled',1)
                ->where('age_group_id',$teamData['age_group_id'])
                ->where(function($query1) use ($teams,$teamId) {
                  if($teamId){
                    $query1->whereIn('home_team',$teams)
                           ->orWhereIn('away_team',$teams) ;
                  } else{
                    $query1->whereIn('home_team_placeholder_name',$teams)
                           ->orWhereIn('away_team_placeholder_name',$teams) ;
                  }

                })

                ->where(function($query) use ($team_interval,$startTime,$endTime,$data) {
                    $edStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes(0);
                    $edEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes($team_interval);
                    $sdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes($team_interval);
                    $sdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes(0);
                    $query->where(function($query2) use ($sdStartTime,$sdEndTime) {
                      $query2->where('match_endtime','>',$sdStartTime)->where('match_endtime','<=',$sdEndTime);
                    });
                    $query->orWhere(function($query3) use ($edStartTime,$edEndTime) {
                       $query3->where('match_datetime','>=',$edStartTime)->where('match_datetime','<',$edEndTime);
                    });
                    $query->orWhere(function($query4) use ($data) {
                      $query4->where('match_datetime','>',$data['matchStartDate'])->where('match_datetime','<',$data['matchEndDate']);
                    });
                    $query->orWhere(function($query5) use ($data) {
                      $query5->where('match_datetime','>=',$data['matchStartDate'])->where('match_datetime','<',$data['matchEndDate']);
                    });
                    $query->orWhere(function($query6) use ($data) {
                      $query6->where('match_endtime','>',$data['matchStartDate'])->where('match_endtime','<=',$data['matchEndDate']);
                    });
                 })
                ->get();

      if($matchResultCount->count() >0){
        if( $allowSchedulingForcefully == false && ((strpos($teamData['match_number'],"RR1") != false) || (strpos($teamData['match_number'],"PM1" ) != false)) ) {
          return -1;
        }

        $setFlag = 1;
      }

      $updateData = [
        'venue_id' => $pitchData->venue_id,
        'pitch_id' => $data['pitchId'],
        'match_datetime' => $data['matchStartDate'],
        'match_endtime' => $data['matchEndDate'],
        'is_scheduled' => 1,
        'minimum_team_interval_flag' => $setFlag,
      ];

      $updateResult = DB::table('temp_fixtures')
          ->where('id', $data['matchId'])
          ->update($updateData);

      $matchData = array('teams'=>$teams,'tournamentId'=>$data['tournamentId'],'ageGroupId'=>$teamData['age_group_id'],'teamId'=>$teamId);
      $matchresult =  $this->checkTeamIntervalforMatches($matchData);

      return $updateResult;
    }
    public function matchUnschedule($matchId)
    {

      $matchData = DB::table('temp_fixtures')->find($matchId);
      // dd($matchData);

      $updateData = [
        'is_scheduled' => 0,
        'pitch_id' => 0,
        'referee_id' => NULL,
        'hometeam_score' => NULL,
        'awayteam_score' => NULL,
        'match_datetime' => NULL,
        'match_endtime' => NULL,
        'venue_id' => 0,

      ];
     $updateResult =  DB::table('temp_fixtures')
            ->where('id', $matchId)
            ->update($updateData);
      // if($matchData->home_team !=0 && $matchData->away_team !=0) {
        if($matchData->home_team != 0 && $matchData->away_team != 0) {
          $teamId = true;
          $teamsList = array($matchData->home_team, $matchData->away_team);
        }else{
          $teamId = false;
          $teamsList = array($matchData->home_team_placeholder_name, $matchData->away_team_placeholder_name);

        }
        $tournamentId = $matchData->tournament_id;
        $ageGroupId  = $matchData->age_group_id;

        $matchData = array('teams'=>$teamsList,'tournamentId'=>$tournamentId,'ageGroupId'=>$ageGroupId,'teamId'=>$teamId);

        $matchresult =  $this->checkTeamIntervalforMatches($matchData);
      // }
     return $updateResult;
    }

    public function getAllScheduledMatches($data)
    {
        return TempFixture::where('tournament_id',$data['tournamentId'])
                  ->where('is_scheduled','1')
                  ->get();
    }
    public function removeAssignedReferee($matchId)
    {
      return TempFixture::where('id',$matchId)
                  ->update(
                    ['referee_id' => '0']
                    );
    }
    public function assignReferee($data)
    {
       $refereeData = Referee::find($data['refereeId'])->toArray();
        // dd($data);
       $age_group = explode(',',$refereeData['age_group_id']);
       $matchData = TempFixture::where('match_datetime','<=',$data['matchStartDate'])
                  ->where('match_endtime','>=',$data['matchStartDate'])
                  ->where('tournament_id',$data['tournamentId'])
                  ->where('is_scheduled',1)
                  ->where('pitch_id',$data['pitchId'])
                  ->Where(function($query){
                      $query->where('referee_id',NULL)
                            ->orWhere('referee_id',0);
                  });
      if($data['filterKey']!='' && $data['filterValue']!= '') {
        if($data['filterKey'] == 'age_category' ){
          $matchData->where('age_group_id',$data['filterValue']['id']);
        } else {
          $matchData->where('venue_id',$data['filterValue']['id']);
        }
      }
      if( $matchData->count() == 0){
        return ['status'=> false,'data' => 'Please assign referee properly'];
      }else{
        if($age_group){
        $matchData = $matchData->whereIn('age_group_id',$age_group)->first();
          if(!$matchData){
            return ['status' => false, 'data' => 'This referee is not authorised to referee this age category'];
          }else{
             $result =  $matchData->update(['referee_id' => $data['refereeId']]);
          return  ['status' => true, 'data' => $matchData];
          }
        }
      }

    }
    public function saveResult($data)
    {
      $updateData = [
        'referee_id' => $data['refereeId'],
        'hometeam_score' => $data['homeTeamScore'],
        'awayteam_score' => $data['awayTeamScore'],
        'match_status' => $data['matchStatus'],
        'match_winner' => $data['matchWinner'],
        'comments' => $data['comments'],
      ];
      $data = TempFixture::where('id',$data['matchId'])
                  ->update($updateData);
      // TODO : call function to add result
      return $data;
    }

    public function getMatchDetail($matchId)
    {
      return TempFixture::leftjoin('teams as home_team', function ($join) {
              $join->on('home_team.id', '=', 'temp_fixtures.home_team');
          })
          ->leftjoin('teams as away_team', function ($join) {
              $join->on('away_team.id', '=', 'temp_fixtures.away_team');
          })
          ->with('referee','pitch','competition', 'winnerTeam', 'categoryAge')->select('temp_fixtures.*','home_team.comments as hometeam_comment','away_team.comments as awayteam_comment')->find($matchId);
    }

    public function getLastUpdateValue($tournamentId)
    {
      $val = TempFixture::where('tournament_id','=',$tournamentId)
              ->select('id','updated_at')
              ->orderBy('updated_at','desc')->first();
      return (isset($val['updated_at']) && $val['updated_at'] != '') ? $val['updated_at'] :new \DateTime();
    }

    public function setUnavailableBlock($matchData)
    {
        // dd($matchData);
        $data = [
          'tournament_id' => $matchData['tournamentId'],
          'pitch_id' => $matchData['pitchId'],
          'match_start_datetime' => $matchData['matchStartDate'],
          'match_end_datetime' => $matchData['matchEndDate'],
        ];
        return PitchUnavailable::create($data);
    }
    public function getUnavailableBlock($data)
    {
        return PitchUnavailable::where('tournament_id',$data['tournamentId'])->where(DB::raw('DATE(match_start_datetime)'),$data['startDate'])
                  ->get();
    }
    public function removeBlock($block_id)
    {
        return PitchUnavailable::find($block_id)->delete();
    }
    public function updateScore($matchData)
    {
      $updateData = [
        'hometeam_score' => $matchData['home_score'],
        'awayteam_score' => $matchData['away_score']
      ];
      return DB::table('temp_fixtures')
            ->where('id', $matchData['matchId'])
            ->update($updateData);
    }

    public function saveStandingsManually($data) {
      $competitionId = $data['competitionId'];
      $tournamentId = $data['tournament_id'];
      $teamDetails = $data['teamDetails'];

      if(count($data['teamDetails']) > 0) {

        $competition = Competition::find($competitionId);
        $competition->is_manual_override_standing = $data['isManualOverrideStanding'] == true ? 1 : 0;
        $competition->save();
      }

      if($data['isManualOverrideStanding'] == true) {
          foreach ($teamDetails as $key => $teamId) {
              $teamStanding = DB::table('match_standing')->where('tournament_id', $tournamentId)->where('competition_id', $competitionId)->where('team_id', $teamId)->update(['manual_order' => $key+1]);
          }
      } else {
          $teamStanding = DB::table('match_standing')->where('competition_id', $competitionId)->update(['manual_order' => null]);
      }
      // $competition = Competition::find($competitionId);

    }
}
