<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\MatchResult;
use Laraspace\Models\Competition;
use Laraspace\Models\Fixture;

use DB;

class MatchRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('match_results');
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

    public function getDraws($tournamentId) {
        return Competition::find($tournamentId)->get();
    }
    public function getFixtures($tournamentData) {

         $reportQuery = DB::table('fixtures')
            // ->Join('tournament', 'fixture.tournament_id', '=', 'tournament.id')
            ->leftjoin('venues', 'fixtures.venue_id', '=', 'venues.id')
            ->leftjoin('teams as home_team', function ($join) {
                $join->on('home_team.id', '=', 'fixtures.home_team');
            })
            ->leftjoin('teams as away_team', function ($join) {
                $join->on('away_team.id', '=', 'fixtures.away_team');
            })
            ->leftjoin('countries as HomeFlag', 'home_team.country_id', '=', 
                'HomeFlag.id')
            ->leftjoin('countries as AwayFlag', 'away_team.country_id', '=', 
                'AwayFlag.id')
            ->leftjoin('pitches', 'fixtures.pitch_id', '=', 'pitches.id')
            ->leftjoin('competitions', 'competitions.id', '=', 'fixtures.competition_id')
            ->leftjoin('tournament_competation_template', 
                'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
           
            ->leftjoin('match_results', 'fixtures.match_result_id', '=', 'match_results.id')
            ->leftjoin('referee', 'referee.id', '=', 'match_results.referee_id')
            ->groupBy('fixtures.id')
            ->select('fixtures.id as fid','competitions.name as competation_name' ,'fixtures.match_datetime',
                'venues.id as venueId', 'competitions.id as competitionId',
                'tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name',
                'home_team.name as HomeTeam','away_team.name as AwayTeam',
                'fixtures.home_team as Home_id','fixtures.away_team as Away_id','HomeFlag.logo as HomeFlagLogo','AwayFlag.logo as AwayFlagLogo','fixtures.hometeam_score as homeScore',
                'fixtures.awayteam_score as AwayScore',
                'fixtures.pitch_id as pitchId',
                'home_team.name as HomeTeam','away_team.name as AwayTeam',
                DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game')
                )
            ->where('fixtures.tournament_id', $tournamentData['tournamentId']);

          if(isset($tournamentData['pitchId']) && $tournamentData['pitchId'] !== '' )
          {
            $reportQuery = $reportQuery->where('fixtures.pitch_id',$tournamentData['pitchId']);

          }
          
          if(isset($tournamentData['teamId']) && $tournamentData['teamId'] !== '')
          {
            
            $reportQuery = $reportQuery->where('fixtures.home_team',$tournamentData['teamId'])
                ->orWhere('fixtures.away_team',$tournamentData['teamId']);
          } 
          if(isset($tournamentData['competitionId']) && $tournamentData['competitionId'] !== '')
          {
            
            $reportQuery = $reportQuery->where('fixtures.competition_id',
                $tournamentData['competitionId']);               
          }  

        return $reportQuery->get();
    }
       
}
