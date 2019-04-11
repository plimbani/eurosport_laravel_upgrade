<?php

namespace Laraspace\Api\Services;

use DB;
use File;
use Storage;
use Laraspace\Api\Contracts\MatchContract;
use Validator;
use Laraspace\Model\Role;
use PDF;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Competition;
use Laraspace\Models\TeamManualRanking;
use Laraspace\Models\Team;
use Laraspace\Models\Position;
use Laraspace\Traits\TournamentAccess;
use Laraspace\Models\TournamentCompetationTemplates;

class MatchService implements MatchContract
{
    use TournamentAccess;

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
        return ['status_code' => '200', 'data' => $fixtureResData,'message' => 'Match Fixture data'];
    }
    /**
     * Get Standing  Details For Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function getStanding($data, $refreshStanding)
    {
        $data = $data->all();
        $tournamentData = $data['tournamentData'];

        $standingResData = $this->matchRepoObj->getStanding($tournamentData);
        if($refreshStanding && $refreshStanding == 'yes' && isset($tournamentData['competitionId']) && $tournamentData['competitionId'] != '')
        {
            $competition = Competition::find($tournamentData['competitionId']);
            if(count($standingResData) != $competition->team_size) {
                return $this->refreshStanding($data);
            }
        }

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
        $data = $matchData->all()['matchData'];
        $scheduledResult = $this->matchRepoObj->setMatchSchedule($matchData->all()['matchData']);
        if ($scheduledResult) {
            if($scheduledResult != -1 && $scheduledResult != -2){
              return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Match has been scheduled successfully'];
            } else if($scheduledResult == -1){
              return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'One or both teams are scheduled for a team interval.'];
            } else if($scheduledResult == -2){
               return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'This pitch is the wrong pitch size for this fixture.'];
            }
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

    public function checkTeamIntervalforMatches($matchData) {
      // dd($matchData->all());
      $matchListResult = $this->matchRepoObj->checkTeamIntervalforMatches($matchData->all());
        if ($matchListResult) {
            return ['status_code' => '200', 'data' => $matchListResult, 'message' => 'Match scheduled successfully'];
        } else {
            return ['status_code' => '300', 'message' => $matchListResult];
        }
    }

    public function generateMatchPrint($matchData)
    {
      $matchId = $matchData['matchId'];
      $matchResult = $this->matchRepoObj->getMatchDetail($matchId);
      $date = new \DateTime(date('H:i d M Y'));
      $resultData = $matchResult->toArray();
      
      // Here we modified the array according to status and winner
      if(isset($matchData['result_override']) && $matchData['result_override']== 'false' ) {
        // Unset the match_status result and match Wineer
        unset($resultData['match_status']);
        unset($resultData['match_winner']);
      } else {
        $resultData['match_status'] = $matchData['status'];
        $resultData['name'] = $matchData['winner'];
      }

      $tempFixture = TempFixture::where('id', $matchId)->first();
      $competition = Competition::where('id', $tempFixture->competition_id)->first();
      $ageCategory = TournamentCompetationTemplates::where('id', $tempFixture->age_group_id)->first();
      
      $categoryAgeColor = $ageCategory->category_age_color;
      $categoryStripColor = $competition->color_code ? $competition->color_code : '#FFFFFF';

      $pdf = PDF::loadView('pitchplanner.pitch',['data' => $resultData,'result_override'=>$matchData['result_override'], 'categoryAgeColor' => $categoryAgeColor, 'categoryStripColor' => $categoryStripColor])
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

    public function generateCategoryReport($ageGroupId)
    {
      $competitions = Competition::where('tournament_competation_template_id',$ageGroupId)->get();
      $date = new \DateTime(date('H:i d M Y'));
      $pdfData = [];
      $leagueTable = [];
      $resultGridTable = [];
      $resultMatchesTable = [];
      $resultMatchesTableAfterFR = [];

      foreach ($competitions as $competition) {
        if ($competition->actual_competition_type == "Round Robin") {
          $tournamentData = ['tournamentData' => ['competitionId' => $competition->id, 'tournamentId' => $competition->tournament_id]];
          $result = $this->refreshStanding($tournamentData, 'yes');
          $leagueTable[$competition->id] = ['name' => $competition['name'] , 'standings' => $result['data']];
        }
        if ($competition->competation_round_no == "Round 1") {
          if ($competition->actual_competition_type == "Round Robin") {
            $tournamentDataResultGrid = ['tournamentData' => ['competationId' => $competition->id, 'tournamentId' => $competition->tournament_id]];
            $resultGrid = $this->getDrawTable(collect($tournamentDataResultGrid));
            if($resultGrid['status_code'] != '200') {
              $resultGrid['data'] = [];
            }
            $resultGridTable[$competition->id] = ['name' => $competition['name'], 'results' => $resultGrid['data'], 'actual_competition_type' => $competition['actual_competition_type']];
          } else {
            $resultGridTable[$competition->id] = ['name' => $competition['name'], 'results' => [], 'actual_competition_type' => $competition['actual_competition_type']];
          }

          $tournamentDataMatches = ['tournamentData' => ['competitionId' => $competition->id, 'tournamentId' => $competition->tournament_id, 'is_scheduled' => 1]];
          $resultMatches =$this->getFixtures(collect($tournamentDataMatches));
          $resultMatchesTable[$competition->id] = ['name' => $competition['name'], 'results' => $resultMatches['data']];
        }
        if ($competition->competation_round_no !== "Round 1") {
          $tournamentDataMatchesAfterFirstRound = ['tournamentData' => ['competitionId' => $competition->id, 'tournamentId' => $competition->tournament_id]];
          $resultMatchesAfterFirstRound =$this->getFixtures(collect($tournamentDataMatchesAfterFirstRound));
          $resultMatchesTableAfterFirstRound[$competition->id] = ['name' => $competition['name'], 'results' => $resultMatchesAfterFirstRound['data'], 'actual_competition_type' => $competition['actual_competition_type']];
        }
      }
      $pdfData['leagueTable'] = $leagueTable;
      $pdfData['resultGridTable'] = $resultGridTable;
      $pdfData['resultMatchesTable'] = $resultMatchesTable;
      $pdfData['resultMatchesTableAfterFirstRound'] = $resultMatchesTableAfterFirstRound;

      // dd($pdfData);

      $pdf = PDF::loadView('age_category.summary_report',['data' => $pdfData])
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOrientation('portrait')
            ->setOption('footer-right', 'Page [page] of [toPage]')
            ->setOption('header-right', $date->format('H:i d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);
        return $pdf->download('Summary report.pdf');
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

        $result = TempFixture::where('id',$matchData->all()['matchData']['matchId'])->first()->toArray();
        $tournamentId = $result['tournament_id'];
        $ageGroupId  = $result['age_group_id'];
        $teamsList =array($result['home_team'],$result['away_team']);

        $matchData = array('teams'=>$teamsList,'tournamentId'=>$tournamentId,'ageGroupId'=>$ageGroupId,'teamId'=>true);

        $matchresult =  $this->matchRepoObj->checkTeamIntervalforMatches($matchData);


        $data['competationId'] = $competationId;
        $data['isResultOverride'] = $result['is_result_override'];
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $data];
        } else {
            return ['status_code' => '300'];
        }
    }

    public function saveAllResults($matchData) {
      $teamArray = [];
      $competitionIds = [];
      $AllMatches = $matchData->all()['matchData']['matchDataArray'];
      $tournamentId = $matchData->all()['matchData']['tournamentId'];
      $matchResult = null;
      foreach ($AllMatches as $match) {
        $matchResult = $this->matchRepoObj->saveAllResults($match);
        $matchData = $matchResult['match_data'];
        $teamArray[$matchData['age_group_id']][] = $matchData['home_team_id'];
        $teamArray[$matchData['age_group_id']][] = $matchData['away_team_id'];
        // $competationId = $this->calculateCupLeagueTable($match['matchId']);
        $competitionIds[$matchData['age_group_id']][] = $matchData['competition_id'];
      }

      foreach ($competitionIds as $ageGroupId => $cids) {
        $lowerCompetitionId = min(array_unique($cids));
        $allCompetitionsIds = Competition::where('tournament_id', '=', $tournamentId)->where('tournament_competation_template_id', '=', $ageGroupId)->where('id', '>=', $lowerCompetitionId)->pluck('id')->toArray();
        foreach ($allCompetitionsIds as $id) {
          $data = ['tournamentId' => $tournamentId, 'competitionId' => $id];
          $this->refreshCompetitionStandings($data);
        }
      }
      foreach ($teamArray as $ageGroupId => $teamsList) {
        $teamsList = array_unique($teamsList);
        $matchData = array('teams'=>$teamsList,'tournamentId'=>$tournamentId,'ageGroupId'=>$ageGroupId,'teamId'=>true);
        $matchresult =  $this->matchRepoObj->checkTeamIntervalforMatches($matchData);
      }
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
       $competationId = $this->calculateCupLeagueTable($matchData->all()['matchData']['matchId']);

       $result = TempFixture::where('id',$matchData->all()['matchData']['matchId'])->first()->toArray();
        $tournamentId = $result['tournament_id'];
        $ageGroupId  = $result['age_group_id'];
        $teamsList =array($result['home_team'],$result['away_team']);

        $matchData = array('teams'=>$teamsList,'tournamentId'=>$tournamentId,'ageGroupId'=>$ageGroupId,'teamId'=>true);

        $matchresult =  $this->matchRepoObj->checkTeamIntervalforMatches($matchData);

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
    private function secondRoundElimination($singleFixture) {

      $age_category_id = $singleFixture->age_group_id;
      $tournament_id   = $singleFixture->tournament_id;

      // We have to find the record of it for winner and looser
      $match_number = $singleFixture->match_number;
      $match_number = explode(".",$match_number);
      $frs = explode("-",$match_number[0]);

      $val = $frs[count($frs)-1]."_".$match_number[1];
      // $val = $frs[count($frs)-1]."_".$match_number[count($match_number)-1];
     // print_r($match_number);exit;
      $home_team_score = $singleFixture->hometeam_score;
      $away_team_score = $singleFixture->awayteam_score;


      $winnerTeam = $singleFixture->home_team_name;
      $winnerId = $singleFixture->home_team;
      $takeTeam = null;
      // For Winner Conditions
      if($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
        $takeTeam = "home";

        if($singleFixture->match_winner == $singleFixture->away_team) {
          $takeTeam = "away";
        }
      } else if($home_team_score !== null && $away_team_score !== null) {
        $takeTeam = "home";

        if($home_team_score <  $away_team_score) {
          $takeTeam = "away";
        }
      }
      if($takeTeam == "away") {
        $winnerTeam = $singleFixture->away_team_name;
        $winnerId = $singleFixture->away_team;
      }

      $looserTeam = $singleFixture->home_team_name;
      $looserId = $singleFixture->home_team;
      $takeTeam = null;
      // For Looser Conditions
      if($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
        $takeTeam = "home";

        if($singleFixture->match_winner != $singleFixture->away_team) {
          $takeTeam = "away";
        }
      } else if($home_team_score !== null && $away_team_score !== null) { 
        $takeTeam = "home";

        if($home_team_score >=  $away_team_score) {
          $takeTeam = "away";
        }
      }
      if($takeTeam == "away") {
        $looserTeam = $singleFixture->away_team_name;
        $looserId = $singleFixture->away_team;
      }

      if(($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') || ($home_team_score !== null && $away_team_score !== null)) {
        // Now fire a query which gives two record Winner and Looser
        $results = DB::table('temp_fixtures')->where('age_group_id','=',$age_category_id)->where('tournament_id','=',$tournament_id)
        ->where(function($query) use ($val) {
          $query->whereRaw(DB::raw("match_number like '%(".$val."_WR)%' OR  match_number like '%(".$val."_LR)%' "));
        })->get();

        // here we get two records 1 for Winner and other for looser
        foreach($results as $record) {
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
      }

      return ;
    }
    private function calculateEliminationTeams($singleFixture) {

      //$singleFixture = $singleFixture[0];

      $tournament_id = $singleFixture->tournament_id;
      $ageGroupId = $singleFixture->age_group_id;
      //$compIds = $this->getCompeIds($singleFixture->competition_id);
      //print_r($compIds);exit;
      $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$tournament_id)
                // ->where('round','=' , 'Elimination')
                ->where('age_group_id','=',$ageGroupId)
                ->get();

      $matchArr =  array();
      $teams_arr = explode('.', $singleFixture->match_number);

      $teams = $teams_arr[count($teams_arr)-1];
      $selTeams = explode('-',$teams);
      $SelhomeTeam = $selTeams[0];
      $SelawayTeam = $selTeams[1];
      // echo "<pre>"; print_r($selTeams); echo "</pre>";
      foreach($matches as $match) {

        $matchNumber = explode('.',$match->match_number);
        //print_r($matchNumber);
        $matchTeams = $matchNumber[count($matchNumber)-1];
        $mtsTeams = explode('-',$matchTeams);
        //print_r($mtsTeams);
        // Teams For that Matches
        // echo "<pre>"; print_r($mtsTeams); echo "</pre>";
        $homeTeam = $mtsTeams[0];
        $awayTeam = $mtsTeams[1];
        // Get hometeam=1A awayTeam =2B
        // here we check it For 2nd round Eliminbation
        // echo "<pre>"; print_r($mtsTeams); echo "</pre>";

        // First For Winner
        $modifiedTeams = str_replace('-','_',$teams);
      //  echo 'Hi MOD Teams<br>';
        //print_r($modifiedTeams);exit;
        if (strpos($modifiedTeams, 'WR') !== false) {
          // echo "asd";echo $homeTeam;

          // echo "<pre>"; print_r($mtsTeams); echo "</pre>";
          $var = '';
          if($SelhomeTeam == $homeTeam ) {
            // echo "SDF";exit;
            $match1 = $match;

          }
          if($homeTeam[0] == '(') {
              if(isset($match1) && $match1 != ''){

                $this->secondRoundElimination($match1);
              }
            }
          if($SelawayTeam==$awayTeam) {
            $match2=$match;

          }
          if($awayTeam[strlen($awayTeam)-1]==')') {
              if(isset($match2) && $match2 != ''){
                $this->secondRoundElimination($match2);
              }
            }
          // here check for Multiple Value for detect the updated record value



        }

        if (strpos($modifiedTeams, 'LR') !== false) {
          // echo "<pre>"; print_r(); echo "</pre>";
          // $selTeams = explode('-',$teams);
          // $SelhomeTeam = $selTeams[0];
          // $SelawayTeam = $selTeams[1];


          $var = '';
          if($SelhomeTeam == $homeTeam ) {
            // here we get that Match
            $match1 = $match;

          }
          if($homeTeam[0] == '(') {
             if(isset($match1) && $match1 != ''){
              $this->secondRoundElimination($match1);
            }
          }
          if($SelawayTeam==$awayTeam) {
            $match2=$match;
          }
          if($awayTeam[strlen($awayTeam)-1]==')'){
              //echo 'false';exit;
             if(isset($match2) && $match2 != ''){
                $this->secondRoundElimination($match2);
              }
            }
          // here check for Multiple Value for detect the updated record value


        }

        $modifiedTeamsWinner = $modifiedTeams.'_WR';

        if($homeTeam  == $modifiedTeamsWinner) {
          $hometeamName = null;
          $homeTeamId = 0;
          $takeTeam = null;

          if($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
            $takeTeam = "home";

            if($singleFixture->match_winner == $singleFixture->away_team) {
              $takeTeam = "away";
            }
          } else if($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
            $takeTeam = "home";

            if($singleFixture->hometeam_score < $singleFixture->awayteam_score) {
              $takeTeam = "away";
            }
          }

          if($takeTeam == 'home') {
            $hometeamName = $singleFixture->home_team_name;
            $homeTeamId = $singleFixture->home_team;
          }
          if($takeTeam == 'away') {
            $hometeamName = $singleFixture->away_team_name;
            $homeTeamId = $singleFixture->away_team;
          }

          if($hometeamName === null && $homeTeamId == 0) {
            $fixture = TempFixture::where('id',$match->id)->first();
            $updateArray = [ 'home_team_name'=> $fixture->home_team_placeholder_name,'home_team'=>$homeTeamId];
            $fixture->update($updateArray);
          } else {
            $updateArray = ['home_team_name'=> $hometeamName,'home_team'=>$homeTeamId];
            DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
          }
        }
        if($awayTeam  == $modifiedTeamsWinner) {
          $awayteamName = null;
          $awayTeamId = 0;
          $takeTeam = null;

          if($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
            $takeTeam = "home";

            if($singleFixture->match_winner == $singleFixture->away_team) {
              $takeTeam = "away";
            }
          } else if($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
            $takeTeam = "home";

            if($singleFixture->hometeam_score < $singleFixture->awayteam_score) {
              $takeTeam = "away";
            }
          }

          if($takeTeam == 'home') {
            $awayteamName = $singleFixture->home_team_name;
            $awayTeamId = $singleFixture->home_team;
          }
          if($takeTeam == 'away') {
            $awayteamName = $singleFixture->away_team_name;
            $awayTeamId = $singleFixture->away_team;
          }

          if($awayteamName === null && $awayTeamId == 0) {
            $fixture = TempFixture::where('id',$match->id)->first();
            $updateArray = [ 'away_team_name'=> $fixture->away_team_placeholder_name,'away_team'=>$awayTeamId];
            $fixture->update($updateArray);
          } else {
            $updateArray = ['away_team_name'=> $awayteamName,'away_team'=>$awayTeamId];
            DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
          }
        }
        // For Looser
        $modifiedTeamsLooser = $modifiedTeams.'_LR';

        if($homeTeam  == $modifiedTeamsLooser) {
          $hometeamName = null;
          $homeTeamId = 0;
          $takeTeam = null;

          if($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
            $takeTeam = "home";

            if($singleFixture->match_winner != $singleFixture->away_team) {
              $takeTeam = "away";
            }
          } else if($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
            $takeTeam = "home";

            if($singleFixture->hometeam_score >= $singleFixture->awayteam_score) {
              $takeTeam = "away";
            }
          }

          if($takeTeam == 'home') {
            $hometeamName = $singleFixture->home_team_name;
            $homeTeamId = $singleFixture->home_team;
          }
          if($takeTeam == 'away') {
            $hometeamName = $singleFixture->away_team_name;
            $homeTeamId = $singleFixture->away_team;
          }

          if($hometeamName === null && $homeTeamId == 0) {
            $fixture = TempFixture::where('id',$match->id)->first();
            $updateArray = [ 'home_team_name'=> $fixture->home_team_placeholder_name,'home_team'=>$homeTeamId];
            $fixture->update($updateArray);
          } else {
            $updateArray = [ 'home_team_name'=> $hometeamName,'home_team'=>$homeTeamId];
            DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
          }
        }
        if($awayTeam  == $modifiedTeamsLooser) {
          $awayteamName = null;
          $awayTeamId = 0;
          $takeTeam = null;

          if($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
            $takeTeam = "home";

            if($singleFixture->match_winner != $singleFixture->away_team) {
              $takeTeam = "away";
            }
          } else if($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
            $takeTeam = "home";

            if($singleFixture->hometeam_score >= $singleFixture->awayteam_score) {
              $takeTeam = "away";
            }
          }

          if($takeTeam == 'home') {
            $awayteamName = $singleFixture->home_team_name;
            $awayTeamId = $singleFixture->home_team;
          }
          if($takeTeam == 'away') {
            $awayteamName = $singleFixture->away_team_name;
            $awayTeamId = $singleFixture->away_team;
          }

          if($awayteamName === null && $awayTeamId == 0) {
            $fixture = TempFixture::where('id',$match->id)->first();
            $updateArray = [ 'away_team_name'=> $fixture->away_team_placeholder_name,'away_team'=>$awayTeamId];
            $fixture->update($updateArray);
          } else {
            $updateArray = ['away_team_name'=> $awayteamName,'away_team'=>$awayTeamId];
            DB::table('temp_fixtures')->where('id',$match->id)->update($updateArray);
          }
        }
      }

      return $singleFixture->competition_id;
    }

    public function refreshStanding($data) {
      $data = $data['tournamentData'];
      $standingCount =  DB::table('match_standing')
                            ->where('tournament_id','=',$data['tournamentId'])
                            ->where('competition_id','=',$data['competitionId'])->count();

      $groupFixture = DB::table('temp_fixtures')->select('temp_fixtures.*')->where('tournament_id','=',$data['tournamentId'])->where('competition_id',$data['competitionId'])->get();

      foreach ($groupFixture as $key => $value) {
        $this->calculateCupLeagueTable($value->id, 0);
      }

      $standingResData = $this->matchRepoObj->getStanding($data);
      if ($standingResData) {
        return ['status_code' => '200', 'data' => $standingResData,'message' => 'Match Standing data'];
      }
    }

    public function calculateCupLeagueTable($id, $generatePositions = 1) {
        $ageCategoryId = 0;
        $competitionId = 0;
        $singleFixture = DB::table('temp_fixtures')->select('temp_fixtures.*')->where('id','=',$id)->get();
        $fix1=array();
        // dd($singleFixture );
        foreach($singleFixture as $singleFxture)
        {
          $competitionId = $fix1['CupFixture']['cupcompetition'] = $singleFxture->competition_id;
          $fix1['CupFixture']['hometeam'] = $singleFxture->home_team;
          $fix1['CupFixture']['awayteam'] = $singleFxture->away_team;
          $fix1['CupFixture']['tournamentId'] = $singleFxture->tournament_id;
          $fix1['CupFixture']['match_round'] = $singleFxture->round;
          $ageCategoryId = $fix1['CupFixture']['age_group_id'] = $singleFxture->age_group_id;
        }
        if( $fix1['CupFixture']['hometeam'] == 0 || $fix1['CupFixture']['awayteam'] == 0)
        {
          return $singleFxture->competition_id;
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
          $competitionId = $this->calculateEliminationTeams($singleFixture[0]);

          // changes for #247
          $competition = Competition::where('id', $singleFxture->competition_id)->first();
          if($competition->competation_type == 'Elimination' && $competition->actual_competition_type == 'Round Robin') {
              $this->generateStandingsForCompetitions($fix1, $cup_competition_id, $findTeams,'Elimination');
          }
          if($generatePositions == 1) {
            $this->updateCategoryPositions($competitionId, $ageCategoryId);
          }

          return $competitionId;
          // end #247
        }
        $comType = 'C';
        if ($comType == 'C') {
            // Manual standing insert - start
            $allCompetitions = Competition::where('tournament_id','=',$fix1['CupFixture']['tournamentId'])->where('tournament_competation_template_id','=',$fix1['CupFixture']['age_group_id'])->where('id','>',$cup_competition_id)->get();

            foreach($allCompetitions as $competition)
            {
              if($competition->is_manual_override_standing == 1) {
                $allCompetitionStandings = DB::table('match_standing')->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])->where('competition_id', '=', $competition->id)->get();

                foreach($allCompetitionStandings as $standing) {
                  $teamManualRanking = TeamManualRanking::where('tournament_id','=',$standing->tournament_id)->where('competition_id', '=', $standing->competition_id)->where('team_id', '=', $standing->team_id)->first();

                  if($teamManualRanking) {
                    $teamManualRanking->manual_order = $standing->manual_order;
                    $teamManualRanking->save();
                  } else {
                    $teamManualRanking = new TeamManualRanking();
                    $teamManualRanking->tournament_id = $standing->tournament_id;
                    $teamManualRanking->competition_id = $standing->competition_id;
                    $teamManualRanking->team_id = $standing->team_id;
                    $teamManualRanking->manual_order = $standing->manual_order;
                    $teamManualRanking->save();
                  }

                }
              }
            }
            // Manual standing insert - end

            $result = DB::table('match_standing')
                            ->join('competitions', 'match_standing.competition_id', '=', 'competitions.id')
                            ->where('match_standing.tournament_id','=',$fix1['CupFixture']['tournamentId'])
                            ->where('match_standing.competition_id','>',$cup_competition_id)
                            ->where('competitions.tournament_competation_template_id', '=', $fix1['CupFixture']['age_group_id'])->delete();


            // dd($result,$fix1['CupFixture']['tournamentId'],$cup_competition_id,$findTeams);
            $this->generateStandingsForCompetitions($fix1, $cup_competition_id, $findTeams, 'Round Robin');

        if($generatePositions == 1) {
          $this->updateCategoryPositions($competitionId, $ageCategoryId);
        }

        return $cup_competition_id;
      }
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

    private function TeamPMAssignKp($data)
    {
      // dd($data);
        $compId = $data['home']['competition_id'];
        $competition = Competition::find($compId);
        $tournament_id =  $competition->tournament_id;

        $cupId = $compId;

        //$cupRoundrobinData = $this->CupRoundrobin->find('first', array('conditions' => array('comp_id' => $cupId)));

        //$groupTeams = json_decode($cupRoundrobinData['CupRoundrobin']['groups'],true);
        $comp = DB::table('temp_fixtures')
                    // ->join('competitions','competitions.id','temp_fixtures.competition_id')
                    ->where('temp_fixtures.competition_id','=',$compId)->get();
        foreach ($comp as $key => $value) {
          $home_team_arr[] = $value->home_team;
          $away_team_arr[] = $value->away_team;
        }
        $teamList = array_unique(array_merge($home_team_arr,$away_team_arr));

         // $teams = DB::table('teams')->where('competation_id','=',$compId)->get();
         $teams = DB::table('teams')->whereIn('id',$teamList)->get();
           // dd($teams);
        // $team_ids = $data['home']['team_id'].','.$data['away']['team_id'];
        // $teams = DB::table('teams')->whereIn('id',[$data['home']['team_id'],$data['away']['team_id']])->get();
        // print_r($teams);exit;
        $defaultArray = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,
          'home_goal' => 0,'away_goal' => 0,'goal_difference' => 0,'Total' => 0,
          'manual_override' => 0,'group_winner' => 0, 'manual_order' => 0);
       // foreach ($groupTeams as $gkey => $gvvalue) {
        //    $i =1;
            foreach ($teams as $gkey => $gvalue) {
                //$teamExist = $this->CupLeagueTable->find('first', array('conditions'
                // => array('comp_id' => $cupId, 'team_id' => $gvalue)));
                // check in match standing table for that team and Group Id
                $teamExist = DB::table('match_standing')
                        ->Join('teams','teams.id','=','match_standing.team_id')
                        ->Join('competitions','match_standing.competition_id','=','competitions.id')
                        ->select('teams.*','match_standing.*','competitions.name as compName')
                              ->where('match_standing.competition_id',$cupId)
                               ->where('teams.id',$gvalue->id)
                               ->get()->first();
               // $winPoints = 3; $losePoints =0;$drawPoints=1;

                $tournamentCompetationTemplatesRecord = TournamentCompetationTemplates::where('id',$competition->tournament_competation_template_id)->first();
                $winPoints = $tournamentCompetationTemplatesRecord->win_point;
                $losePoints = $tournamentCompetationTemplatesRecord->loss_point;
                $drawPoints = $tournamentCompetationTemplatesRecord->draw_point;

                $assigned_group =  '';
                if($teamExist){
                  $group = explode('-',$teamExist->compName);
                  $assigned_group = $group[count($group)-2].'-'.$group[count($group)-1];
                }else{
                   return;
                }
                // $group = explode('-',$teamExist->compName);
                // echo "<pre>"; print_r($group); echo "</pre>";
                // $assigned_group = $group[2].'-'.$group[3];
                 // print_r($teamExist);
                if ($teamExist){

                    $calculatedArray[$compId][$gvalue->id]['Played'] = $teamExist->played;
                    $calculatedArray[$compId][$gvalue->id]['Won'] = $teamExist->won;
                    $calculatedArray[$compId][$gvalue->id]['Lost'] = $teamExist->lost;
                    $calculatedArray[$compId][$gvalue->id]['Draw'] = $teamExist->draws;
                    $calculatedArray[$compId][$gvalue->id]['home_goal'] = $teamExist->goal_for;
                    $calculatedArray[$compId][$gvalue->id]['away_goal'] = $teamExist->goal_against;
                    $total = ( ( (int)$teamExist->won * $winPoints ) + ( (int)$teamExist->draws * $drawPoints) )  + ( (int)$teamExist->lost * $losePoints);

                    $goal_difference = ( (int)$teamExist->goal_for  - (int)$teamExist->goal_against );
                    $calculatedArray[$compId][$gvalue->id]['goal_ratio']  = $teamExist->played > 0 ? $teamExist->goal_for / $teamExist->played : 0;
                    $calculatedArray[$compId][$gvalue->id]['goal_difference'] = $goal_difference;
                    $calculatedArray[$compId][$gvalue->id]['Total'] = $total;
                    $calculatedArray[$compId][$gvalue->id]['manual_order'] = $teamExist->manual_order;
                    $calculatedArray[$compId][$gvalue->id]['teamid'] = $gvalue->id;
                     $calculatedArray[$compId][$gvalue->id]['teamName'] =
                     $teamExist->name;
                     $calculatedArray[$compId][$gvalue->id]['teamGroup'] =
                     $assigned_group;
                      $calculatedArray[$compId][$gvalue->id]['teamGroupName'] =
                     $teamExist->group_name;
                      $calculatedArray[$compId][$gvalue->id]['teamAgeGroup'] =
                     $teamExist->age_group_id;
                     $groupAlphabet = explode('-',$assigned_group);
                     $groupAlphabet = $groupAlphabet[1];
               //  $calculatedArray[$compId][$gvalue->id]['teamAgeGroupPlaceHolder']
               //  =  $i.$groupAlphabet;
                 //   $calculatedArray[$gkey][$gvalue]['manual_override'] =  $teamExist['CupLeagueTable']['manual_override'];
                 //   $calculatedArray[$gkey][$gvalue]['group_winner'] =  $teamExist['CupLeagueTable']['group_winner'];
                } else {
                  // dd($teamExist,$gvalue->id,$cupId);
                  // echo "<pre>"; print_r($teamExist); echo "</pre>";exit;
                    $calculatedArray[$compId][$gvalue->id] = $defaultArray;
                    $calculatedArray[$compId][$gvalue->id]['teamid'] = $gvalue->id;
                    $calculatedArray[$compId][$gvalue->id]['teamName'] =
                     $teamExist->name;
                    $calculatedArray[$compId][$gvalue->id]['teamGroup'] =
                     $assigned_group;
                   $calculatedArray[$compId][$gvalue->id]['teamGroupName'] =
                     $teamExist->group_name;
                      $calculatedArray[$compId][$gvalue->id]['teamAgeGroup'] =
                     $teamExist->age_group_id;
                     $groupAlphabet = explode('-',$assigned_group);
                     $groupAlphabet = $groupAlphabet[1];
                // $calculatedArray[$compId][$gvalue->id]['teamAgeGroupPlaceHolder']
               //  =  $i.$groupAlphabet;

                }
              //  $i++;
            }
       // }
       // echo 'Before Sort';

      //  echo 'After Sort';
            dd($calculatedArray);
        $for_override_condition = array();
        foreach ($calculatedArray as $ckey => $cvalue) {
            $manual_order = $mid = $cid = $did = $eid = $overrride = $group_winner = $matchesWon = $goalRatio = $headToHead = $teamName = array();
            
            foreach ($cvalue as $cckey => $ccvalue) {
               $manual_order[$cckey]  = (int)$ccvalue['manual_order'];
               $mid[$cckey]  = (int)$ccvalue['Total'];
               // $cid[$cckey]  = (int)$ccvalue['Played'];
              
               $did[$cckey]  = (int)$ccvalue['goal_difference'];
               $eid[$cckey]  = (int)$ccvalue['home_goal'];
               $matchesWon[$cckey]  = (int)$ccvalue['Won'];
               $goalRatio[$cckey]  = $ccvalue['Played'] > 0 ? $ccvalue['home_goal'] / $ccvalue['Played'] : 0;
               $teamName[$cckey] =  $ccvalue['teamName'];
              // $overrride[$cckey]  = (int)$ccvalue['manual_override'];
              // $group_winner[$cckey]  = (int)$ccvalue['group_winner'];
              // $for_override_condition[$ckey][$cckey] = (int)$ccvalue['manual_override'];
            }

            $checkResultEntered = DB::table('temp_fixtures')
            ->where('tournament_id',$tournament_id)
            ->where('competition_id',$compId)->where('round','Round Robin')
            ->where(function ($query) {
                $query->whereNull('hometeam_score')
                      ->orWhereNull('awayteam_score');
            })->count();

            $params = [];
            $rules = $tournamentCompetationTemplatesRecord->rules;

            $head_to_head = false;
            $check_head_to_head_with_key = '';
            $remain_head_to_head_with_key = '';
            for($i=0; $i<count($rules); $i++) {
              $rule = $rules[$i];

              if($rule['checked'] == false || ( $rule['key'] != 'head_to_head' && $head_to_head == true)) {

                if (  $rule['checked'] == true && ( $rule['key'] != 'head_to_head' && $head_to_head == true )  )
                {
                  if($rule['key'] == 'goal_difference') {
                    $remain_head_to_head_with_key .= '|goal_difference';
                  }
                  if($rule['key'] == 'goals_for') {
                    $remain_head_to_head_with_key .= '|home_goal';
                  }
                  if($rule['key'] == 'matches_won') {
                    $remain_head_to_head_with_key .= '|Won';
                  }
                  if($rule['key'] == 'goal_ratio') {
                    $remain_head_to_head_with_key .= '|goal_ratio';
                  }
                }

                continue;
              }
              
              if($rule['key'] == 'match_points') {
                $params[] = $mid;
                $params[] = SORT_DESC;
                $check_head_to_head_with_key .= '|Total';
              }
              
              if($rule['key'] == 'head_to_head') {
                if($checkResultEntered > 0)
                {
                  $params[] = $teamName;
                  $params[] = SORT_ASC;
                  $check_head_to_head_with_key .= '|teamName';
                }
                else
                {
                  $head_to_head = true;
                }
               
              }

              if($rule['key'] == 'goal_difference') {
                $params[] = $did;
                $params[] = SORT_DESC;
                $check_head_to_head_with_key .= '|goal_difference';
              }
              if($rule['key'] == 'goals_for') {
                $params[] = $eid;
                $params[] = SORT_DESC;
                $check_head_to_head_with_key .= '|home_goal';
              }
              if($rule['key'] == 'matches_won') {
                $params[] = $matchesWon;
                $params[] = SORT_DESC;
                $check_head_to_head_with_key .= '|Won';
              }
              if($rule['key'] == 'goal_ratio') {
                $params[] = $goalRatio;
                $params[] = SORT_DESC;
                $check_head_to_head_with_key .= '|goal_ratio';
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

            $params[] = &$cvalue;
            if($competition->is_manual_override_standing == 1) {
              $params = array_merge(array($manual_order, SORT_ASC), $params);
            }

            array_multisort(...$params);
            $calculatedArray[$ckey] = $cvalue;
        }


        if ( $head_to_head )
        {

          $calculatedArray = array_shift($calculatedArray);
          list($calculatedArray,$sort_head_to_head) = $this->matchRepoObj->sortByHeadtoHead($calculatedArray,$check_head_to_head_with_key,$tournament_id,$compId,$tournamentCompetationTemplatesRecord,$remain_head_to_head_with_key);

          if ( $sort_head_to_head == '1') {
            $head_to_head_position_sorting = array();    
            $internal_head_to_head_position_sorting = array();         
            foreach ($calculatedArray as $comp_stand_key => $comp_stand_value) {
              $head_to_head_position_sorting[$comp_stand_key] = (int)$comp_stand_value['head_to_head_position'];
              $internal_head_to_head_position_sorting[$comp_stand_key] = (int)$comp_stand_value['internal_head_to_head_position'];
            }
            array_multisort($internal_head_to_head_position_sorting,SORT_ASC,$calculatedArray);
          }

          $setCalculatedArray[$cupId] = $calculatedArray;
          $calculatedArray = $setCalculatedArray;
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
        $matches = $reportQuery;
        // print_r($matches);exit;
        //print_r($matches);exit;
        // dd($calculatedArray[$cupId]);
        if($matches) {
           foreach($matches as $key=>$match) {
            //$templateData = json_decode($match->JsonData,true);
            // echo "<pre>"; print_r($match); echo "</pre>";
            $exmatchNumber = explode('.',$match->MatchNumber);
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
    private function TeamPMAssign($data)
    {
        $compId = $data['home']['competition_id'];
        $cupId = $compId;

        //$cupRoundrobinData = $this->CupRoundrobin->find('first', array('conditions' => array('comp_id' => $cupId)));

        //$groupTeams = json_decode($cupRoundrobinData['CupRoundrobin']['groups'],true);
        $teams = DB::table('teams')->where('competation_id','=',$compId)->get();
        // print_r($teams);exit;
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
                //$winPoints = 3; $losePoints =0;$drawPoints=1;

                $tournamentCompetationTemplatesRecord = TournamentCompetationTemplates::where('id',$teamExist->age_group_id)->get()->first();

                $winPoints = $tournamentCompetationTemplatesRecord->win_point;
                $losePoints = $tournamentCompetationTemplatesRecord->loss_point;
                $drawPoints = $tournamentCompetationTemplatesRecord->draw_point;
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

      $matches = DB::table('temp_fixtures')
      ->where('competition_id',$competationId)
      ->where('round','=','Round Robin')
      ->whereRaw(Db::raw('(hometeam_score IS NULL OR awayteam_score IS NULL)'));

      if($matches->exists()) {
        //echo 'hellofalse';
        return false;
      } else {
        //echo 'hellotrue';
        return true;
      }
    }

    public function saveStandingsManually($request) {
        $this->matchRepoObj->saveStandingsManually($request->all()['data']);
        return ['status_code' => '200', 'message' => 'Ranking has been updated successfully.'];
    }

    public function generateStandingsForCompetitions($fix1, $cup_competition_id, $findTeams, $competitionType) {
      $matches = DB::table('temp_fixtures')
                ->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])
                ->where('competition_id','=',$cup_competition_id)
                ->where(function ($query) use ($findTeams)  {
                    $query ->whereIn('away_team',$findTeams)
                         ->orWhereIn('home_team',$findTeams);
                })->where('round','=' , $competitionType)->get();

      $fixtu = array();
      foreach($matches as $key1=>$match)
      {
        $fixtu[$key1]['CupFixture']['hometeamscore'] = (string)$match->hometeam_score;
        $fixtu[$key1]['CupFixture']['awayteamscore'] = (string)$match->awayteam_score;

        $fixtu[$key1]['CupFixture']['hometeam'] = $match->home_team;
        $fixtu[$key1]['CupFixture']['awayteam'] = $match->away_team;
        $fixtu[$key1]['CupFixture']['HomeTeamScoreAfterExtraTime']='';
      }

      $comp_fixtures = $fixtu;
      $ageGroupList = array();

      $ageGroupList[$fix1['CupFixture']['hometeam']] = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,'home_goal' => 0,'away_goal'=>0);

      $ageGroupList[$fix1['CupFixture']['awayteam']] = array('Played' => 0,'Won' => '0', 'Lost' => 0,'Draw' => 0,'away_goal' => 0,'home_goal'=>0);

      foreach ($comp_fixtures as $key => $fix) {
        $winnerTeam = 'nd';

        if($fix['CupFixture']['hometeamscore'] != '' && ($fix['CupFixture']['awayteamscore'] != '')&& empty($fix['CupFixture']['Abandoned'])) {
            if($fix['CupFixture']['hometeamscore']  == $fix['CupFixture']['awayteamscore']){
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
            } else {
                if($fix['CupFixture']['hometeamscore'] > $fix['CupFixture']['awayteamscore']){
                  $winnerTeam = $fix['CupFixture']['hometeam'];
                  $home = true;
                } else {
                  $winnerTeam = $fix['CupFixture']['awayteam'];
                }
            }
        } else {
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

        if ($winnerTeam != 'nd') {
            if ( $winnerTeam == -1) {
              // 1. check if has same Home id
              if ($fix1['CupFixture']['hometeam'] == $fix['CupFixture']['hometeam']) {
                  $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                  $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] + 1;
                  if ($fix['CupFixture']['hometeamscore'] != '') {
                      $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                  }
                  if ($fix['CupFixture']['awayteamscore'] != '') {
                      $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                  }
              } else{
                  if ($fix1['CupFixture']['hometeam'] == $fix['CupFixture']['awayteam'] ) {
                      $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] =
                      (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                      $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] + 1;

                      if ($fix['CupFixture']['awayteamscore'] != '') {
                        $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                      }

                      if ($fix['CupFixture']['hometeamscore'] != '') {
                        $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                      }
                  }
              }

              // 2. check if has same awayteam
              if ($fix1['CupFixture']['awayteam'] == $fix['CupFixture']['awayteam']) {
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
                  if ($fix1['CupFixture']['awayteam'] == $fix['CupFixture']['hometeam'] ) {
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
            } else{
                // 1 if home team is Winner
                if ( $winnerTeam == $fix1['CupFixture']['hometeam']) {
                    $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] =
                    (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;

                    $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Won'] + 1;

                    if ($fix1['CupFixture']['hometeam'] == $fix['CupFixture']['hometeam']) {
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
                } else {
                    if ($fix1['CupFixture']['hometeam'] == $fix['CupFixture']['hometeam']) {
                      $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Played'] + 1;
                      $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['Lost']+ 1;

                      if ($fix['CupFixture']['hometeamscore'] != '') {
                        $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                      }

                      if ($fix['CupFixture']['awayteamscore'] != '') {
                        $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                      }
                    }

                    if ($fix1['CupFixture']['hometeam'] == $fix['CupFixture']['awayteam']) {
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
                if ($winnerTeam == $fix1['CupFixture']['awayteam']) {
                    $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;

                    $ageGroupList[$fix1['CupFixture']['awayteam']]['Won'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Won'] + 1;

                    if ($fix1['CupFixture']['awayteam'] == $fix['CupFixture']['hometeam']) {
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
                } else {
                    if ($fix1['CupFixture']['awayteam'] == $fix['CupFixture']['awayteam']) {
                        $ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Played'] + 1;
                        $ageGroupList[$fix1['CupFixture']['awayteam']]['Lost'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['Lost']+ 1;
                        if ($fix['CupFixture']['awayteamscore'] != '') {
                          $ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['home_goal'] + (int)$fix['CupFixture']['awayteamscore'];
                        }
                        if ($fix['CupFixture']['hometeamscore'] != '') {
                          $ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] = (int)$ageGroupList[$fix1['CupFixture']['awayteam']]['away_goal'] + (int)$fix['CupFixture']['hometeamscore'];
                        }
                    }

                    if ($fix1['CupFixture']['awayteam'] == $fix['CupFixture']['hometeam']) {
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

      $homeTeamExist = DB::table('match_standing')
                      ->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])
                      ->where('competition_id','=',$cup_competition_id)
                      ->where('team_id',$fix1['CupFixture']['hometeam'])
                      ->get()->first();

      $tournamentCompetationTemplatesRecord = TournamentCompetationTemplates::where('id', $fix1['CupFixture']['age_group_id'])->get()->first();
      $winningPoints = $tournamentCompetationTemplatesRecord->win_point;
      $losePoints = $tournamentCompetationTemplatesRecord->loss_point;
      $drawPoints = $tournamentCompetationTemplatesRecord->draw_point;
      // $winningPoints = 3;$drawPoints = 1;$losePoints = 0;
      $sendData = array();

      if ($homeTeamExist){
          $data = array();

          $data['points'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'] * $winningPoints + $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'] * $drawPoints + $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'] * $losePoints;
          $data['played'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Played'];
          $data['won'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Won'];
          $data['draws'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Draw'];
          $data['lost'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['Lost'];
          $data['goal_for'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['home_goal'];
          $data['goal_against'] = $ageGroupList[$fix1['CupFixture']['hometeam']]['away_goal'];

          DB::table('match_standing')->where('id',$homeTeamExist->id)->update($data);
          $sendData['home'] = $data;
          $sendData['home']['competition_id'] = $homeTeamExist->competition_id;
          $sendData['home']['team_id'] = $homeTeamExist->team_id;
      } else {
          $data3 = array();

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

          $teamManualRanking = TeamManualRanking::where('tournament_id','=',$fix1['CupFixture']['tournamentId'])->where('competition_id', '=', $cup_competition_id)->where('team_id', '=', $fix1['CupFixture']['hometeam'])->first();
          if($teamManualRanking) {
            $data3['manual_order'] = $teamManualRanking->manual_order;
            $teamManualRanking->delete();
          }

          DB::table('match_standing')->insert($data3);
          $sendData['home'] = $data3;
      }

      $awayTeamExist = DB::table('match_standing')
                      ->where('tournament_id','=',$fix1['CupFixture']['tournamentId'])
                      ->where('competition_id','=',$cup_competition_id)
                      ->where('team_id',$fix1['CupFixture']['awayteam'])
                      ->get()->first();

      if ($awayTeamExist){
          $data1 = array();

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
          $data2 = array();

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

          $teamManualRanking = TeamManualRanking::where('tournament_id','=',$fix1['CupFixture']['tournamentId'])->where('competition_id', '=', $cup_competition_id)->where('team_id', '=', $fix1['CupFixture']['awayteam'])->first();
          if($teamManualRanking) {
            $data2['manual_order'] = $teamManualRanking->manual_order;
            $teamManualRanking->delete();
          }

          DB::table('match_standing')->insert($data2);
          $sendData['away'] = $data2;

      }

      $this->TeamPMAssignKp($sendData);
    }

    public function processMatch($data, $match)
    {
        $roundName = $data['roundName'];
        $allTemplateMatchNumber = $data['allTemplateMatchNumber'];
        $notToAllowRoundOne = isset($data['notToAllowRoundOne']) ? $data['notToAllowRoundOne'] : false;

        $updatedMatchDetail = $match;
        $matchNumber = $updatedMatchDetail['match_number'];
        $splittedMatchNumber = explode('.', $matchNumber);
        $splittedMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedMatchNumber[1]));
        $splittedMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedMatchNumber[2]));
        $homeAwayTeamPlaceHolder = explode('-', $splittedMatchNumber[3]);
        $homeTeamPlaceHolder = $homeAwayTeamPlaceHolder[0];
        $awayTeamPlaceHolder = $homeAwayTeamPlaceHolder[1];

        if($roundName == 'Round 1' && $notToAllowRoundOne == false) {
            $splittedMatchNumber[3] = '@HOME-@AWAY';
            $displayHomeTeamPlaceHolderName = $homeAwayTeamPlaceHolder[0];
            $displayAwayTeamPlaceHolderName = $homeAwayTeamPlaceHolder[1];
        }

        if($roundName != 'Round 1' || $notToAllowRoundOne) {
            if(strpos($homeTeamPlaceHolder, '(') !== false && strpos($awayTeamPlaceHolder, '(') !== false) {
                $bracketStarted = false;

                // For home team
                $isWinnerOrLooser = null;
                if((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                    $isWinnerOrLooser = '_WR';
                }
                if((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                    $isWinnerOrLooser = '_LR';
                }

                $changedHomeTeamPlaceHolder = str_replace('(', '', $homeTeamPlaceHolder);
                $changedHomeTeamPlaceHolder = str_replace(')', '', $changedHomeTeamPlaceHolder);
                $changedHomeTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedHomeTeamPlaceHolder));
                $changedHomeTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedHomeTeamPlaceHolder[0]));
                $changedHomeTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedHomeTeamPlaceHolder[1]));

                $displayHomeTeamPlaceHolderName = $changedHomeTeamPlaceHolder[0] . '.' . $changedHomeTeamPlaceHolder[1];

                if($isWinnerOrLooser == '_WR') {
                    $splittedMatchNumber[3] = 'wrs.(@HOME';
                    $bracketStarted = true;
                } else if($isWinnerOrLooser == '_LR') {
                    $splittedMatchNumber[3] = 'lrs.(@HOME';
                    $bracketStarted = true;
                }

                // For away team
                $isWinnerOrLooser = null;
                if((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                    $isWinnerOrLooser = '_WR';
                }
                if((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                    $isWinnerOrLooser = '_LR';
                }

                $changedAwayTeamPlaceHolder = str_replace('(', '', $awayTeamPlaceHolder);
                $changedAwayTeamPlaceHolder = str_replace(')', '', $changedAwayTeamPlaceHolder);
                $changedAwayTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedAwayTeamPlaceHolder));
                $changedAwayTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedAwayTeamPlaceHolder[0]));
                $changedAwayTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedAwayTeamPlaceHolder[1]));

                $displayAwayTeamPlaceHolderName = $changedAwayTeamPlaceHolder[0] . '.' . $changedAwayTeamPlaceHolder[1];

                if($bracketStarted) {
                    $splittedMatchNumber[3] .= '-@AWAY)';
                } else {
                    if($isWinnerOrLooser == '_WR') {
                        $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                    } else if($isWinnerOrLooser == '_LR') {
                        $splittedMatchNumber[3] .= '-lrs.(@AWAY)';
                    }
                }
            } else if(strpos($homeTeamPlaceHolder, '(') === false && strpos($awayTeamPlaceHolder, '(') === false) {
                if(strpos($homeTeamPlaceHolder, '_WR') === false && strpos($homeTeamPlaceHolder, '_LR') === false && strpos($awayTeamPlaceHolder, '_WR') === false && strpos($awayTeamPlaceHolder, '_LR') === false) {
                    $splittedMatchNumber[3] = '@HOME-@AWAY';
                    $displayMatchNumber = $splittedMatchNumber[0].'.'.$splittedMatchNumber[1].'.'.$splittedMatchNumber[2].'.'.$splittedMatchNumber[3];

                    $displayHomeTeamPlaceHolderName = '#' . $homeAwayTeamPlaceHolder[0];
                    $displayAwayTeamPlaceHolderName = '#' . $homeAwayTeamPlaceHolder[1];
                } else if(strpos($homeTeamPlaceHolder, '_WR') !== false && strpos($awayTeamPlaceHolder, '_WR') !== false) {
                    $splittedMatchNumber[3] = 'wrs.(@HOME-@AWAY)';
                    $displayMatchNumber = $splittedMatchNumber[0].'.'.$splittedMatchNumber[1].'.'.$splittedMatchNumber[2].'.'.$splittedMatchNumber[3];

                    // Get home placeholder
                    $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace('_WR', '', $homeTeamPlaceHolder));
                    $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchHomeTeamPlaceHolder) {
                        if(strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if(count($searchHomeTeamMatchNumber) == 1) {
                        $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                        $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                        $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                        $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1] . '.' . $splittedSearchHomeTeamMatchNumber[2];
                    } else {
                        echo "issue found" . $homeTeamPlaceHolder;
                    }

                    // Get away placeholder
                    $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace('_WR', '', $awayTeamPlaceHolder));
                    $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchAwayTeamPlaceHolder) {
                        if(strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if(count($searchAwayTeamMatchNumber) == 1) {
                        $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                        $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                        $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                        $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1] . '.' . $splittedSearchAwayTeamMatchNumber[2];
                    } else {
                        echo "issue found" . $awayTeamPlaceHolder;
                    }
                } else if(strpos($homeTeamPlaceHolder, '_LR') !== false && strpos($awayTeamPlaceHolder, '_LR') !== false) {
                    $splittedMatchNumber[3] = 'lrs.(@HOME-@AWAY)';
                    $displayMatchNumber = $splittedMatchNumber[0].'.'.$splittedMatchNumber[1].'.'.$splittedMatchNumber[2].'.'.$splittedMatchNumber[3];

                    // Get home placeholder
                    $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace('_LR', '', $homeTeamPlaceHolder));
                    $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchHomeTeamPlaceHolder) {
                        if(strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if(count($searchHomeTeamMatchNumber) == 1) {
                        $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                        $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                        $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                        $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1] . '.' . $splittedSearchHomeTeamMatchNumber[2];
                    } else {
                        echo "issue found" . $homeTeamPlaceHolder;
                    }

                    // Get away placeholder
                    $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace('_LR', '', $awayTeamPlaceHolder));
                    $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchAwayTeamPlaceHolder) {
                        if(strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if(count($searchAwayTeamMatchNumber) == 1) {
                        $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                        $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                        $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                        $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1] . '.' . $splittedSearchAwayTeamMatchNumber[2];
                    } else {
                        echo "issue found" . $awayTeamPlaceHolder;;
                    }
                } else if(strpos($homeTeamPlaceHolder, '_WR') !== false || strpos($homeTeamPlaceHolder, '_LR') !== false || strpos($awayTeamPlaceHolder, '_WR') !== false || strpos($awayTeamPlaceHolder, '_LR') !== false) {
                    $bracketStarted = false;
                    if((strpos($homeTeamPlaceHolder, '_WR') !== false || strpos($homeTeamPlaceHolder, '_LR') !== false)) {

                        $isWinnerOrLooser = null;
                        if((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get home placeholder
                        $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $homeTeamPlaceHolder));
                        $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchHomeTeamPlaceHolder) {
                            if(strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if(count($searchHomeTeamMatchNumber) == 1) {
                            $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                            $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                            $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                            $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1] . '.' . $splittedSearchHomeTeamMatchNumber[2];

                            if($isWinnerOrLooser == '_WR') {
                                $splittedMatchNumber[3] = 'wrs.(@HOME';
                                $bracketStarted = true;
                            } else if($isWinnerOrLooser == '_LR') {
                                $splittedMatchNumber[3] = 'lrs.(@HOME';
                                $bracketStarted = true;
                            }
                        } else {
                            echo "issue found";
                        }
                    } else {
                        $displayHomeTeamPlaceHolderName = '#' . $homeAwayTeamPlaceHolder[0];
                        $splittedMatchNumber[3] = '@HOME';
                    }

                    if((strpos($awayTeamPlaceHolder, '_WR') !== false || strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                        $isWinnerOrLooser = null;
                        if((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get away placeholder
                        $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $awayTeamPlaceHolder));
                        $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchAwayTeamPlaceHolder) {
                            if(strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if(count($searchAwayTeamMatchNumber) == 1) {
                            $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                            $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                            $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                            $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1] . '.' . $splittedSearchAwayTeamMatchNumber[2];

                            if($bracketStarted) {
                                $splittedMatchNumber[3] .= '-@AWAY)';
                            } else {
                                if($isWinnerOrLooser == '_WR') {
                                    $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                                } else if($isWinnerOrLooser == '_LR') {
                                    $splittedMatchNumber[3] .= '-lrs.(@AWAY)';
                                }
                            }
                        } else {
                            echo "issue found";
                        }
                    } else {
                        $displayAwayTeamPlaceHolderName = '#' . $homeAwayTeamPlaceHolder[1];
                        $splittedMatchNumber[3] = '-@AWAY';
                    }
                }
            } else if(strpos($homeTeamPlaceHolder, '(') !== false || strpos($awayTeamPlaceHolder, '(') !== false) {
                $bracketStarted = false;
                if(strpos($homeTeamPlaceHolder, '(') === false) {
                    if((strpos($homeTeamPlaceHolder, '_WR') !== false || strpos($homeTeamPlaceHolder, '_LR') !== false)) {

                        $isWinnerOrLooser = null;
                        if((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get home placeholder
                        $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $homeTeamPlaceHolder));
                        $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchHomeTeamPlaceHolder) {
                            if(strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if(count($searchHomeTeamMatchNumber) == 1) {
                            $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                            $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                            $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                            $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1] . '.' . $splittedSearchHomeTeamMatchNumber[2];

                            if($isWinnerOrLooser == '_WR') {
                                $splittedMatchNumber[3] = 'wrs.(@HOME';
                                $bracketStarted = true;
                            } else if($isWinnerOrLooser == '_LR') {
                                $splittedMatchNumber[3] = 'lrs.(@HOME';
                                $bracketStarted = true;
                            }
                        } else {
                            echo "issue found" . $homeTeamPlaceHolder;
                        }

                    } else {
                        $displayHomeTeamPlaceHolderName = '#' . $homeAwayTeamPlaceHolder[0];
                        $splittedMatchNumber[3] = '@HOME';
                    }
                } else {
                    $isWinnerOrLooser = null;
                    if((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                        $isWinnerOrLooser = '_WR';
                    }
                    if((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                        $isWinnerOrLooser = '_LR';
                    }

                    $changedHomeTeamPlaceHolder = str_replace('(', '', $homeTeamPlaceHolder);
                    $changedHomeTeamPlaceHolder = str_replace(')', '', $changedHomeTeamPlaceHolder);
                    $changedHomeTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedHomeTeamPlaceHolder));
                    $changedHomeTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedHomeTeamPlaceHolder[0]));
                    $changedHomeTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedHomeTeamPlaceHolder[1]));

                    $displayHomeTeamPlaceHolderName = $changedHomeTeamPlaceHolder[0] . '.' . $changedHomeTeamPlaceHolder[1];


                    if($isWinnerOrLooser == '_WR') {
                        $splittedMatchNumber[3] = 'wrs.(@HOME';
                        $bracketStarted = true;
                    } else if($isWinnerOrLooser == '_LR') {
                        $splittedMatchNumber[3] = 'lrs.(@HOME';
                        $bracketStarted = true;
                    }
                }

                if(strpos($awayTeamPlaceHolder, '(') === false) {
                    if((strpos($awayTeamPlaceHolder, '_WR') !== false || strpos($awayTeamPlaceHolder, '_LR') !== false)) {

                        $isWinnerOrLooser = null;
                        if((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get away placeholder
                        $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $awayTeamPlaceHolder));
                        $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function($value) use ($searchAwayTeamPlaceHolder) {
                            if(strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if(count($searchAwayTeamMatchNumber) == 1) {
                            $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                            $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                            $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                            $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1] . '.' . $splittedSearchAwayTeamMatchNumber[2];

                            if($bracketStarted) {
                                $splittedMatchNumber[3] .= '-@AWAY)';
                            } else {
                                if($isWinnerOrLooser == '_WR') {
                                    $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                                } else if($isWinnerOrLooser == '_LR') {
                                    $splittedMatchNumber[3] .= '-lrs.(@AWAY)';
                                }
                            }
                        } else {
                            echo "issue found" . $awayTeamPlaceHolder;
                        }
                    } else {
                        $displayAwayTeamPlaceHolderName = '#' . $homeAwayTeamPlaceHolder[1];

                        if($bracketStarted) {
                            $splittedMatchNumber[3] .= ')-@AWAY';
                        } else {
                            $splittedMatchNumber[3] .= '-@AWAY';
                        }
                    }
                } else {
                    $isWinnerOrLooser = null;
                    if((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                        $isWinnerOrLooser = '_WR';
                    }
                    if((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                        $isWinnerOrLooser = '_LR';
                    }

                    $changedAwayTeamPlaceHolder = str_replace('(', '', $awayTeamPlaceHolder);
                    $changedAwayTeamPlaceHolder = str_replace(')', '', $changedAwayTeamPlaceHolder);
                    $changedAwayTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedAwayTeamPlaceHolder));
                    $changedAwayTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedAwayTeamPlaceHolder[0]));
                    $changedAwayTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedAwayTeamPlaceHolder[1]));

                    $displayAwayTeamPlaceHolderName = $changedAwayTeamPlaceHolder[0] . '.' . $changedAwayTeamPlaceHolder[1];


                    if($bracketStarted) {
                        $splittedMatchNumber[3] .= '-@AWAY)';
                    } else {
                        if($isWinnerOrLooser == '_WR') {
                            $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                        } else if($isWinnerOrLooser == '_LR') {
                            $splittedMatchNumber[3] .= '-lrs.(@AWAY)';
                        }
                    }
                }
            }
        }

        $displayMatchNumber = $splittedMatchNumber[0].'.'.$splittedMatchNumber[1].'.'.$splittedMatchNumber[2].'.'.$splittedMatchNumber[3];

        $updatedMatchDetail['display_match_number'] = $displayMatchNumber;
        $updatedMatchDetail['display_home_team_placeholder_name'] = $displayHomeTeamPlaceHolderName;
        $updatedMatchDetail['display_away_team_placeholder_name'] = $displayAwayTeamPlaceHolderName;

        return $updatedMatchDetail;
    }

    public function insertPositionsForPlacingMatches()
    {
        $files = File::allFiles('templates');
        foreach ($files as $file)
        {
            $allTemplateMatchNumber = [];
            $filePath = (string)$file;
            $updatedFilePath = str_replace('templates/', 'updatedtemplates/', $filePath);
            $json = json_decode(file_get_contents($filePath), true);
            $updatedJson = $json;

            $allRounds = $json['tournament_competation_format']['format_name'];
            $allUpdatedRounds = $allRounds;
            $lastRound = $allRounds[count($allRounds) - 1];
            $lastMatchType = $lastRound['match_type'][count($lastRound['match_type']) - 1];

            $matchTypeName = $lastMatchType['name'];
            if(isset($lastMatchType['actual_name'])) {
              $matchTypeName = $lastMatchType['actual_name'];
            }
            $isPlacingMatch = strpos($matchTypeName, 'PM');

            if ($isPlacingMatch !== false) {
              echo $file. '<br/>';
              $matches = $lastMatchType['groups']['match'];
              $position = 1;
              foreach($matches as $matchKey=>$match) {
                $updatedMatchDetail = $match;
                $updatedMatchDetail['position'] = ($position++).'-'.($position++);
                $allUpdatedRounds[count($allRounds) - 1]['match_type'][count($lastRound['match_type']) - 1]['groups']['match'][$matchKey] = $updatedMatchDetail;
              }
            }

            $updatedJson['tournament_competation_format']['format_name'] = $allUpdatedRounds;

            Storage::put($updatedFilePath, json_encode($updatedJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        echo "All templates processed.";exit;
    }

    /**
     * Check for competition end.
     *
     * @param int $competitionId
     *
     * @return boolean
     */
    private function checkForCompetitionEnd($competitionId) {
      $matches = DB::table('temp_fixtures')
      ->where('competition_id', $competitionId)
      ->whereRaw(Db::raw('(hometeam_score IS NULL OR awayteam_score IS NULL)'));

      if($matches->exists()) {
        return false;
      } else {
        return true;
      }
    }

    /**
     * Update competition positions
     *
     * @param int $competitionId
     *
     * @param int $ageCategoryId
     */
    public function updateCategoryPositions($competitionId, $ageCategoryId)
    {
      $ageCategory = TournamentCompetationTemplates::find($ageCategoryId);
      $tournamentTemplate = $ageCategory->TournamentTemplate;
      if($tournamentTemplate->position_type == 'final' || $tournamentTemplate->position_type == 'final_and_group_ranking') {
        $competition = Competition::find($competitionId);
        $matchPositions = Position::where('age_category_id', $ageCategoryId)->where('dependent_type', 'match')->get();
        $this->updatePlacingMatchPositions($ageCategory, $matchPositions);
      }
      if($tournamentTemplate->position_type == 'final_and_group_ranking' || $tournamentTemplate->position_type == 'group_ranking') {
        $rankingPositions = Position::where('age_category_id', $ageCategoryId)->where('dependent_type', 'ranking')->get();
        $this->updateGroupRankingPositions($ageCategory, $rankingPositions);
      }
    }

    /**
     * Update placing match positions
     *
     * @param object $ageCategory
     */
    public function updatePlacingMatchPositions($ageCategory, $positions)
    {
      $prefixMatchName = $ageCategory->group_name . '-' . $ageCategory->category_age . '-';
      for($i=0; $i < count($positions); $i=$i+2) {
        $matchNumber = str_replace('CAT.', $prefixMatchName, $positions[$i]->match_number);
        $fixture = DB::table('temp_fixtures')->where('match_number', $matchNumber)->where('age_group_id', $ageCategory->id)->get()->first();

        $winner = null;
        $looser = null;
        if($fixture->is_result_override == 1 && $fixture->match_status == 'Penalties') {
          $winner = $fixture->home_team;
          $looser = $fixture->away_team;
          if($fixture->match_winner == $fixture->away_team) {
            $winner = $fixture->away_team;
            $looser = $fixture->home_team;
          }
        } else if($fixture->hometeam_score !== null && $fixture->awayteam_score !== null) {
          if($fixture->hometeam_score >= $fixture->awayteam_score) {
            $winner = $fixture->home_team != 0 ? $fixture->home_team : null;
            $looser = $fixture->away_team != 0 ? $fixture->away_team : null;
          } else {
            $winner = $fixture->away_team != 0 ? $fixture->away_team : null;
            $looser = $fixture->home_team != 0 ? $fixture->home_team : null;
          }
        }

        if($winner!==null && $looser!==null) {
          // Update winner team
          $positions[$i]->team_id = $winner;
          $positions[$i]->save();

          // Update looser team
          if(isset($positions[$i + 1])) {
            $positions[$i + 1]->team_id = $looser;
            $positions[$i + 1]->save();
          }
        }
      }
    }

    /**
     * Update group ranking positions
     *
     * @param object $ageCategory
     */
    public function updateGroupRankingPositions($ageCategory, $positions)
    {
      $positionCalculatingGroups = $this->getPositionCalculatingGroups($ageCategory, $positions);
      $competitionIds = $positionCalculatingGroups['competitionIds'];
      $groups = $positionCalculatingGroups['groups'];
      if(count($competitionIds) != count($groups)) {
        return false;
      }
      $data['tournamentId'] = $ageCategory->tournament_id;
      $standingResData = [];
      $competitionEndFlag = 0;

      for($i=0; $i < count($competitionIds); $i++) {
        $competitionId = $competitionIds[$i];
        $standingResData[$groups[$i]] = [];
        if($this->checkForCompetitionEnd($competitionId)) {
          $competitionEndFlag = 1;
          $data['competitionId'] = $competitionId;
          $tournamentData['tournamentData'] = $data;
          $standingResData[$groups[$i]] = $this->getStanding(collect($tournamentData), 'yes')['data']->toArray();
        }
      }

      if($competitionEndFlag == 1) {
        foreach($positions as $position) {
          $ranking = $position->ranking;
          $group = substr($ranking, -1);
          $rankingNumber = intval(substr($ranking, 0, strlen($ranking) - 1));
          $standing = isset($standingResData[$group][$rankingNumber - 1]) ? ((array) $standingResData[$group][$rankingNumber - 1]) : [];
          if(isset($standing['id']) && $standing['id']!=null) {
            $position->team_id = $standing['id'];
            $position->save();
          }
        }
      }
    }

    /**
     * Update group ranking positions
     *
     * @param object $ageCategory
     */
    public function getPositionCalculatingGroups($ageCategory, $positions)
    {
      $ageCategoryPrefix = $ageCategory->group_name . '-' . $ageCategory->category_age . '-';
      $groupNames = [];
      $groups = [];

      foreach($positions as $position) {
        $group = substr($position->ranking, -1);
        if(!in_array($group, $groups)) {
          $groups[] = $group;
          $groupName = $ageCategoryPrefix . 'Group-' . $group;
          $groupNames[] = $groupName;
        }
      }

      $competitionIds = Competition::where('tournament_competation_template_id', $ageCategory->id)->whereIn('name', $groupNames)->pluck('id')->toArray();
      return array('groups' => $groups, 'competitionIds' => $competitionIds);
    }

    /**
      * Update competition stndings
      *
      * @param array $data
      */
    public function refreshCompetitionStandings($data)
    {
      $standingCount =  DB::table('match_standing')
                            ->where('tournament_id','=',$data['tournamentId'])
                            ->where('competition_id','=',$data['competitionId'])->count();

      $groupFixture = DB::table('temp_fixtures')->select('temp_fixtures.*')->where('tournament_id','=',$data['tournamentId'])->where('competition_id',$data['competitionId'])->get();

      foreach ($groupFixture as $key => $value) {
        $this->calculateCupLeagueTable($value->id, 1);
      }
    }

    public function matchUnscheduledFixtures($matchId)
    {
        return $this->matchRepoObj->matchUnscheduledFixtures($matchId);
    }
}
