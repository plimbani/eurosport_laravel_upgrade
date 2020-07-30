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
use Laraspace\Models\TournamentCompetationTemplates;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

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
      
      $tournamentId = $tournamentData['tournamentId'];
      $token = \JWTAuth::getToken();

      $reportQuery = DB::table('competitions')
                     ->leftjoin('tournament_competation_template','tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
                     ->leftjoin('age_category_divisions', 'competitions.age_category_division_id', '=', 'age_category_divisions.id');

      if(isset($tournamentData['competationFormatId']) && $tournamentData['competationFormatId'] != '') {
        $reportQuery->where('competitions.tournament_competation_template_id','=',
          $tournamentData['competationFormatId']);
      }

      $reportQuery->where('competitions.tournament_id', $tournamentId);
      if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
        $reportQuery->select('competitions.*','tournament_competation_template.group_name', 'age_category_divisions.name as divisionName', 'age_category_divisions.id as divisionId', DB::raw('(select count(*) from temp_fixtures where temp_fixtures.competition_id = competitions.id and temp_fixtures.is_scheduled = 1) AS scheduleCount'))->having('scheduleCount', '>', 0);
      } else {
        $reportQuery->select('competitions.*','tournament_competation_template.group_name', 'age_category_divisions.name as divisionName', 'age_category_divisions.id as divisionId');
      }
      $reportQuery = $reportQuery->get();

      $divisionsData = [];
      $roundRobinData = [];
      $finalData = [];
      $ageCategoryData = [];
      $roundRobinGroups = [];
      $divisionGroups = [];
      foreach ($reportQuery as $data) {
        if($data->age_category_division_id != '') {
          $divisionsData[$data->divisionName][$data->competation_round_no][] = $data;
        } else {
          $roundRobinData[$data->competation_round_no][] = $data;
          $roundRobinGroups[] = $data;
        }
      }

      if((app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
        $divisionIndex = 0;
        foreach ($divisionsData as $divisionName => $divisionData) {
          $divisionGroups[$divisionIndex]['title'] = $divisionName;
          $divisionGroups[$divisionIndex]['data'] = [];
          foreach ($divisionData as $roundKey => $groups) {
            $divisionGroups[$divisionIndex]['data'] = array_merge($divisionGroups[$divisionIndex]['data'], $groups);
          }
          $divisionIndex++;
        }
      }

      $finalData['round_robin'] = $roundRobinData;
      $finalData['divisions'] = $divisionsData;

      $ageCategoryData['ageCategoryData'] = $finalData;
      $ageCategoryData['mainData'] = $reportQuery;

      if((app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
        $ageCategoryData['round_robin_groups'] = $roundRobinGroups;
        $ageCategoryData['division_groups'] = $divisionGroups;
      }

      return $ageCategoryData;
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
                'home_team.shirt_color as HomeTeamShirtColor','away_team.shirt_color as AwayTeamShirtColor','home_team.shorts_color as HomeTeamShortsColor','away_team.shorts_color as AwayTeamShortsColor',
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

    public function checkMaximumTeamIntervalforMatches($matchData) {
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

          return  $this->findMaximumMatchInterval($matches);

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
      $teamData = TempFixture::join('tournament_competation_template','temp_fixtures.age_group_id','tournament_competation_template.id')->where('temp_fixtures.id',$data->id)->select('tournament_competation_template.minimum_team_interval','temp_fixtures.*')->first()->toArray();
      $team_interval =  $teamData['minimum_team_interval'];

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
      $token = \JWTAuth::getToken();
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
              'temp_fixtures.home_team as Home_id','temp_fixtures.away_team as Away_id','temp_fixtures.minimum_team_interval_flag as min_interval_flag','temp_fixtures.maximum_team_interval_flag as max_interval_flag',
              DB::raw('CONCAT("'.$this->getAWSUrl.'", HomeFlag.logo) AS HomeFlagLogo'),
              DB::raw('CONCAT("'.$this->getAWSUrl.'", AwayFlag.logo) AS AwayFlagLogo'),
              'HomeFlag.country_flag as HomeCountryFlag',
              'AwayFlag.country_flag as AwayCountryFlag',
              'HomeFlag.name as HomeCountryName',
              'AwayFlag.name as AwayCountryName',
              'temp_fixtures.hometeam_score as homeScore',
              'temp_fixtures.awayteam_score as AwayScore',
              'temp_fixtures.comments as matchRemarks',
              'temp_fixtures.pitch_id as pitchId',
              'temp_fixtures.is_scheduled',
              'temp_fixtures.is_final_round_match',
              'temp_fixtures.is_result_override as isResultOverride',              
              'home_team.name as HomeTeam','away_team.name as AwayTeam',
              'home_team.shirt_color as HomeTeamShirtColor','away_team.shirt_color as AwayTeamShirtColor',
              'home_team.shorts_color as HomeTeamShortsColor','away_team.shorts_color as AwayTeamShortsColor',
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
              'temp_fixtures.home_yellow_cards', 'temp_fixtures.away_yellow_cards',
              'temp_fixtures.home_red_cards', 'temp_fixtures.away_red_cards',
              'temp_fixtures.score_last_update_date_time',
              DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game'),
              'temp_fixtures.schedule_last_update_date_time'
              )
          ->where('temp_fixtures.tournament_id', $tournamentData['tournamentId']);

        if(isset($tournamentData['tournamentDate']) && $tournamentData['tournamentDate'] !== '' && $tournamentData['tournamentDate'] !== 'all')
        {

          $dd1 = \DateTime::createFromFormat('d/m/Y H:i:s', $tournamentData['tournamentDate'].' 00:00:00');
          $mysql_date_string = $dd1->format('Y-m-d H:i:s');

          //echo $dd1;
           $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime', '=',$mysql_date_string);
        }

        if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
          $reportQuery = $reportQuery->where('is_scheduled', 1);
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

        if(isset($tournamentData['is_scheduled']) && $tournamentData['is_scheduled'] !== '')
        {
          // TODO: add constraint to only Show which are Scheduled
          $reportQuery =  $reportQuery->where('temp_fixtures.is_scheduled','=',$tournamentData['is_scheduled']);
        }
        if(isset($tournamentData['fixture_date']))
        {
          $reportQuery =  $reportQuery->whereDate('temp_fixtures.match_datetime','=',$tournamentData['fixture_date']);
        }
        
        if(isset($tournamentData['matchScoreFilter']) && $tournamentData['matchScoreFilter'] == 'played') {
            $reportQuery = $reportQuery->where(function($query) {
                                $query->where('temp_fixtures.hometeam_score', '!=', NULL)
                                ->orWhere('temp_fixtures.awayteam_score', '!=', NULL);
                            });
        }
          
        if(isset($tournamentData['matchScoreFilter']) && $tournamentData['matchScoreFilter'] == 'to_be_played') { 
          $reportQuery = $reportQuery->where(function($query) {
                                $query->where('temp_fixtures.hometeam_score', NULL)
                                ->orWhere('temp_fixtures.awayteam_score', NULL);
                            });
        }

        // Check all rounds matches are placing matches or not
        $roundQuery = 0;
        if(isset($tournamentData['competitionId'])) {
          $compDiv = DB::table('competitions')->where('competitions.id', $tournamentData['competitionId'])->pluck('competitions.age_category_division_id')->toArray();
          if($compDiv[0] != ''){
            $getAllCompetitionID = DB::table('competitions')->where('competitions.age_category_division_id',$compDiv[0])->pluck('competitions.id')->toArray();
            // All rounds are pm or round robin
             $countForRRElem = DB::table('temp_fixtures')->whereIn('temp_fixtures.competition_id',$getAllCompetitionID)
             ->whereIn('round',['Round Robin'])->count(); //,'Elimination'
             
             if ($countForRRElem == 0){
              $roundQuery = 1;
            }
          }
        }

        // Check for all knockout placing matches
        $showAllPlacingMatchesOfKnockout = false;
        if(isset($tournamentData['competitionId'])) {
          $knockoutAgeCategory = Competition::leftJoin('tournament_competation_template', 'competitions.tournament_competation_template_id', '=', 'tournament_competation_template.id')
            ->where('competitions.id', $tournamentData['competitionId'])
            ->where('tournament_competation_template.tournament_format', 'basic')
            ->where('tournament_competation_template.competition_type', 'knockout')
            ->where('competitions.competation_round_no', '!=', 'Round 1')
            ->first();
          if($knockoutAgeCategory) {
            $showAllPlacingMatchesOfKnockout = true;
            $getAllCompetitionID = Competition::where('tournament_competation_template_id', $knockoutAgeCategory->tournament_competation_template_id)
            ->where('competitions.competation_round_no', '!=', 'Round 1')
            ->pluck('id')->toArray();
          }
        }

        // Todo Added Condition For Filtering Purpose on Pitch Planner
        if(isset($tournamentData['fiterEnable'])){
          if(isset($tournamentData['filterKey']) && $tournamentData['filterKey'] !='') {
            switch($tournamentData['filterKey']) {
              case 'location':
                // $reportQuery =  $reportQuery->where('temp_fixtures.venue_id','=',$tournamentData['filterValue']);
                $reportQuery = $reportQuery->where(function ($query) use($tournamentData)
                   {
                     $query->where('temp_fixtures.venue_id',$tournamentData['filterValue'])
                           ->orWhere('temp_fixtures.is_scheduled', 0);
                   }
                 );
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
              case 'competation_group_division':
                $reportQuery =  $reportQuery->where('competitions.age_category_division_id','=',$tournamentData['filterValue']);
                break;
              case 'competation_group_agecategory_round':
                $filterValue = explode("-", $tournamentData['filterValue']);
                $reportQuery =  $reportQuery->where('temp_fixtures.age_group_id','=',$filterValue[0]);
                if($filterValue[1] != "") {
                  $reportQuery =  $reportQuery->where('competitions.age_category_division_id','=',$filterValue[1]);
                }
                $reportQuery =  $reportQuery->where('competitions.competation_round_no','=',$filterValue[2]);
                break;
            }
          }
        }

        if ( $roundQuery || $showAllPlacingMatchesOfKnockout )
        {
          $reportQuery =  $reportQuery->whereIn('temp_fixtures.competition_id',$getAllCompetitionID);
        }
        else
        {
          if(isset($tournamentData['competitionId']) && $tournamentData['competitionId'] !== '')
          {

            $reportQuery = $reportQuery->where('temp_fixtures.competition_id',
                $tournamentData['competitionId']);
          }
        }

        if(isset($tournamentData['matchOrderReport']) && $tournamentData['matchOrderReport'] == 1)
        {
          $reportQuery = $reportQuery->orderBy(DB::raw('match_datetime IS NULL, match_datetime'), 'asc');
        }

        $resultData = $reportQuery->get();
        $updatedArray =[];

        foreach($resultData as $key=>$res) {
          $updatedArray[$key] = $res;
          $updatedArray[$key]->isDivExist = $roundQuery;
          $updatedArray[$key]->isKnockoutPlacingMatches = $showAllPlacingMatchesOfKnockout;
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
      $competition = Competition::find($tournamentData['competitionId']);

      $tournamentCompetationTemplatesRecord = TournamentCompetationTemplates::where('id',$competition->tournament_competation_template_id)->first();

      $rules = $tournamentCompetationTemplatesRecord->rules;


      // Check all results are declared or not
      $checkResultEntered = DB::table('temp_fixtures')->where('tournament_id',$tournamentData['tournamentId'])
        ->where('competition_id',$tournamentData['competitionId'])->where('round','Round Robin')
        ->where(function ($query) {
            $query->whereNull('hometeam_score')
                  ->orWhereNull('awayteam_score');
        })->count();

        $reportQuery = DB::table('match_standing')
          ->leftjoin('teams', 'match_standing.team_id', '=', 'teams.id')
          ->leftjoin('countries', 'teams.country_id', '=', 'countries.id')
          ->leftjoin('competitions', 'match_standing.competition_id', '=', 'competitions.id')
          // ->join('temp_fixtures', 'match_standing.competition_id', '=', 'competitions.id')
          ->select('match_standing.*','teams.*',

            DB::raw('match_standing.goal_for - match_standing.goal_against as GoalDifference'),

            DB::raw('IF(match_standing.Played > 0, (match_standing.goal_for / match_standing.Played), 0) as GoalRatio'),

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

          $reportQuery = $reportQuery->orderBy('match_standing.manual_order','asc');
          $head_to_head = false;
          $check_head_to_head_with_key = '';
          $remain_head_to_head_with_key = '';
          $head_to_head_order_atlast = false;
          if($competition->is_manual_override_standing == 0) {
            foreach($rules as $key => $rule) {

              if($rule['checked'] == false || ( $rule['key'] != 'head_to_head' && $head_to_head == true)) {

                if (  $rule['checked'] == true && ( $rule['key'] != 'head_to_head' && $head_to_head == true )  )
                {
                  if($rule['key'] == 'goal_difference') {
                    $remain_head_to_head_with_key .= '|GoalDifference';
                  }
                  if($rule['key'] == 'goals_for') {
                    $remain_head_to_head_with_key .= '|goal_for';
                  }
                  if($rule['key'] == 'matches_won') {
                    $remain_head_to_head_with_key .= '|won';
                  }
                  if($rule['key'] == 'goal_ratio') {
                    $remain_head_to_head_with_key .= '|GoalRatio';
                  }
                }
                continue;
              }

              if($rule['key'] == 'match_points') {
                $reportQuery = $reportQuery->orderBy('match_standing.points','desc');
                $check_head_to_head_with_key .= '|points';
              }

              if($rule['key'] == 'head_to_head') {
                if($checkResultEntered > 0)
                {
                  $head_to_head_order_atlast = true;
                }
                else
                {
                  $head_to_head = true;
                }
              }
              
              if($rule['key'] == 'goal_difference') {
                $reportQuery = $reportQuery->orderBy('GoalDifference','desc');
                $check_head_to_head_with_key .= '|GoalDifference';
              }
              if($rule['key'] == 'goals_for') {
                $reportQuery = $reportQuery->orderBy('match_standing.goal_for','desc');
                $check_head_to_head_with_key .= '|goal_for';
              }
              if($rule['key'] == 'matches_won') {
                $reportQuery = $reportQuery->orderBy('match_standing.won','desc');
                $check_head_to_head_with_key .= '|won';
              }
              if($rule['key'] == 'goal_ratio') {
                $reportQuery = $reportQuery->orderBy('GoalRatio','desc');
                $check_head_to_head_with_key .= '|GoalRatio';
              }
            }
          }


          if ( !empty($check_head_to_head_with_key) )
          {
            $check_head_to_head_with_key = ltrim($check_head_to_head_with_key,'|');
          }

          if ( !empty($remain_head_to_head_with_key) )
          {
            $remain_head_to_head_with_key = ltrim($remain_head_to_head_with_key,'|');
          }

          if ( $head_to_head_order_atlast )
          {
            $reportQuery = $reportQuery->orderBy('teams.name','asc');
          }

          $reportQuery = $reportQuery->orderBy('match_standing.team_id','asc');
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
          if ( $head_to_head == true && $competition->is_manual_override_standing == 0 )
          {
            $competitionStandings = json_decode(json_encode($competitionStandings), true);

            list($competitionStandings,$sort_head_to_head) = $this->sortByHeadtoHead($competitionStandings,$check_head_to_head_with_key,$tournamentData['tournamentId'],$tournamentData['competitionId'],$tournamentCompetationTemplatesRecord,$remain_head_to_head_with_key);

            if ( $sort_head_to_head == '1') {
              $head_to_head_position_sorting = array();           
              $internal_head_to_head_position_sorting = array();  
              foreach ($competitionStandings as $comp_stand_key => $comp_stand_value) {
                $head_to_head_position_sorting[$comp_stand_key] = (int)$comp_stand_value['head_to_head_position'];
                $internal_head_to_head_position_sorting[$comp_stand_key] = (int)$comp_stand_value['internal_head_to_head_position'];
                
              }
              
              array_multisort($internal_head_to_head_position_sorting,SORT_ASC,$competitionStandings);

            }

            $competitionStandings = collect($competitionStandings);
              

          }
          // dd($competitionStandings);
          $mergedStandings = $competitionStandings->merge($holdingTeamStandings);

          // end
          return $mergedStandings;
    }

    public function sortByHeadtoHead($standingData,$keyConflict,$tournamentId,$compId,$tournamentCompetationTemplatesRecord,$remain_head_to_head_with_key)
    {

      if ( gettype($standingData) != 'array')
      {
        $standingArray = $standingData->toArray();
      }
      else
      {
        $standingArray = $standingData;
      }

      $standingCopyArray = $standingArray;
      $prepareArrayForh2h = [];

      //preparing array to check conflicted team with 
      $keyConflictArray = explode('|', $keyConflict);
      foreach ($standingArray as $key => $value) {
        // make key unique base on admin sorting selection
        $finalKey = '';
        foreach ($keyConflictArray as $cflictkey => $cflictvalue) {
          $finalKey .= '|'.$value[$cflictvalue];
        }

        if ( !empty($finalKey) )
        {
          $finalKey = ltrim($finalKey,'|');
        }
        $prepareArrayForh2h[$finalKey][$key] = $value;
      }

      $conflictedTeamArray = [];
      $conflictedTeamids = [];

      //remove conflicted team from main array and send seprate array further for processing
      foreach ($prepareArrayForh2h as $key => $preparedArray) {
        if ( sizeof($preparedArray) > 1)
        {
          $conflictedTeamArray[$key] = $preparedArray;
          foreach ($preparedArray as $key1 => $value) {
            //unset($standingArray[$key1]);
            $standingArray[$key1]['check_head_to_head'] = true;
            $conflictedTeamids[$key][$key1] = ( isset($value['teamid']) ) ? $value['teamid'] : $value['team_id'];
          }
        }
      }

      //make virtual league table for head to head functionality
      $virtual_lt_sorted = array();
      $virtual_lt_sorted_details = array();
      foreach ($conflictedTeamArray as $cta => $ctv) {
        $headToHeadVirtualLeagueTableData =  $this->headToHeadVirtualLeagueTable($conflictedTeamids,$cta,$ctv,$tournamentId,$compId,$tournamentCompetationTemplatesRecord,$remain_head_to_head_with_key,$standingData);
        if ( !empty($headToHeadVirtualLeagueTableData) )
        {
          $virtual_lt_sorted[$cta] = $headToHeadVirtualLeagueTableData;
          $virtual_lt_sorted_details[$cta]['total_teams'] = count($virtual_lt_sorted[$cta]);
          $virtual_lt_sorted_details[$cta]['remaining'] = count($virtual_lt_sorted[$cta]);
        }
      }

      // make new sorted array
      if ( !empty($virtual_lt_sorted) )
      {
        $pos = 1;
        foreach ($standingArray as $v_l_key => $v_l_value) {
          // Fetch team_id and find position
          if ( !empty($v_l_value['check_head_to_head']) && $v_l_value['check_head_to_head']== 1)
          {
            $flag = false;
            foreach ($virtual_lt_sorted as $vt_key => $vt_value) {
              $total_group_count = count($vt_value);
              foreach ($vt_value as $vt_key1 => $vt_value1) {

                if ( !array_key_exists('team_id', $v_l_value) )
                {
                  $v_l_value['team_id'] = $v_l_value['teamid'];
                }

                if ( $vt_value1['team_id'] == $v_l_value['team_id'] && $flag == false)
                {
                  //$standingArray[$v_l_key]['head_to_head_position'] = $pos + $vt_key1 ;

                  $standingArray[$v_l_key]['head_to_head_position'] = $pos;
                  $standingArray[$v_l_key]['internal_head_to_head_position'] = $pos + $vt_key1 ;

                  $flag = true;
                  $virtual_lt_sorted_details[$vt_key]['remaining'] = $virtual_lt_sorted_details[$vt_key]['remaining'] - 1;

                  if ( $virtual_lt_sorted_details[$vt_key]['remaining'] == 0)
                  {
                    $pos = $pos + $virtual_lt_sorted_details[$vt_key]['total_teams'];
                  }

                }
                else
                {
                  continue;
                }
              }
            }
          }
          else
          {
            $standingArray[$v_l_key]['head_to_head_position'] = $pos;
            $standingArray[$v_l_key]['internal_head_to_head_position'] = $pos;
            $pos++;
          }
        }
        return array($standingArray,'1');
      }
      else
      {
        return array($standingArray,'0');
      }

    }

    public function headToHeadVirtualLeagueTable($conflictedTeamids,$cta,$ctv,$tournamentId,$compId,$tournamentCompetationTemplatesRecord,$remain_head_to_head_with_key,$standingData)
    {
      //merge conflicted teams
      $teamids = $conflictedTeamids[$cta];

      // Fetch fixture from DB and make virtual league table for above teams
      $virtualLegaueTable = [];
      $fixturesOfConflictedTeams = DB::table('temp_fixtures')->where('tournament_id',$tournamentId)
        ->where('competition_id',$compId)->where('round','Round Robin')
        ->whereIn('home_team',$teamids)->whereIn('away_team',$teamids)
        ->get();


      foreach ($fixturesOfConflictedTeams as $fkey => $fvalue) {
        $homeTeam = $fvalue->home_team;
        $awayTeam = $fvalue->away_team;

        if ( !array_key_exists($homeTeam, $virtualLegaueTable))
        {
          $virtualLegaueTable[$homeTeam]['team_id'] =  $homeTeam;
          $virtualLegaueTable[$homeTeam]['played'] =  0;
          $virtualLegaueTable[$homeTeam]['won'] =  0;
          $virtualLegaueTable[$homeTeam]['draws'] =  0;
          $virtualLegaueTable[$homeTeam]['lost'] =  0;
          $virtualLegaueTable[$homeTeam]['goal_for'] =  0;
          $virtualLegaueTable[$homeTeam]['goal_against'] =  0;
          $virtualLegaueTable[$homeTeam]['team_name'] = $fvalue->home_team_name;

        }

        if ( !array_key_exists($awayTeam, $virtualLegaueTable))
        {
          $virtualLegaueTable[$awayTeam]['team_id'] =  $awayTeam;
          $virtualLegaueTable[$awayTeam]['played'] =  0;
          $virtualLegaueTable[$awayTeam]['won'] =  0;
          $virtualLegaueTable[$awayTeam]['draws'] =  0;
          $virtualLegaueTable[$awayTeam]['lost'] =  0;
          $virtualLegaueTable[$awayTeam]['goal_for'] =  0;
          $virtualLegaueTable[$awayTeam]['goal_against'] =  0;
          $virtualLegaueTable[$awayTeam]['team_name'] = $fvalue->away_team_name;
        }

        // Find winner
        $virtualLegaueTable[$homeTeam]['played'] = $virtualLegaueTable[$homeTeam]['played']+1;
        $virtualLegaueTable[$awayTeam]['played'] = $virtualLegaueTable[$awayTeam]['played']+1;

        // if home team won then set below data
        if ( $fvalue->hometeam_score > $fvalue->awayteam_score )
        {
          // set Home team data
          $virtualLegaueTable[$homeTeam]['won'] = $virtualLegaueTable[$homeTeam]['won']+1;
          $virtualLegaueTable[$homeTeam]['goal_for'] = $virtualLegaueTable[$homeTeam]['goal_for']+$fvalue->hometeam_score;
          $virtualLegaueTable[$homeTeam]['goal_against'] = $virtualLegaueTable[$homeTeam]['goal_against']+$fvalue->awayteam_score;


          //set Away team data
          $virtualLegaueTable[$awayTeam]['lost'] = $virtualLegaueTable[$awayTeam]['lost']+1;
          $virtualLegaueTable[$awayTeam]['goal_for'] = $virtualLegaueTable[$awayTeam]['goal_for']+$fvalue->awayteam_score;
          $virtualLegaueTable[$awayTeam]['goal_against'] = $virtualLegaueTable[$awayTeam]['goal_against']+$fvalue->hometeam_score;
        }

        // if away team won then set below data
        if ( $fvalue->awayteam_score > $fvalue->hometeam_score )
        {
          // set Home team data
          $virtualLegaueTable[$homeTeam]['lost'] = $virtualLegaueTable[$homeTeam]['lost']+1;
          $virtualLegaueTable[$homeTeam]['goal_for'] = $virtualLegaueTable[$homeTeam]['goal_for']+$fvalue->hometeam_score;
          $virtualLegaueTable[$homeTeam]['goal_against'] = $virtualLegaueTable[$homeTeam]['goal_against']+$fvalue->awayteam_score;


          //set Away team data
          $virtualLegaueTable[$awayTeam]['won'] = $virtualLegaueTable[$awayTeam]['won']+1;
          $virtualLegaueTable[$awayTeam]['goal_for'] = $virtualLegaueTable[$awayTeam]['goal_for']+$fvalue->awayteam_score;
          $virtualLegaueTable[$awayTeam]['goal_against'] = $virtualLegaueTable[$awayTeam]['goal_against']+$fvalue->hometeam_score;
        }

        // if match draw then set below data
        if ( $fvalue->awayteam_score == $fvalue->hometeam_score )
        {
          // set Home team data
          $virtualLegaueTable[$homeTeam]['draws'] = $virtualLegaueTable[$homeTeam]['draws']+1;
          $virtualLegaueTable[$homeTeam]['goal_for'] = $virtualLegaueTable[$homeTeam]['goal_for']+$fvalue->hometeam_score;
          $virtualLegaueTable[$homeTeam]['goal_against'] = $virtualLegaueTable[$homeTeam]['goal_against']+$fvalue->awayteam_score;


          //set Away team data
          $virtualLegaueTable[$awayTeam]['draws'] = $virtualLegaueTable[$awayTeam]['draws']+1;
          $virtualLegaueTable[$awayTeam]['goal_for'] = $virtualLegaueTable[$awayTeam]['goal_for']+$fvalue->awayteam_score;
          $virtualLegaueTable[$awayTeam]['goal_against'] = $virtualLegaueTable[$awayTeam]['goal_against']+$fvalue->hometeam_score;
        }
      }

      // calculate goal difference and points and sorting virtual league table base on points,goal difference,goals for, team name
      $points_array = $goal_difference = $goal_for = $teamName = array();
      foreach ($virtualLegaueTable as $vkey => $vvalue) {
        $virtualLegaueTable[$vkey]['GoalDifference'] = $vvalue['goal_for'] - $vvalue['goal_against'];

        $won_point = $tournamentCompetationTemplatesRecord->win_point;
        $loss_point = $tournamentCompetationTemplatesRecord->loss_point;
        $draw_point = $tournamentCompetationTemplatesRecord->draw_point;

        $virtualLegaueTable[$vkey]['points'] = ($won_point*$vvalue['won']) + ($draw_point*$vvalue['draws']) + ($loss_point*$vvalue['lost']);

        $points_array[$vkey] = (int)$virtualLegaueTable[$vkey]['points'];
        $goal_difference[$vkey] = (int)$virtualLegaueTable[$vkey]['GoalDifference'];
        $goal_for[$vkey] = (int)$vvalue['goal_for'];
        $teamName[$vkey] = $vvalue['team_name'];

      }

      array_multisort($points_array, SORT_DESC,$goal_difference, SORT_DESC,$goal_for,SORT_DESC,$teamName, SORT_ASC,$virtualLegaueTable);

      //check point, gd, goal for still same 
      $sort_virtual_leaguetable = array();
      foreach ($virtualLegaueTable as $vtakey => $vlvalue) {
        $key_for_check =  $vlvalue['points'].'|'.$vlvalue['GoalDifference'].'|'.$vlvalue['goal_for'];
        $sort_virtual_leaguetable[$key_for_check][] = $vlvalue;
      }

      $conflict_still_in_internal_leaguetable_array = array();
      $remainingSorting = array();

      foreach ($sort_virtual_leaguetable as $skey => $svalue) {
        if(sizeof($svalue) > 1 && !empty($remain_head_to_head_with_key) ){
          if ( !empty($remain_head_to_head_with_key) && gettype($remain_head_to_head_with_key) == 'string')
          {
            $remain_head_to_head_with_key = explode('|',$remain_head_to_head_with_key);
          }

          foreach ($remain_head_to_head_with_key as $rkey => $rvalue) {
              foreach ($svalue as $sskey => $svvalue) {
                $team_id = $svvalue['team_id'];
                if ( array_key_exists_r('team_id', $standingData) )
                {
                  $standKey = array_search($team_id, array_column($standingData, 'team_id'));
                }
                else
                {
                  $standKey = array_search($team_id, array_column($standingData, 'teamid'));
                }

                $svalue[$sskey]['outer_'.$rvalue] = $standingData[$standKey][$rvalue];
                $remainingSorting['outer_'.$rvalue][$sskey] = (int)$standingData[$standKey][$rvalue];
              }
              $sort_virtual_leaguetable[$skey] = $svalue;

            $sortArray[] = &$remainingSorting['outer_'.$rvalue];
            $sortArray[] = SORT_DESC;
          }
          $sortArray[] = &$svalue;

          call_user_func_array('array_multisort', $sortArray);
          }
        $sort_virtual_leaguetable[$skey] = $svalue;
      }

      return Arr::collapse($sort_virtual_leaguetable);
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
        $inititalOfHolidingName = $competition->actual_competition_type == 'Round Robin' ? 'Group-' : 'Pos-';

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
                      'countries.country_flag as TeamCountryFlag',
                      'teams.shirt_color as ShirtColor',
                      'teams.shorts_color as ShortsColor'
                      )->whereIn('teams.id',$teamList)->get();

      $teamDetails=array();

      if(!$teamData->isEmpty() && $teamData->count() > 0)
      {

        foreach($teamData as $Tdata) {
          //$numTeamsArray[]=$Tdata->TeamId;
          $teamDetails[$Tdata->TeamId]['TeamName']=$Tdata->TeamName;
          $teamDetails[$Tdata->TeamId]['TeamFlag']=$this->getAWSUrl.$Tdata->TeamLogo;
          $teamDetails[$Tdata->TeamId]['TeamCountryFlag']=$Tdata->TeamCountryFlag;
          $teamDetails[$Tdata->TeamId]['ShirtColor']=$Tdata->ShirtColor;
          $teamDetails[$Tdata->TeamId]['ShortsColor']=$Tdata->ShortsColor;
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
          $arr1[$i]['ShirtColor'] = $teamDetails[$teamId]['ShirtColor'];
          $arr1[$i]['ShortsColor'] = $teamDetails[$teamId]['ShortsColor'];
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
      $isFixtureScheduled = true;
      $matchData = $data['matchData'];
      $scheduleMatchesArray = $data['isMultiSchedule'] === true ? $data['scheduleMatchesArray'] : [];
      $teamData = TempFixture::join('tournament_competation_template','temp_fixtures.age_group_id','tournament_competation_template.id')->where('temp_fixtures.id',$matchData['matchId'])->select('tournament_competation_template.minimum_team_interval', 'tournament_competation_template.maximum_team_interval','tournament_competation_template.pitch_size','temp_fixtures.*')->first()->toArray();
      $team_interval = $teamData['minimum_team_interval'];
      $maximum_team_interval = $teamData['maximum_team_interval'];

      $pitchData = Pitch::find($matchData['pitchId']);
      $pitchSize = $pitchData->size;
      $ageCategoryPitchSize = $teamData['pitch_size'];
      $setMinimumIntervalFlag = 0;
      $setMaximumIntervalFlag = 0;

      if( $allowSchedulingForcefully == false && $pitchSize!=$ageCategoryPitchSize ) {
        return -2;
      }

      $startTime =  Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes($team_interval);
      $endTime =  Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes(0);
      if($teamData['home_team'] != 0 && $teamData['away_team'] != 0 ) {
        $teams = array($teamData['home_team'],$teamData['away_team'] );
        $teamId = true;
      } else{
        $teams = array($teamData['home_team_placeholder_name'],$teamData['away_team_placeholder_name'] );
        $teamId = false;
      }

      $scheduleMatchesIds = count($scheduleMatchesArray) > 0 ? array_column($scheduleMatchesArray, 'matchId') : [];
      $matchResultCount = TempFixture::where('tournament_id',$matchData['tournamentId']);
      if(count($scheduleMatchesIds) > 0) {
        $matchResultCount = $matchResultCount->where(function($query) use($scheduleMatchesIds) {
          $query->where('is_scheduled', 1)
            ->orWhereIn('id', $scheduleMatchesIds);
        });
      } else {
        $matchResultCount = $matchResultCount->where('is_scheduled', 1);
      }
      $matchResultCount = $matchResultCount->where('age_group_id',$teamData['age_group_id'])
      ->where('id','!=',$matchData['matchId'])
      ->where(function($query1) use ($teams,$teamId) {
        if($teamId){
          $query1->whereIn('home_team',$teams)
                 ->orWhereIn('away_team',$teams);
        } else{
          $query1->whereIn('home_team_placeholder_name',$teams)
                 ->orWhereIn('away_team_placeholder_name',$teams) ;
        }
      })->get()->keyBy('id');

      foreach($scheduleMatchesArray as $scheduleMatch) {
        if($scheduleMatch['matchId'] !== $matchData['matchId'] && isset($matchResultCount[$scheduleMatch['matchId']])) {
          $matchResultCount[$scheduleMatch['matchId']]['match_datetime'] = $scheduleMatch['matchStartDate'];
          $matchResultCount[$scheduleMatch['matchId']]['match_endtime'] = $scheduleMatch['matchEndDate'];
        }
      }

      $matchResultCount = collect($matchResultCount);
      $homeMaximumIntervalMatchResultCount = collect($matchResultCount);
      $awayMaximumIntervalMatchResultCount = collect($matchResultCount);

      // $matchResultCount->where(function($query) use ($team_interval,$startTime,$endTime,$matchData) {
          $edStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchEndDate'])->addMinutes(0);
          $edEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchEndDate'])->addMinutes($team_interval);
          $sdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes($team_interval);
          $sdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes(0);
          $matchResultCount = $matchResultCount->filter(function($item) use ($sdStartTime,$sdEndTime,$edStartTime,$edEndTime,$matchData) {
            if($item['match_endtime'] > $sdStartTime && $item['match_endtime'] <= $sdEndTime) {
              return true;
            }
            if($item['match_datetime'] >= $edStartTime && $item['match_datetime'] < $edEndTime) {
              return true;
            }
            if($item['match_datetime'] > $matchData['matchStartDate'] && $item['match_datetime'] < $matchData['matchEndDate']) {
              return true;
            }
            if($item['match_datetime'] >= $matchData['matchStartDate'] && $item['match_datetime'] < $matchData['matchEndDate']) {
              return true;
            }

            if($item['match_endtime'] > $matchData['matchStartDate'] && $item['match_endtime'] <= $matchData['matchEndDate']) {
              return true;
            }

            return false;
            // $query2->where('match_endtime','>',$sdStartTime)->where('match_endtime','<=',$sdEndTime);
          });
          // $matchResultCount->orWhere(function($query3) use ($edStartTime,$edEndTime) {
          //    $query3->where('match_datetime','>=',$edStartTime)->where('match_datetime','<',$edEndTime);
          // });
          // $matchResultCount->orWhere(function($query4) use ($matchData) {
          //   $query4->where('match_datetime','>',$matchData['matchStartDate'])->where('match_datetime','<',$matchData['matchEndDate']);
          // });
          // $matchResultCount->orWhere(function($query5) use ($matchData) {
          //   $query5->where('match_datetime','>=',$matchData['matchStartDate'])->where('match_datetime','<',$matchData['matchEndDate']);
          // });
          // $matchResultCount->orWhere(function($query6) use ($matchData) {
          //   $query6->where('match_endtime','>',$matchData['matchStartDate'])->where('match_endtime','<=',$matchData['matchEndDate']);
          // });
       //});

      if($matchResultCount->count() >0){
            if( $allowSchedulingForcefully == false && ((strpos($teamData['match_number'],"RR1") != false) || (strpos($teamData['match_number'],"PM1" ) != false)) ) {
                return -1;
            }
            $setMinimumIntervalFlag = 1;
      }

      // Maximum interval time check
      $isFirstMatchOfHomeTeam = true;
      $isFirstMatchOfAwayTeam = true;
      if($homeMaximumIntervalMatchResultCount->count() > 0) {
        $maxedStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchEndDate'])->addMinutes(0);
        $maxedEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchEndDate'])->addMinutes($maximum_team_interval);
        $maxsdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes($maximum_team_interval);
        $maxsdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes(0);
        $homeMaximumIntervalMatchResultCount = $homeMaximumIntervalMatchResultCount->filter(function($item) use ($maxsdStartTime,$maxsdEndTime,$maxedStartTime,$maxedEndTime,$matchData,$teamId,$teamData, &$isFirstMatchOfHomeTeam) {
          $homeTeamCheck = false;
          if($teamId){
            $homeTeamCheck = ($item['home_team'] === $teamData['home_team'] || $item['away_team'] === $teamData['home_team']);
          } else {
            $homeTeamCheck = ($item['home_team_placeholder_name'] === $teamData['home_team_placeholder_name'] || $item['away_team_placeholder_name'] === $teamData['home_team_placeholder_name']);
          }
          if(!$homeTeamCheck) {
            return false;
          } else {
            $isFirstMatchOfHomeTeam = false;
          }
          if($item['match_endtime'] <= $maxsdEndTime && $item['match_endtime'] >= $maxsdStartTime) {
            return true;
          }
          if($item['match_datetime'] >= $maxedStartTime && $item['match_datetime'] <= $maxedEndTime) {
            return true;
          }
          return false;
        });
      }
      if($awayMaximumIntervalMatchResultCount->count() > 0) {
        $maxedStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchEndDate'])->addMinutes(0);
        $maxedEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchEndDate'])->addMinutes($maximum_team_interval);
        $maxsdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes($maximum_team_interval);
        $maxsdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $matchData['matchStartDate'])->subMinutes(0);
        $awayMaximumIntervalMatchResultCount = $awayMaximumIntervalMatchResultCount->filter(function($item) use ($maxsdStartTime,$maxsdEndTime,$maxedStartTime,$maxedEndTime,$matchData,$teamId,$teamData, &$isFirstMatchOfAwayTeam) {
          $awayTeamCheck = false;
          if($teamId){
            $awayTeamCheck = ($item['home_team'] === $teamData['away_team'] || $item['away_team'] === $teamData['away_team']);
          } else {
            $awayTeamCheck = ($item['home_team_placeholder_name'] === $teamData['away_team_placeholder_name'] || $item['away_team_placeholder_name'] === $teamData['away_team_placeholder_name']);
          }
          if(!$awayTeamCheck) {
            return false;
          } else {
            $isFirstMatchOfAwayTeam = false;
          }
          if($item['match_endtime'] <= $maxsdEndTime && $item['match_endtime'] >= $maxsdStartTime) {
            return true;
          }
          if($item['match_datetime'] >= $maxedStartTime && $item['match_datetime'] <= $maxedEndTime) {
            return true;
          }
          return false;
        });
      }
      if( ($homeMaximumIntervalMatchResultCount->count() === 0 && !$isFirstMatchOfHomeTeam) || ($awayMaximumIntervalMatchResultCount->count() === 0 && !$isFirstMatchOfAwayTeam)){
            // if( $allowSchedulingForcefully == false && ((strpos($teamData['match_number'], "RR1") != false) || (strpos($teamData['match_number'],"PM1" ) != false)) ) {
            //     return -3;
            // }
            $setMaximumIntervalFlag = 1;
      }

      $startDateTime = $matchData['matchStartDate'];
      $endDateTime = $matchData['matchEndDate'];
      $fixturesResultCount = TempFixture::where('tournament_id',$teamData['tournament_id'])
        ->where('id','!=', $matchData['matchId'])
        ->where('is_scheduled', 1)
        ->where('pitch_id', $matchData['pitchId'])
        ->where(function($query) use ($startDateTime, $endDateTime) {
            $query->where(function($query2) use ($startDateTime, $endDateTime) {
              $query2->where('match_endtime','>',$startDateTime)->where('match_endtime','<=',$endDateTime);
            });
            $query->orWhere(function($query3) use ($startDateTime,$endDateTime) {
              $query3->where('match_datetime','>=',$startDateTime)->where('match_datetime','<',$endDateTime);
            });
          })
        ->get();


      if($fixturesResultCount->count() > 0) {
        $isFixtureScheduled = false;
        return ['status' => false, 'message' => 'You cannot schedule this match here. As another match is already scheduled.', 'data'=>$teamData, 'is_fixture_scheduled' => $isFixtureScheduled, 'is_another_match_scheduled' => true];
      }


      if($data['isMultiSchedule'] === false && $matchData['scheduleLastUpdateDateTime'] != $teamData['schedule_last_update_date_time']) {
        $isFixtureScheduled = false;
        return ['status' => false, 'message' => 'You need to refresh page to get latest updated fixtures.', 'data'=>$teamData, 'is_fixture_scheduled' => $isFixtureScheduled, 'is_another_match_scheduled' => false];
      }

      if($data['isMultiSchedule'] === false) {
        $updateData = [
          'venue_id' => $pitchData->venue_id,
          'pitch_id' => $matchData['pitchId'],
          'match_datetime' => $matchData['matchStartDate'],
          'match_endtime' => $matchData['matchEndDate'],
          'is_scheduled' => 1,
          'minimum_team_interval_flag' => $setMinimumIntervalFlag,
          'maximum_team_interval_flag' => $setMaximumIntervalFlag,
          'schedule_last_update_date_time' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $updateResult = DB::table('temp_fixtures')
            ->where('id', $matchData['matchId'])
            ->update($updateData);

        $matchData = array('teams'=>$teams,'tournamentId'=>$matchData['tournamentId'],'ageGroupId'=>$teamData['age_group_id'],'teamId'=>$teamId);
        $matchresult =  $this->checkTeamIntervalforMatches($matchData);

        // Check maximum team interval for matches
        $this->checkMaximumTeamIntervalforMatches($matchData);

        return ['status' => true, 'data' => $updateData, 'is_fixture_scheduled' => $isFixtureScheduled, 
        'is_another_match_scheduled' => false, 'maximum_interval_flag' => $setMaximumIntervalFlag];
      }
      return ['status' => true, 'data' => [], 'is_fixture_scheduled' => $isFixtureScheduled, 'is_another_match_scheduled' => false, 'maximum_interval_flag' => $setMaximumIntervalFlag];
    }
    public function matchUnschedule($matchId)
    {
      $matchData = DB::table('temp_fixtures')->find($matchId);

      $updateData = [
        'is_scheduled' => 0,
        'pitch_id' => 0,
        'referee_id' => NULL,
        'hometeam_score' => NULL,
        'awayteam_score' => NULL,
        'match_datetime' => NULL,
        'match_endtime' => NULL,
        'venue_id' => 0,
        'schedule_last_update_date_time' => Carbon::now()->format('Y-m-d H:i:s'),
        'minimum_team_interval_flag' => 0,
        'maximum_team_interval_flag' => 0,
      ];
      $updateResult =  DB::table('temp_fixtures')
            ->where('id', $matchId)
            ->update($updateData);

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

      // Check maximum team interval for matches
      $this->checkMaximumTeamIntervalForMatchesOnCategoryUpdate(['tournamentId' => $tournamentId, 'ageGroupId' => $ageGroupId]);
      
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

      if($matchData->count() == 0){
        return ['status'=> false,'data' => 'Please assign referee properly'];
      }else{
        $result = $matchData->update(['referee_id' => $data['refereeId']]);
        return ['status' => true, 'data' => $matchData];
      }
    }
    public function saveResult($data)
    {
      $tempFixture = TempFixture::where('id', $data['matchId'])->first();
      $competition = Competition::where('id', $tempFixture->competition_id)->first();
      $ageCategory = TournamentCompetationTemplates::where('id', $tempFixture->age_group_id)->first();

      if($data['is_result_override'] == 0) {
        $data['matchStatus'] == null;
        $data['matchWinner'] == null;
      }
    
      $updateData = [
        'referee_id' => $data['refereeId'],
        'hometeam_score' => $data['homeTeamScore'],
        'awayteam_score' => $data['awayTeamScore'],
        'match_status' => $data['matchStatus'],
        'match_winner' => $data['matchWinner'],
        'comments' => $data['comments'],
        'is_result_override' => $data['is_result_override'],
        'home_yellow_cards' => $data['home_yellow_cards'],
        'away_yellow_cards' => $data['away_yellow_cards'],
        'home_red_cards' => $data['home_red_cards'],
        'away_red_cards' => $data['away_red_cards'],
      ];

      $updateResult = TempFixture::where('id', $data['matchId'])
                  ->update($updateData);

      $ageCategory->category_age_color = $data['age_category_color'];
      $ageCategory->save();

      $competition->color_code = strtolower($data['group_color']) == '#ffffff' ? NULL : $data['group_color'];
      $competition->save();

      // TODO : call function to add result
      return $updateResult;
    }

    public function saveAllResults($data)
    {
      $isScoreUpdated = true;
      $matchData = [];
      $tempFixtures = TempFixture::where('id',$data['matchId'])->first();
      if($tempFixtures['hometeam_score'] == $data['homeScore'] && $tempFixtures['awayteam_score'] == $data['awayScore']) {
        $isScoreUpdated = false;
      }
      if($isScoreUpdated === false) {
        return ['status' => true, 'data' => 'Scores updated successfully.', 'match_data' => $matchData, 'is_score_updated' => $isScoreUpdated];
      }
      if($data['score_last_update_date_time'] != $tempFixtures['score_last_update_date_time']) {
        return ['status' => false, 'data' => 'You need to refresh page to get latest updated score.', 'match_data' => $matchData, 'is_score_updated' => $isScoreUpdated, 'tempFixture' => $tempFixtures];
      }

      $matchData['home_team_id'] = $tempFixtures['home_team'];
      $matchData['away_team_id'] = $tempFixtures['away_team'];
      $matchData['age_group_id'] = $tempFixtures['age_group_id'];
      $matchData['competition_id'] = $tempFixtures['competition_id'];
      $updateData = [
        'hometeam_score' => $data['homeScore'],
        'awayteam_score' => $data['awayScore'],
        'score_last_update_date_time' => Carbon::now(),
      ];
      $data = TempFixture::where('id',$data['matchId'])
                ->update($updateData);
      return ['status' => true, 'data' => 'Scores updated successfully.', 'match_data' => $matchData, 'is_score_updated' => $isScoreUpdated];
    }

    public function getMatchDetail($matchId)
    {
      return TempFixture::leftjoin('teams as home_team', function ($join) {
              $join->on('home_team.id', '=', 'temp_fixtures.home_team');
          })
          ->leftjoin('teams as away_team', function ($join) {
              $join->on('away_team.id', '=', 'temp_fixtures.away_team');
          })
          ->with('referee','pitch','competition', 'winnerTeam', 'categoryAge')->select('temp_fixtures.*','home_team.comments as hometeam_comment','away_team.comments as awayteam_comment', 'home_team.shirt_color as  hometeam_shirt_color', 'home_team.shorts_color as  hometeam_shorts_color', 'away_team.shirt_color as  awayteam_shirt_color', 'away_team.shorts_color as  awayteam_shorts_color')->find($matchId);
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
    }

    public function matchUnscheduledFixtures($matchData)
    {
      $conflictedMatchFixtureIds = [];
      $unConflictedMatchFixtureIds = [];
      $conflictedFixtureMatchNumber = [];
      $ageCategories = [];
      $tournamentId = "";

      foreach ($matchData['matchData'] as $key => $value) {
        $tempFixture = TempFixture::find($value['matchId']);
        if($value['scheduleLastUpdateDateTime'] != $tempFixture->schedule_last_update_date_time) {
          $isFixturesUncheduled = false;
          $conflictedFixtureMatchNumber[] = $tempFixture->match_number;
        } else {
          $unConflictedMatchFixtureIds[] = $value['matchId'];
        }

        $ageCategories[] = $tempFixture->age_group_id;
        $tournamentId = $tempFixture->tournament_id;
      }

      if(sizeof($conflictedFixtureMatchNumber) > 0) {
        return ['status' => false, 'message' => 'You need to refresh page to get latest updated fixtures.', 'data' => $tempFixture, 'is_fixture_unscheduled' => $isFixturesUncheduled, 'conflictedFixtureMatchNumber' => $conflictedFixtureMatchNumber];
      }

      $updateMatchUnscheduledRecord = [
        'is_scheduled' => 0,
        'pitch_id' => 0,
        'referee_id' => NULL,
        'hometeam_score' => NULL,
        'awayteam_score' => NULL,
        'match_datetime' => NULL,
        'match_endtime' => NULL,
        'venue_id' => 0,
        'schedule_last_update_date_time' => Carbon::now()->format('Y-m-d H:i:s'),
        'minimum_team_interval_flag' => 0,
        'maximum_team_interval_flag' => 0,
      ];

      $updateMatchFixtures = TempFixture::whereIn('id', $unConflictedMatchFixtureIds)->update($updateMatchUnscheduledRecord);

      foreach($ageCategories as $ageCategoryId) {
        $matchData = array('tournamentId' => $tournamentId, 'ageGroupId' => $ageCategoryId);
        $this->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);
        $this->checkMaximumTeamIntervalForMatchesOnCategoryUpdate($matchData);
      }

      return ['status' => true, 'data' => $updateMatchFixtures, 'conflictedFixtureMatchNumber' => $conflictedFixtureMatchNumber];
    }


    public function saveScheduleMatches($data)
    {
      $conflictedFixtureMatchNumber = null;
      $matchData = TempFixture::find($data['matchId']);

      if($data['scheduleLastUpdateDateTime'] != $matchData->schedule_last_update_date_time) {
        $conflictedFixtureMatchNumber = $matchData->match_number;
      }

      if($conflictedFixtureMatchNumber) {
        return ['status' => false, 'message' => 'You need to refresh page to get latest updated fixtures.', 'match_data' => $matchData, 'conflictedFixtureMatchNumber' => $conflictedFixtureMatchNumber];
      }

      $updateMatchScheduleResult = TempFixture::where('id', $data['matchId'])
                      ->update([
                        'venue_id' => $data['venue_id'],
                        'pitch_id' => $data['pitchId'],
                        'match_datetime' => $data['matchStartDate'],
                        'match_endtime' => $data['matchEndDate'],
                        'is_scheduled' => 1,
                        'schedule_last_update_date_time' => Carbon::now()->format('Y-m-d H:i:s')
                      ]);
  
      return ['status' => true, 'message' => 'Scores updated successfully.', 'match_data' => $updateMatchScheduleResult, 'conflictedFixtureMatchNumber' => $conflictedFixtureMatchNumber];
    }

    public function getScheduledMatch($data)
    {
      $scheduledMatches = TempFixture::where('tournament_id',$data['tournamentId'])->where('is_scheduled',1)->get();
      return $scheduledMatches;
    }

    public function setAutomaticMatchSchedule($data, $allowSchedulingForcefully = false)
    {
      $teamData = TempFixture::join('tournament_competation_template','temp_fixtures.age_group_id','tournament_competation_template.id')->where('temp_fixtures.id',$data['matchId'])->select('tournament_competation_template.minimum_team_interval', 'tournament_competation_template.maximum_team_interval','tournament_competation_template.pitch_size','temp_fixtures.*')->first()->toArray();
      $team_interval =   $teamData['minimum_team_interval'];
      $maximum_team_interval = $teamData['maximum_team_interval'];

      $pitchData = Pitch::find($data['pitchId']);
      $pitchSize = $pitchData->size;
      $ageCategoryPitchSize = $teamData['pitch_size'];
      $setMinimumIntervalFlag = 0;
      $setMaximumIntervalFlag = 0;

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

                })->get()->keyBy('id');

      $homeMaximumIntervalMatchResultCount = collect($matchResultCount);
      $awayMaximumIntervalMatchResultCount = collect($matchResultCount);

      $edStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes(0);
      $edEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes($team_interval);
      $sdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes($team_interval);
      $sdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes(0);
      $matchResultCount = $matchResultCount->filter(function($item) use ($sdStartTime,$sdEndTime,$edStartTime,$edEndTime,$data) {
        if($item['match_endtime'] > $sdStartTime && $item['match_endtime'] <= $sdEndTime) {
          return true;
        }
        if($item['match_datetime'] >= $edStartTime && $item['match_datetime'] < $edEndTime) {
          return true;
        }
        if($item['match_datetime'] > $data['matchStartDate'] && $item['match_datetime'] < $data['matchEndDate']) {
          return true;
        }
        if($item['match_datetime'] >= $data['matchStartDate'] && $item['match_datetime'] < $data['matchEndDate']) {
          return true;
        }

        if($item['match_endtime'] > $data['matchStartDate'] && $item['match_endtime'] <= $data['matchEndDate']) {
          return true;
        }

        return false;
      });



                // ->where(function($query) use ($team_interval,$startTime,$endTime,$data) {
                //     $edStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes(0);
                //     $edEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes($team_interval);
                //     $sdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes($team_interval);
                //     $sdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes(0);
                //     $query->where(function($query2) use ($sdStartTime,$sdEndTime) {
                //       $query2->where('match_endtime','>',$sdStartTime)->where('match_endtime','<=',$sdEndTime);
                //     });
                //     $query->orWhere(function($query3) use ($edStartTime,$edEndTime) {
                //        $query3->where('match_datetime','>=',$edStartTime)->where('match_datetime','<',$edEndTime);
                //     });
                //     $query->orWhere(function($query4) use ($data) {
                //       $query4->where('match_datetime','>',$data['matchStartDate'])->where('match_datetime','<',$data['matchEndDate']);
                //     });
                //     $query->orWhere(function($query5) use ($data) {
                //       $query5->where('match_datetime','>=',$data['matchStartDate'])->where('match_datetime','<',$data['matchEndDate']);
                //     });
                //     $query->orWhere(function($query6) use ($data) {
                //       $query6->where('match_endtime','>',$data['matchStartDate'])->where('match_endtime','<=',$data['matchEndDate']);
                //     });
                //  })
                // ->get();

      if($matchResultCount->count() >0){
        if( $allowSchedulingForcefully == false && ((strpos($teamData['match_number'],"RR1") != false) || (strpos($teamData['match_number'],"PM1" ) != false)) ) {
          return -1;
        }

        $setMinimumIntervalFlag = 1;
      }

      // Maximum interval time check
      $isFirstMatchOfHomeTeam = true;
      $isFirstMatchOfAwayTeam = true;
      if($homeMaximumIntervalMatchResultCount->count() > 0) {
        $maxedStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes(0);
        $maxedEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes($maximum_team_interval);
        $maxsdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes($maximum_team_interval);
        $maxsdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes(0);
        $homeMaximumIntervalMatchResultCount = $homeMaximumIntervalMatchResultCount->filter(function($item) use ($maxsdStartTime,$maxsdEndTime,$maxedStartTime,$maxedEndTime,$data,$teamId,$teamData) {
          $homeTeamCheck = false;
          if($teamId){
            $homeTeamCheck = ($item['home_team'] === $teamData['home_team'] || $item['away_team'] === $teamData['home_team']);
          } else {
            $homeTeamCheck = ($item['home_team_placeholder_name'] === $teamData['home_team_placeholder_name'] || $item['away_team_placeholder_name'] === $teamData['home_team_placeholder_name']);
          }
          if(!$homeTeamCheck) {
            return false;
          }
          if($item['match_endtime'] <= $maxsdEndTime && $item['match_endtime'] >= $maxsdStartTime) {
            return true;
          }
          if($item['match_datetime'] >= $maxedStartTime && $item['match_datetime'] <= $maxedEndTime) {
            return true;
          }
          return false;
        });

        $isFirstMatchOfHomeTeam = false;
      }
      if($awayMaximumIntervalMatchResultCount->count() > 0) {
        $maxedStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes(0);
        $maxedEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchEndDate'])->addMinutes($maximum_team_interval);
        $maxsdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes($maximum_team_interval);
        $maxsdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data['matchStartDate'])->subMinutes(0);
        $awayMaximumIntervalMatchResultCount = $awayMaximumIntervalMatchResultCount->filter(function($item) use ($maxsdStartTime,$maxsdEndTime,$maxedStartTime,$maxedEndTime,$data,$teamId,$teamData) {
          $awayTeamCheck = false;
          if($teamId){
            $awayTeamCheck = ($item['home_team'] === $teamData['away_team'] || $item['away_team'] === $teamData['away_team']);
          } else {
            $awayTeamCheck = ($item['home_team_placeholder_name'] === $teamData['away_team_placeholder_name'] || $item['away_team_placeholder_name'] === $teamData['away_team_placeholder_name']);
          }
          if(!$awayTeamCheck) {
            return false;
          }
          if($item['match_endtime'] <= $maxsdEndTime && $item['match_endtime'] >= $maxsdStartTime) {
            return true;
          }
          if($item['match_datetime'] >= $maxedStartTime && $item['match_datetime'] <= $maxedEndTime) {
            return true;
          }
          return false;
        });

        $isFirstMatchOfAwayTeam = false;
      }
      if( ($homeMaximumIntervalMatchResultCount->count() === 0 && !$isFirstMatchOfHomeTeam) || ($awayMaximumIntervalMatchResultCount->count() === 0 && !$isFirstMatchOfAwayTeam)){
            if( $allowSchedulingForcefully == false && ((strpos($teamData['match_number'], "RR1") != false) || (strpos($teamData['match_number'],"PM1" ) != false)) ) {
                return -3;
            }
            $setMaximumIntervalFlag = 1;
      }

      $updateData = [
        'venue_id' => $pitchData->venue_id,
        'pitch_id' => $data['pitchId'],
        'match_datetime' => $data['matchStartDate'],
        'match_endtime' => $data['matchEndDate'],
        'is_scheduled' => 1,
        'minimum_team_interval_flag' => $setMinimumIntervalFlag,
        'maximum_team_interval_flag' => $setMaximumIntervalFlag,
      ];

      $updateResult = DB::table('temp_fixtures')
          ->where('id', $data['matchId'])
          ->update($updateData);

      $matchData = array('teams'=>$teams,'tournamentId'=>$data['tournamentId'],'ageGroupId'=>$teamData['age_group_id'],'teamId'=>$teamId);
      $matchresult =  $this->checkTeamIntervalforMatches($matchData);

      return $updateResult;
    }

    public function checkMaximumTeamIntervalForMatchesOnCategoryUpdate($matchData) {
      $matches = [];
      $matches = DB::table('temp_fixtures')
              ->where('tournament_id','=',$matchData['tournamentId'])
              ->where('age_group_id','=',$matchData['ageGroupId'])
               ->where('is_scheduled',1)
              ->get()->toArray();

        return  $this->findMaximumMatchInterval($matches);
    }

    public function findMaximumMatchInterval($matches='') {
      if(count($matches) > 0){
        $setFlag=array();
        $unsetFlag=array();
        foreach ($matches as $key => $match) {
          if($this->setMaximumTeamIntervalFlagFixture($match)){
            $setFlag[] = $match->id;
          }else{
            $unsetFlag[] = $match->id;
          }
        }
        // echo "<pre>"; print_r($setFlag); echo "</pre>";
        // dd($setFlag,$unsetFlag);
        TempFixture::whereIn('id',$setFlag)->update(['maximum_team_interval_flag' => 1]);
        TempFixture::whereIn('id',$unsetFlag)->update(['maximum_team_interval_flag' => 0]);
         return true;
      }
    }

    public function setMaximumTeamIntervalFlagFixture($data='') {
      $teamData = TempFixture::join('tournament_competation_template','temp_fixtures.age_group_id','tournament_competation_template.id')->where('temp_fixtures.id',$data->id)->select('tournament_competation_template.maximum_team_interval','temp_fixtures.*')->first()->toArray();
      $maximum_team_interval =  $teamData['maximum_team_interval'];

      if($maximum_team_interval == 0) {
        return false;
      }

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
        })->get()->keyBy('id');

      $matchResultCount = collect($matchResultCount);
      $homeMaximumIntervalMatchResultCount = collect($matchResultCount);
      $awayMaximumIntervalMatchResultCount = collect($matchResultCount);

      // Maximum interval time check
      $isFirstMatchOfHomeTeam = true;
      $isFirstMatchOfAwayTeam = true;
      if($homeMaximumIntervalMatchResultCount->count() > 0) {
        $maxedStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_endtime)->addMinutes(0);
        $maxedEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_endtime)->addMinutes($maximum_team_interval);
        $maxsdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes($maximum_team_interval);
        $maxsdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes(0);
        $homeMaximumIntervalMatchResultCount = $homeMaximumIntervalMatchResultCount->filter(function($item) use ($maxsdStartTime,$maxsdEndTime,$maxedStartTime,$maxedEndTime,$teamId,$teamData, &$isFirstMatchOfHomeTeam) {
          $homeTeamCheck = false;
          if($teamId){
            $homeTeamCheck = ($item['home_team'] === $teamData['home_team'] || $item['away_team'] === $teamData['home_team']);
          } else {
            $homeTeamCheck = ($item['home_team_placeholder_name'] === $teamData['home_team_placeholder_name'] || $item['away_team_placeholder_name'] === $teamData['home_team_placeholder_name']);
          }
          if(!$homeTeamCheck) {
            return false;
          } else {
            $isFirstMatchOfHomeTeam = false;
          }
          if($item['match_endtime'] <= $maxsdEndTime && $item['match_endtime'] >= $maxsdStartTime) {
            return true;
          }
          if($item['match_datetime'] >= $maxedStartTime && $item['match_datetime'] <= $maxedEndTime) {
            return true;
          }
          return false;
        });
      }
      if($awayMaximumIntervalMatchResultCount->count() > 0) {
        $maxedStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_endtime)->addMinutes(0);
        $maxedEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_endtime)->addMinutes($maximum_team_interval);
        $maxsdStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes($maximum_team_interval);
        $maxsdEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $data->match_datetime)->subMinutes(0);
        $awayMaximumIntervalMatchResultCount = $awayMaximumIntervalMatchResultCount->filter(function($item) use ($maxsdStartTime,$maxsdEndTime,$maxedStartTime,$maxedEndTime,$teamId,$teamData, &$isFirstMatchOfAwayTeam) {
          $awayTeamCheck = false;
          if($teamId){
            $awayTeamCheck = ($item['home_team'] === $teamData['away_team'] || $item['away_team'] === $teamData['away_team']);
          } else {
            $awayTeamCheck = ($item['home_team_placeholder_name'] === $teamData['away_team_placeholder_name'] || $item['away_team_placeholder_name'] === $teamData['away_team_placeholder_name']);
          }
          if(!$awayTeamCheck) {
            return false;
          } else {
            $isFirstMatchOfAwayTeam = false;
          }
          if($item['match_endtime'] <= $maxsdEndTime && $item['match_endtime'] >= $maxsdStartTime) {
            return true;
          }
          if($item['match_datetime'] >= $maxedStartTime && $item['match_datetime'] <= $maxedEndTime) {
            return true;
          }
          return false;
        });
      }
      if( ($homeMaximumIntervalMatchResultCount->count() === 0 && !$isFirstMatchOfHomeTeam) || ($awayMaximumIntervalMatchResultCount->count() === 0 && !$isFirstMatchOfAwayTeam)){
            return true;
      }
      return false;
    }
}
