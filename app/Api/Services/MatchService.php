<?php

namespace Laraspace\Api\Services;

use DB;
use Laraspace\Api\Contracts\MatchContract;
use Validator;
use Laraspace\Model\Role;
use PDF;
use Laraspace\Models\TempFixture;

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
    public function generateMatchPrint($matchData)
    {
       $matchId = $matchData['matchId'];
       $matchResult = $this->matchRepoObj->getMatchDetail($matchId);
       //echo '<pre>';
     // print_r($matchResult->toArray());exit;
      $date = new \DateTime(date('H:i d M Y'));
        // $date->setTimezone();.
      $resultData = $matchResult;
      // dd($resultData);
        $pdf = PDF::loadView('pitchplanner.pitch',['data' => $resultData->toArray()])
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOrientation('portrait')
            ->setOption('footer-right', 'Page [page] of [toPage]')
            ->setOption('header-right', $date->format('H:i d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);
        return $pdf->inline('Pitch.pdf');
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

        $competationId = $this->calculateCupLeagueTable($matchData->all()['matchData']['matchId']);
        $data['competationId'] = $competationId;
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $data];
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
       $competationId = $this->calculateCupLeagueTable($matchData->all()['matchData']['matchId']);
       $data['competationId'] = $competationId;
        if ($scoreUpdate) {
            return ['status_code' => '200', 'data' => $data, 'message' => 'Score updated successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scoreUpdate];
        }
    }

    private function secondRoundElimination123($fetchRecord, $updatedValue,$var)
    {
      //echo '<br/>hello s3condroundelimination';
      echo '\n<br/>ID:'.$fetchRecord->id;
      echo '\n<br/>ID2:'.$fetchRecord->id;

     // print_r($fetchRecord);
      echo 'updated record';
      //print_r($updatedValue);
      //print_r();
      // explode updated record
      $updatedMatchNumber = explode('-',$updatedValue->match_number);
      $updatedMatchNumber = $updatedMatchNumber[count($updatedMatchNumber)-1];
      $updatedMatchNumber = substr($updatedMatchNumber,1,-1);
      $rec = explode("|",$updatedMatchNumber);
      print_r($rec);
      return;
      $updval =(string)$updatedValue->match_number;
      $singleFixTeams = explode(".",$singleFixture->match_number);
      $singleFXTeam = $singleFixTeams[count($singleFixTeams)-1];

      $doubleElm = explode('.',$updval);
      $doubleElm1 = $doubleElm[count($doubleElm)-1];

      $vaal = explode('-(',$doubleElm1);

      print_r($vaal);
      $hometeam = substr($vaal[0],1,-1);
      $awayteam = substr($vaal[1],0,-1);
      echo '<br>HTSM:'.$hometeam;
      echo '<br>ATSM:'.$awayteam;
      //echo 'ssd'.$singleFXTeam;
    if($var == 'WR') {
      if(trim($hometeam) == trim($singleFXTeam)) {
        echo 'hi123';
        // here we check the score
          if($singleFixture->hometeam_score > $singleFixture->awayteam_score)
          {
            $hometeamName = $singleFixture->home_team_name;
            $homeTeamId = $singleFixture->home_team;
           }else {
            $hometeamName = $singleFixture->away_team_name;
            $homeTeamId = $singleFixture->away_team;
           }
           $updateArray = [ 'home_team_name'=> $hometeamName,'home_team'=>$homeTeamId];
           DB::table('temp_fixtures')->where('id',$updatedValue->id)->update($updateArray);

      }
      if(trim($awayteam) == trim($singleFXTeam)) {
        echo 'hi246';
          if($singleFixture->hometeam_score > $singleFixture->awayteam_score)
         {
            $awayteamName = $singleFixture->home_team_name;
            $awayTeamId = $singleFixture->home_team;
         }else {
            $awayteamName = $singleFixture->away_team_name;
            $awayTeamId = $singleFixture->away_team;
         }
         $updateArray = ['away_team_name'=> $awayteamName, 'away_team'=>$awayTeamId];
         DB::table('temp_fixtures')->where('id',$updatedValue->id)->update($updateArray);

      }
    }
    if($var == 'LR') {
      if(trim($hometeam) == trim($singleFXTeam)) {
        echo 'hi123';
        // here we check the score
          if($singleFixture->hometeam_score < $singleFixture->awayteam_score)
          {
            $hometeamName = $singleFixture->home_team_name;
            $homeTeamId = $singleFixture->home_team;
           }else {
            $hometeamName = $singleFixture->away_team_name;
            $homeTeamId = $singleFixture->away_team;
           }
           $updateArray = [ 'home_team_name'=> $hometeamName,'home_team'=>$homeTeamId];
           DB::table('temp_fixtures')->where('id',$updatedValue->id)->update($updateArray);

      }
      if(trim($awayteam) == trim($singleFXTeam)) {
        echo 'hi246';
          if($singleFixture->hometeam_score < $singleFixture->awayteam_score)
         {
            $awayteamName = $singleFixture->home_team_name;
            $awayTeamId = $singleFixture->home_team;
         }else {
            $awayteamName = $singleFixture->away_team_name;
            $awayTeamId = $singleFixture->away_team;
         }
         $updateArray = ['away_team_name'=> $awayteamName, 'away_team'=>$awayTeamId];
         DB::table('temp_fixtures')->where('id',$updatedValue->id)->update($updateArray);

      }
    }
    }
    private function secondRoundElimination($singleFixture,$updatedval,$var) {
      // Now here we propgate the result to the descender teams
      //echo '1';exit;
      $age_category_id = $singleFixture->age_group_id;
      $tournament_id   = $singleFixture->tournament_id;

      // We have to find the record of it for winner and looser
      $match_number = $singleFixture->match_number;
      $match_number = explode(".",$match_number);
      $frs = explode("-",$match_number[0]);
      $val = $frs[2]."_".$match_number[1];

      $home_team_score = $singleFixture->hometeam_score;
      $away_team_score = $singleFixture->awayteam_score;

      // FOr Winner Conditions
      if($home_team_score >  $away_team_score) {
        $winnerTeam = $singleFixture->home_team_name;
        $winnerId = $singleFixture->home_team;
      }
      if($home_team_score <  $away_team_score) {
        $winnerTeam = $singleFixture->away_team_name;
        $winnerId = $singleFixture->away_team;
      }
      // FOr Looser Conditions
      if($home_team_score <  $away_team_score) {
        $looserTeam = $singleFixture->home_team_name;
        $looserId = $singleFixture->home_team;
      }
      if($home_team_score >  $away_team_score) {
        $looserTeam = $singleFixture->away_team_name;
        $looserId = $singleFixture->away_team;
      }
      // Now fire a query which gives two record Winner and Looser
      $results = TempFixture::where('age_group_id','=',$age_category_id)->where('tournament_id','=',$tournament_id)
         ->whereRaw(DB::raw("match_number like '%(".$val."_WR)%' OR  match_number like '%(".$val."_LR)%' "))->get();
      // here we get two records 1 for Winner and other for looser
      foreach($results as $record) {
        // we have record
       // echo 'Match Id'.$record->id;
        // here first we check condition for Draw if it is then use match_winner field
        // here check if Home Score is Greater than away score
       $rec_mtchNumber = explode(".",$record->match_number);
       $teams =  $rec_mtchNumber[2];
       $teams = explode("-",$teams);
       $homeTeam = $teams[0];$awayTeam = $teams[1];
       // if its winner then
       if(strpos($record->match_number,"WR") !== false)
       {
         // its Home team
         if(trim("(".$val."_WR)") == trim($homeTeam)) {
         // echo 'homeW';
          TempFixture::where('id',$record->id)->update([
            'home_team_name'=> $winnerTeam,
            'home_team'=> $winnerId
          ]);
         }

         if(trim("(".$val."_WR)") == trim($awayTeam)) {
          TempFixture::where('id',$record->id)->update([
            'away_team_name'=> $winnerTeam,
            'away_team'=> $winnerId
          ]);
         }

         // its away team
       }
       // if its looser then
       if(strpos($record->match_number,"LR") !== false)
       {
        if(trim("(".$val."_LR)") == trim($homeTeam)) {
          TempFixture::where('id',$record->id)->update([
            'home_team_name'=> $looserTeam,
            'home_team'=> $looserId
          ]);
         }

         if(trim("(".$val."_LR)") == trim($awayTeam)) {
          TempFixture::where('id',$record->id)->update([
            'away_team_name'=> $looserTeam,
            'away_team'=> $looserId
          ]);
         }
       }
      }
      return ;
    }
    private function calculateEliminationTeams($singleFixture, $findTeams) {

      $singleFixture = $singleFixture[0];

      $tournament_id = $singleFixture->tournament_id;
      $ageGroupId = $singleFixture->age_group_id;
      //$compIds = $this->getCompeIds($singleFixture->competition_id);
      //print_r($compIds);exit;
      $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$tournament_id)
                ->where('round','=' , 'Elimination')
                ->where('age_group_id','=',$ageGroupId)
                ->get();

      $matchArr =  array();
      $teams_arr = explode('.', $singleFixture->match_number);

      $teams = $teams_arr[count($teams_arr)-1];
      foreach($matches as $match) {

        $matchNumber = explode('.',$match->match_number);
        //print_r($matchNumber);
        $matchTeams = $matchNumber[count($matchNumber)-1];
        $mtsTeams = explode('-',$matchTeams);
        //print_r($mtsTeams);
        // Teams For that Matches
        $homeTeam = $mtsTeams[0];
        $awayTeam = $mtsTeams[1];
        // Get hometeam=1A awayTeam =2B
        // here we check it For 2nd round Eliminbation


        // First For Winner
        $modifiedTeams = str_replace('-','_',$teams);
      //  echo 'Hi MOD Teams<br>';
        //print_r($modifiedTeams);exit;
        if (strpos($modifiedTeams, 'WR') !== false) {

          $selTeams = explode('-',$teams);
          $SelhomeTeam = $selTeams[0];
          $SelawayTeam = $selTeams[1];

          $var = '';

          if($SelhomeTeam == $homeTeam ) {
            $match1 = $match;
          }
          if($SelawayTeam==$awayTeam) {
            $match2=$match;
          }
          // here check for Multiple Value for detect the updated record value

          if($homeTeam[0] == '(') {
            $this->secondRoundElimination($match1,$match,'WR');
          }
          if($awayTeam[strlen($awayTeam)-1]==')') {
            $this->secondRoundElimination($match2,$match,'WR');
          }
        }

        if (strpos($modifiedTeams, 'LR') !== false) {

          $selTeams = explode('-',$teams);
          $SelhomeTeam = $selTeams[0];
          $SelawayTeam = $selTeams[1];
          $var = '';
          if($SelhomeTeam == $homeTeam ) {
            // here we get that Match
            $match1 = $match;
          }
          if($SelawayTeam==$awayTeam) {
            $match2=$match;
          }
          // here check for Multiple Value for detect the updated record value

          if($homeTeam[0] == '(') {
            //echo 'yes';exit;
            $this->secondRoundElimination($match1,$match,'LR');
          }
          if($awayTeam[strlen($awayTeam)-1]==')'){
            //echo 'false';exit;
            $this->secondRoundElimination($match2,$match,'LR');
          }
        }

        $modifiedTeamsWinner = $modifiedTeams.'_WR';

        if($homeTeam  == $modifiedTeamsWinner) {
          if($singleFixture->hometeam_score > $singleFixture->awayteam_score)
          {
            $hometeamName = $singleFixture->home_team_name;
            $homeTeamId = $singleFixture->home_team;
           }else {
            $hometeamName = $singleFixture->away_team_name;
            $homeTeamId = $singleFixture->away_team;
           }
           $updateArray = [ 'home_team_name'=> $hometeamName,'home_team'=>$homeTeamId];
           DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
        }
        if($awayTeam  == $modifiedTeamsWinner) {
          if($singleFixture->hometeam_score > $singleFixture->awayteam_score)
         {
            $awayteamName = $singleFixture->home_team_name;
            $awayTeamId = $singleFixture->home_team;
         }else {
            $awayteamName = $singleFixture->away_team_name;
            $awayTeamId = $singleFixture->away_team;
         }
         $updateArray = ['away_team_name'=> $awayteamName,'away_team'=>$awayTeamId];
         DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
        }
        // For Looser
        $modifiedTeamsLooser = $modifiedTeams.'_LR';

        if($homeTeam  == $modifiedTeamsLooser) {
            if($singleFixture->hometeam_score < $singleFixture->awayteam_score)
         {
            $hometeamName = $singleFixture->home_team_name;
            $homeTeamId = $singleFixture->home_team;

         }else {
              $hometeamName = $singleFixture->away_team_name;
               $homeTeamId = $singleFixture->away_team;
         }
         $updateArray = ['home_team_name'=> $hometeamName,'home_team'=>$homeTeamId];
         DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
        }
        if($awayTeam  == $modifiedTeamsLooser) {
             if($singleFixture->hometeam_score < $singleFixture->awayteam_score)
         {
               $awayteamName = $singleFixture->home_team_name;
               $awayTeamId = $singleFixture->home_team;
         }else {
               $awayteamName = $singleFixture->away_team_name;
               $awayTeamId = $singleFixture->away_team;
         }
         $updateArray = ['away_team_name'=> $awayteamName,'away_team'=>$awayTeamId];
         DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
        }
      }

      return $singleFixture->competition_id;
      exit;
      print_r($matches);exit;
    }
    public function calculateCupLeagueTable($id) {
        $singleFixture = DB::table('temp_fixtures')->select('temp_fixtures.*')->where('id','=',$id)->get();

        $fix1=array();

        foreach($singleFixture as $singleFxture)
        {
          $fix1['CupFixture']['cupcompetition'] = $singleFxture->competition_id;
          $fix1['CupFixture']['hometeam'] = $singleFxture->home_team;
          $fix1['CupFixture']['awayteam'] = $singleFxture->away_team;
          $fix1['CupFixture']['tournamentId'] = $singleFxture->tournament_id;
          $fix1['CupFixture']['match_round'] = $singleFxture->round;
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
        if($fix1['CupFixture']['match_round'] == 'Elimination') {
          // So here we have to Call Function For Elimination Matches
          return $this->calculateEliminationTeams($singleFixture, $findTeams);
        }
        $comType = 'C';
        if ($comType == 'C') {
            $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])
                ->where('competition_id','=',$cup_competition_id)
                //->leftJoin('competitions','competitions.id','=','temp_fixtures.competition_id')
                ->whereIn('away_team',$findTeams)
                ->ORwhereIn('home_team',$findTeams)
                ->where('round','=' , 'Round Robin')
                ->get();
                //print_r($matches);exit;

            $fixtu = array();
            foreach($matches as $key1=>$match)
            {
              $fixtu[$key1]['CupFixture']['hometeamscore'] = (string)$match->hometeam_score;
              $fixtu[$key1]['CupFixture']['awayteamscore'] = (string)$match->awayteam_score;

              $fixtu[$key1]['CupFixture']['hometeam'] = $match->home_team;
              $fixtu[$key1]['CupFixture']['awayteam'] = $match->away_team;
              $fixtu[$key1]['CupFixture']['HomeTeamScoreAfterExtraTime']='';
            }
            //echo getType($fixtu[$key1]['CupFixture']['hometeamscore']);exit;
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
                if($fix['CupFixture']['hometeamscore'] != '' && ($fix['CupFixture']['awayteamscore'] != '')&& empty($fix['CupFixture']['Abandoned'])) {

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
                                 // echo '1';
                                    $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                                }
                            } else {
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                // echo '2';
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

         // print_r($ageGroupList);exit;
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

        return $cup_competition_id ;
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

        if(count($calculatedArray) > 0) {
          foreach($calculatedArray[$cupId] as $kky=>$data) {
            $groupAlphabet = explode('-',$data['teamGroup']);
            $groupAlphabet = $groupAlphabet[1];
            $calculatedArray[$cupId][$kky]['teamAgeGroupPlaceHolder'] = $i.$groupAlphabet;
            $i++;
          }
        } else {
          return $cupId;
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
        ->where('temp_fixtures.tournament_id','=',$temptournamentId)
        ->get();
       // print_r($calculatedArray);
       //exit;
       // print_r($reportQuery);
       // exit;
        $matches = $reportQuery;
        //print_r($matches);exit;
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
                  if($this->checkForEndRR($cupId) == true) {
                    DB::table('temp_fixtures')->where('id',$match->matchID)->update($updateArray);
                    unset($updateArray);
                  }
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
                  if($this->checkForEndRR($cupId) == true) {
                    DB::table('temp_fixtures')->where('id',$match->matchID)->update($updateArray);
                    unset($updateArray);
                 }
                }else {
                 // echo 'hi-Away';
                }
              }
            }

            // else check if its new change

          }

        }


        return ;
    }
    private function checkForEndRR($competationId) {
      // here we check if any unschedule match for that competations if no return yes else no
      //echo 'CID'.$competationId;
      $matches = DB::table('temp_fixtures')->
      where('is_scheduled','=','0')
      ->where('hometeam_score','=',NULL)->orWhere('awayteam_score','=',NULL)
      ->where('round','=','Round Robin')
      ->where('competition_id',$competationId);
      //->get();

      if($matches->exists()) {
        //echo 'hellofalse';
        return false;
      } else {
        //echo 'hellotrue';
        return true;
      }
    }
}

