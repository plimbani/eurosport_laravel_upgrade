<?php

namespace Laraspace\Api\Services;

use DB;
use Laraspace\Api\Contracts\MatchContract;
use Validator;
use Laraspace\Model\Role;

class MatchService implements MatchContract
{
    public function __construct()
    {
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
    }

    public function getAllMatches()
    {
        return $this->matchRepoObj->getAllMatches();
    }

    /**
     * create New Match.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function createMatch($data)
    {
        $data = $data->all();
        $data = $this->matchRepoObj->createMatch($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Match.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        $data = $this->matchRepoObj->edit($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Match.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function deleteMatch($deleteId)
    {
        $matchRes = $this->matchRepoObj->getMatchFromId($deleteId)->delete();
        if ($matchRes) {
            return ['code' => '200', 'message' => 'Match Sucessfully Deleted'];
        }
    }
    /**
     * Get Draws Details For Competation.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getDraws($data)
    {
        $tournamentId = $data['tournamentId'];

        $matchResData = $this->matchRepoObj->getDraws($data);
        $timeStamp = $this->matchRepoObj->getLastUpdateValue($data['tournamentId']);

        if ($matchResData) {
            return ['status_code' => '200', 'data' => $matchResData,
            'message' => 'Draw data',
            'updatedValue' => $timeStamp,
            ];
        }
    }
    /**
     * Get Fixtures Details For Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getFixtures($data)
    {
        $data = $data->all();

        // $fixtureResData = $this->matchRepoObj->getFixtures($data['tournamentData']);
        $fixtureResData = $this->matchRepoObj->getTempFixtures($data['tournamentData']);
        if ($fixtureResData) {
            return ['status_code' => '200', 'data' => $fixtureResData,'message' => 'Match Fixture data'];
        }
    }
    /**
     * Get Standing  Details For Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getStanding($data)
    {
        $data = $data->all();

        $standingResData = $this->matchRepoObj->getStanding($data['tournamentData']);

        if ($standingResData) {
            return ['status_code' => '200', 'data' => $standingResData,'message' => 'Match Standing data'];
        }
    }
    /**
     * Get Draw Table  Details For Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getDrawTable($Data)
    {
       $Data = $Data->all();

       $drawTableResData = $this->matchRepoObj->getDrawTable($Data['tournamentData']);

        if (is_array($drawTableResData)) {
            return ['status_code' => '200', 'data' => $drawTableResData, 'message' => 'Match Draw data'];
        } else {
            return ['status_code' => '300', 'message' => $drawTableResData];
        }
    }

    public function scheduleMatch($matchData) {
        $scheduledResult = $this->matchRepoObj->setMatchSchedule($matchData->all()['matchData']);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Match scheduled successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }

    public function getAllScheduledMatch($matchData) {
        $scheduledResult = $this->matchRepoObj->getAllScheduledMatches($matchData->all());
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Match scheduled successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }
    public function getMatchDetail($matchData) {

        $matchResult = $this->matchRepoObj->getMatchDetail($matchData->all()['matchId']);
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $matchResult];
        } else {
            return ['status_code' => '300'];
        }
    }
    public function removeAssignedReferee($matchData) {
        $matchResult = $this->matchRepoObj->removeAssignedReferee($matchData->all()['data']);
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $matchResult];
        } else {
            return ['status_code' => '300'];
        }
    }
    public function assignReferee($matchData) {
        $matchResult = $this->matchRepoObj->assignReferee($matchData->all()['data']);
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $matchResult];
        } else {
            return ['status_code' => '300'];
        }
    }
    public function saveResult($matchData) {
        $matchResult = $this->matchRepoObj->saveResult($matchData->all()['matchData']);
        $this->calculateCupLeagueTable($matchData->all()['matchData']['matchId']);
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $matchResult];
        } else {
            return ['status_code' => '300'];
        }
    }
    public function unscheduleMatch($matchData) {
        $scheduledResult = $this->matchRepoObj->matchUnschedule($matchData->all()['matchData']);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Match scheduled successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }
    public function saveUnavailableBlock($matchData) {

        $scheduledResult = $this->matchRepoObj->setUnavailableBlock($matchData->all()['matchData']);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Block added successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }
    public function getUnavailableBlock($matchData) {
        $scheduledResult = $this->matchRepoObj->getUnavailableBlock($matchData->all()['matchData']);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Block added successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }
    public function removeBlock($block_id) {
         $scheduledResult = $this->matchRepoObj->removeBlock($block_id);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Block added successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }
    public function updateScore($matchData) {

       $scoreUpdate = $this->matchRepoObj->updateScore($matchData->all()['matchData']);
       $this->calculateCupLeagueTable($matchData->all()['matchData']['matchId']);
        if ($scoreUpdate) {
            return ['status_code' => '200', 'data' => $scoreUpdate, 'message' => 'Score updated successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scoreUpdate];
        }
    }

    public function CalculateLeaguePoints($matchData)
    {
      $this->calculateCupLeagueTable($matchData['matchId']);
      // Updated Value
      $homeScore = $matchData['home_score'];
      $awayScore = $matchData['away_score'];
      // Now calculate and update the values with Some Rules
      // It gives SingLe record
      $record = DB::table('temp_fixtures')
                ->where('id', $matchData['matchId'])->get();

      $record = $record[0];
      // Now find all matches for that CompetationId which have team assigned
      $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$record->tournament_id)
                ->where('competition_id','=',$record->competition_id)->get();
      echo '<pre>';
      print_r($matches);


      exit;
      $homeTeamId =    $record->home_team;
      $awayTeamId =    $record->away_team;
      $competationId = $record->competition_id;
      $tournamentId =  $record->tournament_id;

      // Find record in match Standing we find two records
      $teamsIds = $homeTeamId.','.$awayTeamId;

      $standingData = DB::table('match_standing')
      ->where('tournament_id','=',$tournamentId)
      ->where('competition_id','=',$competationId)
      ->whereIn('team_id',[$homeTeamId,$awayTeamId])
      ->get();

      // here we check the status of game if homeTeam is winner
      if($homeScore > $awayScore)
      {
        $winner = $homeTeamId;
        $loss = $awayTeamId;
        // add 3 points for winning
        // add 1 for won
        // add home score goal_for
        // add awayscore for goal_against
      }

      // For Loss Scnerio
      if($homeScore < $awayScore)
      {

         $winner = $awayTeamId;
         $loss = $homeTeamId;
         // add 3 poins for winning
         // add 1 for won
         // add awayScore goal_for
         // add homeScore for goal_against
         // update the two record
         $matchStandingData = array();
         foreach($standingData as $standData) {
          // if winner
           if($standData->team_id == $winner) {
            // before update check if new score and enterd score are not same

            $matchStandingData['points'] = $standData->points + 3;
            $matchStandingData['won'] = $standData->won + 1;
            $matchStandingData['goal_for'] = $standData->goal_for + $awayScore;
            $matchStandingData['goal_against'] = $standData->goal_against + $homeScore;

            $standUpdate = DB::table('match_standing')
            ->where('id','=',$standData->id)
            ->update($matchStandingData);

           }
           // if loss
           else {

            $matchStandingData['lost'] = $standData->lost + 1;
            $matchStandingData['goal_for'] = $standData->goal_for + $homeScore;
            $matchStandingData['goal_against'] = $standData->goal_against + $awayScore;
            $standUpdate = DB::table('match_standing')
            ->where('id','=',$standData->id)
            ->update($matchStandingData);

           }
           unset($matchStandingData);
         }

      }

      // For Draw Scenerio
      if($homeScore == $awayScore) {
        // add 1 point for draw
        // add 1 for draws
        // add home score goal_for
        // add awayscore goal_against
      }

      echo 'hi';exit;
    }

     public function calculateCupLeagueTable($id){

        // $fix1 = temp_fixtures Single record
        //$leagueId = $this->Session->read('League.id');
        // competationId
        $singleFixture = DB::table('temp_fixtures')->select('temp_fixtures.*')->where('id','=',$id)->get();

        $fix1=array();

        foreach($singleFixture as $singleFxture)
        {
          $fix1['CupFixture']['cupcompetition'] = $singleFxture->competition_id;
          $fix1['CupFixture']['hometeam'] = $singleFxture->home_team;
          $fix1['CupFixture']['awayteam'] = $singleFxture->away_team;
          $fix1['CupFixture']['tournamentId'] = $singleFxture->tournament_id;

        }

        if( $fix1['CupFixture']['hometeam'] == 0 || $fix1['CupFixture']['awayteam'] == 0)
        {
          return false;
        }
        // Set the fix1 single record team

        $cup_competition_id = $fix1['CupFixture']['cupcompetition'];

        //$this->CupCompetition->id = $cup_competition_id;
        //$comType = $this->CupCompetition->field('competition_type');
        $home = false;
        // Home team Id, away team id
        $home_tema_id[] = $fix1['CupFixture']['hometeam'];
        $away_team_id[] = $fix1['CupFixture']['awayteam'];
        // merge it
        $findTeams = array_merge($home_tema_id,$away_team_id);
        $comType = 'C';
        if ($comType == 'C'){
            // assign zero value $fix1['CupFixture']['hometeam'] is home team id
            //$home_team_leaguetable[$fix1['CupFixture']['hometeam']] = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,'home_goal' => 0);
            // assign zero value $fix1['CupFixture']['awayteam'] is away team id
            //$away_team_leaguetable[$fix1['CupFixture']['awayteam']] = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,'away_goal' => 0);

            // Find fixtures data or can say all data for that group:Note: for round 1
           /* $comp_fixtures = $this->CupFixture->find('all', array('conditions' => array(
                'fk_leagueId' => $leagueId ,
                'cupcompetition' => $fix1['CupFixture']['cupcompetition'],
                'round' => 'Round 1',
                'OR' => array(
                            array('CupFixture.hometeam' => $findTeams),
                            array('CupFixture.awayteam' => $findTeams)
                        )
                )));*/
            // change the query to get all fixtures for that competations

            $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])
                ->where('competition_id','=',$fix1['CupFixture']['cupcompetition'])
                ->whereIn('away_team',$findTeams)
                ->ORwhereIn('home_team',$findTeams)
                ->get();
            $fixtu = array();
            foreach($matches as $key1=>$match)
            {
              $fixtu[$key1]['CupFixture']['hometeamscore'] = $match->hometeam_score;
              $fixtu[$key1]['CupFixture']['awayteamscore'] = $match->awayteam_score;
              $fixtu[$key1]['CupFixture']['awayteamscore'] = $match->awayteam_score;
              $fixtu[$key1]['CupFixture']['hometeam'] = $match->home_team;
              $fixtu[$key1]['CupFixture']['awayteam'] = $match->away_team;
              $fixtu[$key1]['CupFixture']['HomeTeamScoreAfterExtraTime']='';
            }
            $comp_fixtures = $fixtu;
            $ageGroupList = array();
            //if ( !array_key_exists($fix['CupFixture']['hometeam'], $ageGroupList) ) {
                $ageGroupList[$fix1['CupFixture']['hometeam']] = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,'home_goal' => 0,'away_goal'=>0);
            //}

            //if ( !array_key_exists($fix['CupFixture']['awayteam'], $ageGroupList) ) {
                $ageGroupList[$fix1['CupFixture']['awayteam']] = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,'away_goal' => 0,'home_goal'=>0);
            //}
            // iterate fixtures data

            foreach ($comp_fixtures as $key => $fix) {
                // Initilize winner as Not Declare
                $winnerTeam = 'nd';

                // check temp_fixtures homescore , awayscore is not null and not abandone
                if($fix['CupFixture']['hometeamscore'] != '' && $fix['CupFixture']['awayteamscore'] != '' && empty($fix['CupFixture']['Abandoned'])){

                     // check the equal score condition
                    if($fix['CupFixture']['hometeamscore']  == $fix['CupFixture']['awayteamscore']){
                        // check if  ExtraTime for Home and awayScore
                        if ($fix['CupFixture']['HomeTeamScoreAfterExtraTime'] != '' && $fix['CupFixture']['AwayTeamScoreAfterExtraTime'] != '' ){

                            if($fix['CupFixture']['HomeTeamScoreAfterExtraTime'] == $fix['CupFixture']['AwayTeamScoreAfterExtraTime']){
                                if ($fix['CupFixture']['HomeTeamScoreAfterPen'] != '' && $fix['CupFixture']['AwayTeamScoreAfterPen'] != ''){
                                    if($fix['CupFixture']['HomeTeamScoreAfterPen'] == $fix['CupFixture']['AwayTeamScoreAfterPen']){
                                        $winnerTeam = -1;
                                    } else {
                                        if($fix['CupFixture']['HomeTeamScoreAfterPen'] > $fix['CupFixture']['AwayTeamScoreAfterPen']){
                                            $winnerTeam = $fix['CupFixture']['hometeam'];
                                            $home = true;
                                        } else {
                                            $winnerTeam = $fix['CupFixture']['awayteam'];
                                        }
                                    }
                                }else{
                                    $winnerTeam = -1;
                                }
                            } else {
                              // Hometeamscore extratime is greter than awayteamscore
                                if($fix['CupFixture']['HomeTeamScoreAfterExtraTime'] > $fix['CupFixture']['AwayTeamScoreAfterExtraTime']){
                                    $winnerTeam = $fix['CupFixture']['hometeam'];
                                    $home = true;
                                } else {
                                    $winnerTeam = $fix['CupFixture']['awayteam'];
                                }
                            }
                        }else{
                            $winnerTeam = -1;
                        }
                    }
                    // if its not equal
                    // homescore > awayscore : winner-hometeam
                    // else winner -awayteam
                    else {
                        if($fix['CupFixture']['hometeamscore'] > $fix['CupFixture']['awayteamscore']){
                            $winnerTeam = $fix['CupFixture']['hometeam'];
                            $home = true;
                        } else {
                            $winnerTeam = $fix['CupFixture']['awayteam'];
                        }
                    }
                } else {
                    // If match is Abandoned
                    // check if its HomeWin or AwayWIn if its draw WinnerTeam is -1
                    if(!empty($fix['CupFixture']['Abandoned'])){
                        if($fix['CupFixture']['Abandoned'] == 'HomeWin'){
                            $winnerTeam = $fix['CupFixture']['hometeam'];
                        }

                        if($fix['CupFixture']['Abandoned'] == 'AwayWin'){
                            $winnerTeam = $fix['CupFixture']['awayteam'];
                        }

                        if($fix['CupFixture']['Abandoned'] == 'Draw'){
                            $winnerTeam = -1;
                        }
                    }
                }

                // if WinnerTeam is Not Declare
                if ( $winnerTeam != 'nd') {
                    // Yes its not declare
                    if ( $winnerTeam == -1) {
                        // check temp_fixtures value with single record value
                        // 1. check if has same Home id
                        if ( $fix1['CupFixture']['hometeam'] == $fix['CupFixture']['hometeam']) {
                            // we will increase played option of ageGroupList Array for that HomeTeam
                            $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                            $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] + 1;
                            // Increase HomeTeamScore
                            if ($fix['CupFixture']['hometeamscore'] != '') {
                               // increase Homegoal for hometeam
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                            }
                            // increase awayteam goal
                            if ($fix['CupFixture']['awayteamscore'] != '') {
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                            }
                        }else{
                            // if matchfixtures record away team is single record hometeam
                            if ( $fix1['CupFixture']['hometeam'] == $fix['CupFixture']['awayteam'] ) {
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] =
                                (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] + 1;

                                // if awayteamscore is not null
                                // add homegoal + awayteamscore
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                                // if hometeamscore is not null
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                            }
                        }
                       // check temp_fixtures value with single record value
                        // 2. check if has same awayteam
                        if ( $fix1['CupFixture']['awayteam'] == $fix['CupFixture']['awayteam']) {
                            $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;
                            $ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'] + 1;
                            // Add home_goal Score for awayTeam
                            if ($fix['CupFixture']['awayteamscore'] != '') {
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] =
                                (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] +
                                (int)$fix['CupFixture']['awayteamscore'];
                            }
                            // Add away_goal score for hometeam
                            if ($fix['CupFixture']['hometeamscore'] != '') {
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] =
                                (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] +
                                (int)$fix['CupFixture']['hometeamscore'];
                            }
                        } else {
                            // if awayteam for singlerecord is same as hometeam for iterate
                            if ( $fix1['CupFixture']['awayteam'] == $fix['CupFixture']['hometeam'] ) {
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] =
                                (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'] + 1;

                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }

                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                            }
                        }

                    }else{
                        // 1 if home team is Winner
                        if ( $winnerTeam == $fix1['CupFixture']['hometeam']) {
                            $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] =
                            (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                            //$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;
                            $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Won'] + 1;
                            // if singlerecord hometeam =  iterate hometeam
                            if ( $fix1['CupFixture']['hometeam'] == $fix['CupFixture']['hometeam']) {

                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                            } else {
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                            }
                            //$ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'] + 1;
                        } else {
                            if ( $fix1['CupFixture']['hometeam'] == $fix['CupFixture']['hometeam']) {
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Lost']+ 1;

                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }

                                if ($fix['CupFixture']['awayteamscore'] != '') {

                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];

                                }
                            }

                            if ( $fix1['CupFixture']['hometeam'] == $fix['CupFixture']['awayteam']) {
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                                $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Lost']+ 1;

                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                            }
                        }

                        if ( $winnerTeam == $fix1['CupFixture']['awayteam']) {
                            $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;
                            //$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                            $ageGroupList[$fix1['CupFixture']['awayteam']]['Won'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Won'] + 1;

                            if ( $fix1['CupFixture']['awayteam'] == $fix['CupFixture']['hometeam']) {
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                            } else {
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                            }
                            //$ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Lost']+ 1;
                        } else {
                            if ( $fix1['CupFixture']['awayteam'] == $fix['CupFixture']['awayteam']) {
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Lost']+ 1;
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                            }

                            if ( $fix1['CupFixture']['awayteam'] == $fix['CupFixture']['hometeam']) {
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;
                                $ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Lost']+ 1;

                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                                }
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                            }
                        }


                    }
                }
            }

            //print_r($ageGroupList);exit;
            //echo 'hello';exit;
            //$this->loadModel('CupLeagueTable');
            // check in standing table for find hometeam
           // $homeTeamExist = $this->CupLeagueTable->find('first', array('conditions' => array('comp_id' => $cup_competition_id, 'team_id' => $fix1['CupFixture']['hometeam'])));
            $homeTeamExist = DB::table('match_standing')
                            ->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])
                            ->where('competition_id','=',$cup_competition_id)
                            ->where('team_id',$fix1['CupFixture']['hometeam'])
                            ->get()->first();
            $winningPoints = 3;$drawPoints = 1;$losePoints = 0;
            $sendData = array();
            // if its exist update it
            if ( count($homeTeamExist) > 0){
                //$this->CupLeagueTable->id = $homeTeamExist->id;
                $data = array();
                // TODO : remains
                //$data['points'] =

                $data['points'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'] * $winningPoints + $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] * $drawPoints + $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'] * $losePoints;
                $data['played'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'];
                $data['won'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'];
                $data['draws'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'];
                $data['lost'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'];
                $data['goal_for'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'];
                $data['goal_against'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'];

                //$data = $ageGroupList[$fix1['CupFixture']['hometeam']];
                DB::table('match_standing')->where('id',$homeTeamExist->id)->update($data);
                $sendData['home'] = $data;
                $sendData['home']['competition_id'] = $homeTeamExist->competition_id;
                $sendData['home']['team_id'] = $homeTeamExist->team_id;
                //$this->CupLeagueTable->save($data);
            } else {

              //  $this->CupLeagueTable->create();
                $data3 = array();
                //$data = $ageGroupList[$fix1['CupFixture']['hometeam']];
                $data3['competition_id'] = $cup_competition_id;
                $data3['tournament_id'] = $fix1['CupFixture']['tournamentId'];
                $data3['team_id'] = $fix1['CupFixture']['hometeam'];

                $data3['points'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'] * $winningPoints + $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] * $drawPoints + $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'] * $losePoints;

                $data3['played'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'];
                $data3['won'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'];
                $data3['draws'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'];
                $data3['lost'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'];
                $data3['goal_for'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'];
                $data3['goal_against'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'];
                DB::table('match_standing')->insert($data3);
                $sendData['home'] = $data3;
                //$this->CupLeagueTable->save($data);
            }

            // check for awayTeam
           // $awayTeamExist = $this->CupLeagueTable->find('first', array('conditions' => array('comp_id' => $cup_competition_id, 'team_id' => $fix1['CupFixture']['awayteam'])));
             $awayTeamExist = DB::table('match_standing')
                            ->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])
                            ->where('competition_id','=',$cup_competition_id)
                            ->where('team_id',$fix1['CupFixture']['awayteam'])
                            ->get()->first();

            if ( count($awayTeamExist) > 0){
               // $this->CupLeagueTable->id = $awayTeamExist['CupLeagueTable']['id'];
              //  $data = $ageGroupList[$fix1['CupFixture']['awayteam']];
              //  $this->CupLeagueTable->save($data);
                $data1 = array();
                // TODO : remains
                //$data['points'] =
                $data1['points'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Won'] * $winningPoints + $ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'] * $drawPoints + $ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'] * $losePoints;

                $data1['played'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'];
                $data1['won'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Won'];
                $data1['draws'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'];
                $data1['lost'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'];
                $data1['goal_for'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'];
                $data1['goal_against'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'];

                //$data = $ageGroupList[$fix1['CupFixture']['hometeam']];
                DB::table('match_standing')->where('id',$awayTeamExist->id)->update($data1);
                $sendData['away'] = $data1;
                $sendData['away']['competition_id'] = $awayTeamExist->competition_id;
                $sendData['away']['team_id'] = $awayTeamExist->team_id;
            } else {
              /* $this->CupLeagueTable->create();
                $data = array();
                $data = $ageGroupList[$fix1['CupFixture']['awayteam']];
                $data['comp_id'] = $cup_competition_id;
                $data['team_id'] = $fix1['CupFixture']['awayteam'];
                $this->CupLeagueTable->save($data); */

                $data2 = array();
                //$data = $ageGroupList[$fix1['CupFixture']['hometeam']];
                $data2['competition_id'] = $cup_competition_id;
                $data2['team_id'] = $fix1['CupFixture']['awayteam'];
                $data2['tournament_id'] = $fix1['CupFixture']['tournamentId'];
                $data2['points'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Won'] * $winningPoints + $ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'] * $drawPoints + $ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'] * $losePoints;

                $data2['played'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'];
                $data2['won'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Won'];
                $data2['draws'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Draw'];
                $data2['lost'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'];
                $data2['goal_for'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'];
                $data2['goal_against'] = $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'];
                DB::table('match_standing')->insert($data2);
                //$sendData = $data2;
                $sendData['away'] = $data2;
            }
        }

        // Now here call Function for Placing Match Team Assignments

        $this->TeamPMAssign($sendData);
       // $this->PMTeamAssignment($sendData);
        return $fix;
    }
    /*
      This function used for Team Assignment For Placing Matches
     */
    private function PMTeamAssignment($sendData)
    {

      $competition_id = $sendData['home']['competition_id'];
      $teams  = DB::table('teams')->where('teams.competation_id','=',$competition_id)
               ->leftJoin('tournament_competation_template','tournament_competation_template.id','=','teams.age_group_id')
               ->leftJoin('tournament_template','tournament_template.id','=','tournament_competation_template.tournament_template_id')
               ->select('teams.id as TeamId','tournament_template.id as TemplateId','tournament_template.json_data as TemplateJson',
                'teams.name as TeamName','tournament_competation_template.group_name','tournament_competation_template.category_age')
               ->get();
      print_r($teams);exit;
      $templateJson = $teams[0]->TemplateJson;


    }
    private function TeamPMAssign($data)
    {
        $compId = $data['home']['competition_id'];
        $cupId = $compId;

        //$cupRoundrobinData = $this->CupRoundrobin->find('first', array('conditions' => array('comp_id' => $cupId)));

        //$groupTeams = json_decode($cupRoundrobinData['CupRoundrobin']['groups'],true);
        $teams = DB::table('teams')->where('competation_id','=',$compId)->get();
        //print_r($teams);exit;
        $defaultArray = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,
          'home_goal' => 0,'away_goal' => 0,'goal_difference' => 0,'Total' => 0,
          'manual_override' => 0,'group_winner' => 0);
        $calculatedArray = array();
       // foreach ($groupTeams as $gkey => $gvvalue) {
        //    $i =1;
            foreach ($teams as $gkey => $gvalue) {
                //$teamExist = $this->CupLeagueTable->find('first', array('conditions'
                // => array('comp_id' => $cupId, 'team_id' => $gvalue)));
                // check in match standing table for that team and Group Id
                $teamExist = DB::table('match_standing')
                        ->leftJoin('teams','teams.id','=','match_standing.team_id')
                        ->select('teams.*','match_standing.*')
                              ->where('match_standing.competition_id',$cupId)
                               ->where('teams.id',$gvalue->id)
                               ->get()->first();
                $winPoints = 3; $losePoints =0;$drawPoints=1;

                //print_r($teamExist);
                if ( count($teamExist) > 0){

                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['Played'] = $teamExist->played;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['Won'] = $teamExist->won;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['Lost'] = $teamExist->lost;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['Draw'] = $teamExist->draws;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['home_goal'] = $teamExist->goal_for;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['away_goal'] = $teamExist->goal_against;
                    $total = ( ( (int)$teamExist->won * $winPoints ) + ( (int)$teamExist->draws * $drawPoints) )  + ( (int)$teamExist->lost * $losePoints);

                    $goal_difference = ( (int)$teamExist->goal_for  - (int)$teamExist->goal_against );
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['goal_difference'] = $goal_difference;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['Total'] = $total;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamid'] = $gvalue->id;
                     $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamName'] =
                     $teamExist->name;
                     $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamGroup'] =
                     $teamExist->assigned_group;
                      $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamGroupName'] =
                     $teamExist->group_name;
                      $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamAgeGroup'] =
                     $teamExist->age_group_id;
                     $groupAlphabet = explode('-',$teamExist->assigned_group);
                     $groupAlphabet = $groupAlphabet[1];
               //  $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamAgeGroupPlaceHolder']
               //  =  $i.$groupAlphabet;
                 //   $calculatedArray[$gkey][$gvalue]['manual_override'] =  $teamExist['CupLeagueTable']['manual_override'];
                 //   $calculatedArray[$gkey][$gvalue]['group_winner'] =  $teamExist['CupLeagueTable']['group_winner'];
                } else {
                    $calculatedArray[$gvalue->competation_id][$gvalue->id] = $defaultArray;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamid'] = $gvalue->id;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamName'] =
                     $teamExist->name;
                    $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamGroup'] =
                     $teamExist->assigned_group;
                   $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamGroupName'] =
                     $teamExist->group_name;
                      $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamAgeGroup'] =
                     $teamExist->age_group_id;
                     $groupAlphabet = explode('-',$teamExist->assigned_group);
                     $groupAlphabet = $groupAlphabet[1];
                // $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamAgeGroupPlaceHolder']
               //  =  $i.$groupAlphabet;

                }
              //  $i++;
            }
       // }
       // echo 'Before Sort';

      //  echo 'After Sort';
        $for_override_condition = array();
        foreach ($calculatedArray as $ckey => $cvalue) {
            $mid = $cid = $did = $overrride = $group_winner = array();
            foreach ($cvalue as $cckey => $ccvalue) {

               $mid[$cckey]  = (int)$ccvalue['Total'];
               $cid[$cckey]  = (int)$ccvalue['Played'];
               $did[$cckey]  = (int)$ccvalue['goal_difference'];
              // $overrride[$cckey]  = (int)$ccvalue['manual_override'];
              // $group_winner[$cckey]  = (int)$ccvalue['group_winner'];
              // $for_override_condition[$ckey][$cckey] = (int)$ccvalue['manual_override'];
            }

            array_multisort($mid, SORT_DESC,$did, SORT_DESC,$cid, SORT_DESC,$cvalue);
            $calculatedArray[$ckey] = $cvalue;
        }
        $i=1;
        foreach($calculatedArray[$cupId] as $kky=>$data) {
          $groupAlphabet = explode('-',$data['teamGroup']);
          $groupAlphabet = $groupAlphabet[1];
          $calculatedArray[$cupId][$kky]['teamAgeGroupPlaceHolder'] = $i.$groupAlphabet;
          $i++;
        }
       // print_r($calculatedArray);
       // exit;
        // Now we have sorted array with TeamId
        $ageGroupId = 0;
        $temptournamentId  =0;
        $particularGroup = '';
        if(isset($calculatedArray[$cupId][0]['teamAgeGroup']))
        {
          $temptournamentId = $teamExist->tournament_id;
          $ageGroupId = $calculatedArray[$cupId][0]['teamAgeGroup'];
          $particularGroup = $calculatedArray[$cupId][0]['teamGroup'];
        }

        $reportQuery = DB::table('temp_fixtures')
        ->select('temp_fixtures.id as matchID','temp_fixtures.match_number as MatchNumber','temp_fixtures.home_team_name as HomeTeam','temp_fixtures.home_team as HomeTeamId','temp_fixtures.away_team_name as AwayTeam',

          'temp_fixtures.away_team as AwayTeamId')
        ->leftJoin('competitions','competitions.id','=','temp_fixtures.competition_id')
        ->leftJoin('tournament_competation_template','tournament_competation_template.id','=','competitions.tournament_competation_template_id')
        ->leftJoin('tournament_template','tournament_template.id','=','tournament_competation_template.tournament_template_id')
        ->where('competitions.tournament_competation_template_id','=',$ageGroupId)
        //->where('temp_fixtures.is_scheduled','=',0)
        ->where('temp_fixtures.tournament_id','=',$temptournamentId)
       //  ->whereRaw(
        //  DB::raw(" (temp_fixtures.home_team = '0' or temp_fixtures.away_team = '0')"))
        ->get();
       // print_r($calculatedArray);
       //exit;
       // print_r($reportQuery);
       // exit;
        $matches = $reportQuery;
        //print_r($matches);exit;
        if($matches) {
          foreach($matches as $key=>$match) {
            //$templateData = json_decode($match->JsonData,true);
            $exmatchNumber = explode('.',$match->MatchNumber);
            //print_r($exmatchNumber);exit;
            $value = explode('-',$exmatchNumber[2]);
            $homeTeam = $value[0];

            //$homeTeam = $match->HomeTeam;

            if($homeTeam) {
              foreach($calculatedArray[$cupId] as $dd1) {

                if($homeTeam == $dd1['teamAgeGroupPlaceHolder']) {
                  //echo $matchId = $match->matchID;
                  //echo $matchNumber = $match->MatchNumber;


                  $updatedMatchNumer =  str_replace($homeTeam,$dd1['teamName'],$match->MatchNumber);
                  $homeTeamId = $dd1['teamid'];
                  $updateArray = [
                  'home_team_name'=> $dd1['teamName'],
                  'home_team'=>$dd1['teamid']
                  ];
                 // echo '<pre>';
                //  print_r($updateArray);
                  DB::table('temp_fixtures')->where('id',$match->matchID)->update($updateArray);
                  unset($updateArray);
                  //echo '<br>';
                }
                // check if value is changed
                //if($match->HomeTeamId != $dd1[''])

              }
            }
            $awayTeam = $value[1];
            //$awayTeam = $match->AwayTeam;
            if($awayTeam) {
              foreach($calculatedArray[$cupId] as $dd1) {
                if($awayTeam == $dd1['teamAgeGroupPlaceHolder']) {
                  $updatedMatchNumer =  str_replace($awayTeam,$dd1['teamName'],$match->MatchNumber);
                  $awayTeamId = $dd1['teamid'];
                  $updateArray = [
                  'away_team_name'=> $dd1['teamName'],
                  'away_team'=>$dd1['teamid']
                  ];
                  DB::table('temp_fixtures')->where('id',$match->matchID)->update($updateArray);
                  unset($updateArray);
                }else {
                  echo 'hi-Away';
                }
              }
            }

            // else check if its new change

          }

        }

        // now here we check how many matches and Sync it with proper calc poition

        //exit;
        return ;
        exit;
        print_r($matches);exit;
        /*foreach($matches as $match){
          $jsonData = json_decode($match->JsonData,true);
          $rankingData = $jsonData['tournament_competition_ranking'];

          foreach($rankingData as $rankData) {
            foreach($rankData as $roundGroups) {
              foreach($roundGroups['groups'] as $rrGroup) {
                  foreach($rrGroup['teams'] as $teams) {
                    if($teams['team'] == $match->HomeTeam) {
                      //$calculatedArray[$match->]
                      foreach($calculatedArray as $key=>$groupTeams) {
                          foreach($groupTeams as $tindex=>$teamm) {
                            $calculatedArray[$key][$tindex][$match->HomeTeam] =
                            $teamm['teamid'];
                          }
                      }
                    }
                  }
              }
            }
          }

          //print_r($rankingData);
        }*/
        print_r($calculatedArray);
        exit;
       /* $reportQuery = $reportQuery->where(function ($query)
                              {
                                $query->where('temp_fixtures.home_team','=' ,0)
                                ->andWhere('temp_fixtures.away_team','=',0);
                              }
                            );*/
        $reportQuery = $reportQuery
                ->where('competitions.tournament_competation_template_id','=',$ageGroupId)
                ->where('temp_fixtures.is_scheduled','=',0)
                ->where('temp_fixtures.tournament_id','=',$temptournamentId)
                ->toSql();
        print_r($reportQuery);exit;
        print_r($calculatedArray);
        exit;
        $matches = DB::table('temp_fixtures')
        ->select('temp_fixtures.id as matchID','temp_fixtures.match_number as MatchNumber','temp_fixtures.home_team_name as HomeTeam','temp_fixtures.home_team as HomeTeamId','temp_fixtures.away_team_name as AwayTeam','temp_fixtures.away_team as AwayTeamId')
        ->leftJoin('competitions','competitions.id','=','temp_fixtures.competition_id')
        ->where('competitions.tournament_competation_template_id','=',$ageCategoryId)
        ->where('temp_fixtures.is_scheduled','=',0)
        ->get();

        // we get the teams sort by position and assign it to the placing matches
         $CompTempData = DB::table('teams')
                          ->leftJoin('competitions','competitions.id','=','teams.competation_id')
                        ->leftJoin('tournament_competation_template','tournament_competation_template.id','=','competitions.tournament_competation_template_id')
                        ->leftJoin('tournament_template','tournament_template.id','=','tournament_template_id')
                        ->select(
                          'competitions.name as GroupName',
                          'competitions.id as GroupId',
                          'competitions.competation_round_no as RoundNo',
                          'competitions.competation_type as CompType',
                          'tournament_competation_template.id as TCTID',
                      'tournament_competation_template.group_name as TemplateGroupName',
                      'tournament_competation_template.category_age as CategoryAge',
                      'teams.id as TeamId','teams.name as TeamName',
                      'teams.group_name as TeamGroupName','teams.assigned_group as TeamGroup','tournament_template.json_data as JsonData'
                      )
                        ->where('teams.competation_id',$cupId)->get();
                        //'tournament_template.json_data as JsonData'
        $new_arr = array();
        $ageCategoryId = '';
      //   print_r($calculatedArray);exit;
        foreach($CompTempData as $teamData) {
          $jsonData = json_decode($teamData->JsonData,true);
          $ageCategoryId = $teamData->TCTID;
          $cround = $teamData->RoundNo;
          $templateRanking = $jsonData['tournament_competition_ranking']['format_name'];

          foreach($templateRanking as $round)
          {

            //echo $cround;exit;
            if($round['round'] == $teamData->RoundNo){
              foreach($round['groups'] as $groups) {
                 $tempGroupName = str_replace(' ','-' ,$groups['group_name']);
                 //echo $teamData->TeamGroup;
                 //echo $tempGroupName;

                if(trim($tempGroupName) == trim($teamData->TeamGroup)) {
                    // Now we have to replace it
                    foreach($groups['teams'] as $key=>$teams) {
                      $new_arr[$key]['placeholder'] =  $teams['team'];
                      $new_arr[$key]['actual_team'] =  $calculatedArray[$teamData->GroupId][$key];
                    }

                }
              }
             // echo 'SameRound';exit;
            }
          }

          //exit;
        }
        //echo $ageCategoryId;
        $matches = DB::table('temp_fixtures')
        ->select('temp_fixtures.id as matchID','temp_fixtures.match_number as MatchNumber','temp_fixtures.home_team_name as HomeTeam','temp_fixtures.home_team as HomeTeamId','temp_fixtures.away_team_name as AwayTeam','temp_fixtures.away_team as AwayTeamId')
        ->leftJoin('competitions','competitions.id','=','temp_fixtures.competition_id')
        ->where('competitions.tournament_competation_template_id','=',$ageCategoryId)
        ->where('temp_fixtures.is_scheduled','=',0)
        ->get();
        //echo $matches;exit;
        //print_r($matches);exit;
        $update_array = array();
        foreach($matches as $match){
          foreach($new_arr as $key=>$data) {
            if($data['placeholder'] == $match->HomeTeam) {
              $update_array[$key]['id'] =$match->matchID;
                $update_array[$key]['home_team'] =$data['actual_team']['teamid'];
                //$update_array[$key]['match_number'] =$data['actual_team']['teamid'];
            }
            if($data['placeholder'] == $match->AwayTeam) {
              $update_array[$key]['id'] =$match->matchID;;
                $update_array[$key]['away_team'] =$data['actual_team']['teamid'];
                //$update_array[$key]['match_number'] =str_replace('' , '',$data['actual_team']['teamid'];
            }
          }
        }
        print_r($update_array);exit;
        // Now here we update the placeholder teams
        //foreach($new_arr)
          print_r($new_arr);exit;
          print_r($CompTempData);exit;
         print_r($calculatedArray);
        // Here we get the teams by sort for particular Group
        return ;
        print_r($calculatedArray);
       //exit;
    }
}

