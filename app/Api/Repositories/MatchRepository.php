<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\MatchResult;
use Laraspace\Models\Competition;
use Laraspace\Models\Fixture;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Pitch;
use Laraspace\Models\PitchUnavailable;
use Laraspace\Models\Team;
use DB;

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
                'tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name',
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
            ->select('temp_fixtures.id as fid','temp_fixtures.match_number as match_number' ,'competitions.competation_type as round' ,'competitions.name as competation_name' , 'competitions.team_size as team_size','temp_fixtures.match_datetime','temp_fixtures.match_endtime','temp_fixtures.match_status','temp_fixtures.match_winner',
              'match_winner.name as MatchWinner',
                'venues.id as venueId', 'competitions.id as competitionId',
                'venues.venue_coordinates as venueCoordinates',
                'pitches.type as pitchType','venues.address1 as venueaddress',
                'venues.state as venueState','venues.county as venueCounty',
                'venues.city as venueCity','venues.country as venueCountry',
                'venues.postcode as venuePostcode',
                'tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name','temp_fixtures.referee_id as referee_id','referee.first_name as first_name','referee.last_name as last_name','home_team.name as HomeTeam','away_team.name as AwayTeam',
                'temp_fixtures.home_team as Home_id','temp_fixtures.away_team as Away_id',
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
                'home_team.name as HomeTeam','away_team.name as AwayTeam',
                'tournament_competation_template.game_duration_RR',
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
             $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime',
              '=',$mysql_date_string);

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

          // dd($tournamentData);
            // Todo Added Condition For Filtering Purpose on Pitch Planner
          if(isset($tournamentData['fiterEnable'])){
            if(isset($tournamentData['filterKey']) && $tournamentData['filterKey'] !='') {
              switch($tournamentData['filterKey']) {
                case 'location':
                 $reportQuery =  $reportQuery->where('temp_fixtures.venue_id','=',$tournamentData['filterValue']);
                 break;
                case 'age_category':
                 $reportQuery =  $reportQuery->where('tournament_competation_template.id','=',$tournamentData['filterValue']);
                 break;
                  case 'team':
                   $team = $tournamentData['filterValue'];
                   $reportQuery = $reportQuery->where(function ($query) use($team)
                        {
                          $query->where('temp_fixtures.home_team',$team)
                          ->orWhere('temp_fixtures.away_team',$team);
                        }
                      );
                // $reportQuery =  $reportQuery->where('tournament_competation_template.id','=',$tournamentData['filterValue']);
                 break;
                 case 'competation_group':
                    $reportQuery =  $reportQuery->where('temp_fixtures.competition_id','=',$tournamentData['filterValue']);
                  break;
              }
            }
          }

          // dd($reportQuery->get());
        return $reportQuery->get();
    }
    private function getTeamsForClub($club_id, $tournamentId)
    {
      return Team::where('club_id','=',$club_id)->where('tournament_id','=',$tournamentId)->pluck('teams.id')->toArray();
    }
    public function getStanding($tournamentData)
    {
        //print_r($tournamentData);
        //print_r($tournamentData);exit;
        //$url = getenv('S3_URL');
        $reportQuery = DB::table('match_standing')
          ->leftjoin('teams', 'match_standing.team_id', '=', 'teams.id')
          ->leftjoin('countries', 'teams.country_id', '=', 'countries.id')
          ->leftjoin('competitions', 'match_standing.competition_id', '=', 'competitions.id')
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
          if(isset($tournamentData['tournamentId']) &&	$tournamentData['tournamentId'] !== '')
          {
          $reportQuery = $reportQuery->where('match_standing.tournament_id', $tournamentData['tournamentId']);
          }

          $reportQuery->orderBy('match_standing.points','desc')
                      ->orderBy('GoalDifference','desc')
                      ->orderBy('match_standing.goal_for','desc');
           //print_r($reportQuery->get());exit;
          return $reportQuery->get();
    }
    public function getDrawTable($tournamentData){

       $isMatchExist = false;
       $totalMatches = DB::table('temp_fixtures')
                ->where('temp_fixtures.tournament_id',$tournamentData['tournamentId'])
                ->where('temp_fixtures.competition_id',$tournamentData['competationId'])
                ->where('temp_fixtures.is_scheduled','=',1)
                ->select(
                DB::raw('CONCAT(temp_fixtures.hometeam_score, "-", temp_fixtures.awayteam_score) AS scoresFix'),
                DB::raw('CONCAT(temp_fixtures.home_team, "-", temp_fixtures.away_team) AS teamsFix'),
                'temp_fixtures.match_datetime as matchDateTime'
                  ) ->get();
      $matchArr = array();
      $matchDate = array();
      //print_r($teamData);exit;

      if(!$totalMatches->isEmpty() && $totalMatches->count() > 0)
      {
        $isMatchExist = true;
        foreach($totalMatches as $data) {

          $newkey = sprintf('%s',$data->teamsFix);
          $matchArr[$data->teamsFix] = $data->scoresFix;
          $matchDate[$data->teamsFix] = $data->matchDateTime;
        }
      }  else {
          $errorMsg= 'No Matches';
      }
     // print_r($);exit;
      $teamData = DB::table('teams')
                    ->leftjoin('countries', 'teams.country_id', '=', 'countries.id')
                    ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
                    ->leftjoin('competitions', 'competitions.id', '=', 'teams.competation_id')
                    ->select('teams.id as TeamId','teams.name as TeamName','competitions.*','countries.logo as TeamLogo',
                      'countries.country_flag as TeamCountryFlag'
                      )
                    ->where('teams.tournament_id',$tournamentData['tournamentId'])
                    ->where('competitions.id',$tournamentData['competationId'])
                    ->get();

      $numTeamsArray = array();
      $teamDetails=array();

      if(!$teamData->isEmpty() && $teamData->count() > 0)
      {

        foreach($teamData as $Tdata) {
          $numTeamsArray[]=$Tdata->TeamId;
          $teamDetails[$Tdata->TeamId]['TeamName']=$Tdata->TeamName;
          $teamDetails[$Tdata->TeamId]['TeamFlag']=$Tdata->TeamLogo;
          $teamDetails[$Tdata->TeamId]['TeamCountryFlag']=$Tdata->TeamCountryFlag;
        }
      } else {

        $errorMsg= 'No Team Assigned Yet';
        return $errorMsg;
      }

      //$table=array();
      $htmlData='';
      $arr1=array();
      //print_r($numTeamsArray);
      //print_r($matchArr);
     //exit;
      $matchNotExist=false;
      for($i=0;$i<count($numTeamsArray);$i++)
      {
        $arr1[$i]['id'] = $numTeamsArray[$i];
        $arr1[$i]['TeamName'] = $teamDetails[$numTeamsArray[$i]]['TeamName'];
        $arr1[$i]['TeamFlag'] = $teamDetails[$numTeamsArray[$i]]['TeamFlag'];
        $arr1[$i]['TeamCountryFlag'] = $teamDetails[$numTeamsArray[$i]]['TeamCountryFlag'];
        for($j=0;$j<count($numTeamsArray);$j++)
        {
          // Here we check if Result is Declare or not
          if($isMatchExist ==  true)
          {

            if($i==$j)
            {
              $arr1[$i]['matches'][$j] ='Y';
              // we set another variable for same team
            }
            else
            {
              $teamId = $numTeamsArray[$i];
              //echo 'Team id is'.$teamId;
              $rowKey=$numTeamsArray[$i];
              $colKey=$numTeamsArray[$j];

              // Now here we explode it and check
              if($teamId == $rowKey)
              {
               // print_r($matchArr);exit;
                if(array_key_exists($rowKey.'-'.$colKey, $matchArr))
                {
                  $arr1[$i]['matches'][$j]['score']= $matchArr[$rowKey.'-'.$colKey];
                  $arr1[$i]['matches'][$j]['date']= $matchDate[$rowKey.'-'.$colKey];
                  $arr1[$i]['matches'][$j]['home']= $rowKey;
                  $arr1[$i]['matches'][$j]['away']= $colKey;
                }
                else
                {
                // Flip it

                /*  if(isset($matchArr[$colKey.'-'.$rowKey])){
                       $nwArr = explode('-',$matchArr[$colKey.'-'.$rowKey]);
                       $arr1[$i]['matches'][$j]['score']= $nwArr[1].'-'.$nwArr[0];
                       $arr1[$i]['matches'][$j]['home']= $rowKey;
                      $arr1[$i]['matches'][$j]['away']= $colKey;
                  } */
                  $arr1[$i]['matches'][$j]= 'X';
                }
              }
            }
         }
         // Match is Not Exist Yet
         // TODO : Not needed
         else
         {

            $matchNotExist=true;
            $arr1[$i]['matches'][$j]['score'] = '';

         }
        }
      }

      if($matchNotExist == true) {
        return array();
      }
   //   print_r($arr1);exit;
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

    public function setMatchSchedule($data)
    {
      $pitchData = Pitch::find($data['pitchId']);
      $updateData = [
        'venue_id' => $pitchData->venue_id,
        'pitch_id' => $data['pitchId'],
        'match_datetime' => $data['matchStartDate'],
        'match_endtime' => $data['matchEndDate'],
        'is_scheduled' => 1,
      ];
     return DB::table('temp_fixtures')
            ->where('id', $data['matchId'])
            ->update($updateData);
    }
    public function matchUnschedule($matchId)
    {
      $updateData = [
        'is_scheduled' => 0,
      ];
     return DB::table('temp_fixtures')
            ->where('id', $matchId)
            ->update($updateData);
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
      return TempFixture::where('id',$matchId)
                  ->update(['referee_id' => '0']);
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
        return TempFixture::leftjoin('teams as winner_team', function ($join) {
                $join->on('winner_team.id', '=', 'temp_fixtures.match_winner');
            })->with('referee','pitch')->find($matchId);
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
}
