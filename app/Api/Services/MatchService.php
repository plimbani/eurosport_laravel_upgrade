<?php

namespace App\Api\Services;

use DB;
use File;
use App\Api\Contracts\MatchContract;
use App\Models\Competition;
use App\Models\Pitch;
use App\Models\Position;
use App\Models\Team;
use App\Models\TeamManualRanking;
use App\Models\TempFixture;
use App\Models\Tournament;
use App\Models\TournamentCompetationTemplates;
use App\Traits\TournamentAccess;
use PDF;
use Storage;

class MatchService implements MatchContract
{
    use TournamentAccess;

    public function __construct()
    {
        $this->matchRepoObj = new \App\Api\Repositories\MatchRepository();
        $this->tournamentLogo = getenv('S3_URL').'/assets/img/tournament_logo/';
    }

    public function getAllMatches()
    {
        return $this->matchRepoObj->getAllMatches();
    }

    /**
     * create New Match.
     *
     * @param  [type]
     * @param  mixed  $data
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
     * @param  array  $data
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
     * @param  array  $data
     * @param  mixed  $deleteId
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
     * @param  array  $data
     * @param  mixed  $deleteId
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
     * @param  array  $data
     * @param  mixed  $deleteId
     * @return [type]
     */
    public function getFixtures($data)
    {
        $data = $data->all();
        // $fixtureResData = $this->matchRepoObj->getFixtures($data['tournamentData']);
        $fixtureResData = $this->matchRepoObj->getTempFixtures($data['tournamentData']);

        return ['status_code' => '200', 'data' => $fixtureResData, 'message' => 'Match Fixture data'];
    }

    /**
     * Get Standing  Details For Tournament.
     *
     * @param  array  $data
     * @param  mixed  $deleteId
     * @return [type]
     */
    public function getStanding($data, $refreshStanding)
    {
        $data = $data->all();
        $tournamentData = $data['tournamentData'];

        $standingResData = $this->matchRepoObj->getStanding($tournamentData);
        if ($refreshStanding && $refreshStanding == 'yes' && isset($tournamentData['competitionId']) && $tournamentData['competitionId'] != '') {
            $competition = Competition::find($tournamentData['competitionId']);
            if (count($standingResData) != $competition->team_size) {
                return $this->refreshStanding($data);
            }
        }

        if ($standingResData) {
            return ['status_code' => '200', 'data' => $standingResData, 'message' => 'Match Standing data'];
        }
    }

    /**
     * Get Draw Table  Details For Tournament.
     *
     * @param  array  $data
     * @param  mixed  $deleteId
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

    public function scheduleMatch($data)
    {
        $matchFixturesStatusArray = [];
        $areAllMatchFixtureScheduled = false;
        $data = $data->all()['data'];
        $scheduledResult = $this->matchRepoObj->setMatchSchedule($data);

        $unChangedFixturesArray = [];
        if ($scheduledResult['status'] === false) {
            $unChangedFixturesArray[] = $scheduledResult['data']['match_number'];
        }

        if (count($unChangedFixturesArray) === 0) {
            $areAllMatchFixtureScheduled = true;
        }

        if ($scheduledResult) {
            if ($scheduledResult != -1 && $scheduledResult != -2 && $scheduledResult != -3) {
                $message = 'Match has been scheduled successfully';
                if (isset($scheduledResult['maximum_interval_flag']) && $scheduledResult['maximum_interval_flag'] === 1) {
                    if ($data['isMultiSchedule'] === false) {
                        $message = 'The match has been scheduled but it does exceed the maximum team interval.';
                    } else {
                        $message = 'The match will get schedule but it does exceed the maximum team interval.';
                    }
                }

                return ['status_code' => '200', 'data' => $scheduledResult, 'message' => $message, 'unChangedFixturesArray' => $unChangedFixturesArray, 'areAllMatchFixtureScheduled' => $areAllMatchFixtureScheduled];
            } elseif ($scheduledResult == -1) {
                return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'One or both teams are scheduled for a team interval.'];
            } elseif ($scheduledResult == -2) {
                return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'This pitch is the wrong pitch size for this fixture.'];
            }
            // else if($scheduledResult == -3){
            //    return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Match can not be scheduled as it exceeds maximum team interval.'];
            // }
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }

    public function getAllScheduledMatch($matchData)
    {
        $scheduledResult = $this->matchRepoObj->getAllScheduledMatches($matchData->all());
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Match scheduled successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }

    public function checkTeamIntervalforMatches($matchData)
    {
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
        if (isset($matchData['result_override']) && $matchData['result_override'] == 'false') {
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

        $pdf = PDF::loadView('pitchplanner.pitch', ['data' => $resultData, 'result_override' => $matchData['result_override'], 'categoryAgeColor' => $categoryAgeColor, 'categoryStripColor' => $categoryStripColor])
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

    public function generateCategoryReport($ageGroupId, $tournamentId)
    {
        $competitions = Competition::where('tournament_competation_template_id', $ageGroupId)->get();
        $date = new \DateTime(date('H:i d M Y'));
        $pdfData = [];
        $leagueTable = [];
        $resultGridTable = [];
        $resultMatchesTable = [];
        $resultMatchesTableAfterFR = [];

        foreach ($competitions as $competition) {
            if ($competition->actual_competition_type == 'Round Robin') {
                $tournamentData = ['tournamentData' => ['competitionId' => $competition->id, 'tournamentId' => $competition->tournament_id]];
                $result = $this->refreshStanding($tournamentData, 'yes');
                $leagueTable[$competition->id] = ['name' => $competition['name'], 'standings' => $result['data']];
            }
            if ($competition->competation_round_no == 'Round 1') {
                if ($competition->actual_competition_type == 'Round Robin') {
                    $tournamentDataResultGrid = ['tournamentData' => ['competationId' => $competition->id, 'tournamentId' => $competition->tournament_id]];
                    $resultGrid = $this->getDrawTable(collect($tournamentDataResultGrid));
                    if ($resultGrid['status_code'] != '200') {
                        $resultGrid['data'] = [];
                    }
                    $resultGridTable[$competition->id] = ['name' => $competition['name'], 'results' => $resultGrid['data'], 'actual_competition_type' => $competition['actual_competition_type']];
                } else {
                    $resultGridTable[$competition->id] = ['name' => $competition['name'], 'results' => [], 'actual_competition_type' => $competition['actual_competition_type']];
                }

                $tournamentDataMatches = ['tournamentData' => ['competitionId' => $competition->id, 'tournamentId' => $competition->tournament_id, 'is_scheduled' => 1, 'matchOrderReport' => 1]];
                $resultMatches = $this->getFixtures(collect($tournamentDataMatches));
                $resultMatchesTable[$competition->id] = ['name' => $competition['name'], 'results' => $resultMatches['data']];
            }
            if ($competition->competation_round_no !== 'Round 1') {
                $tournamentDataMatchesAfterFirstRound = ['tournamentData' => ['competitionId' => $competition->id, 'tournamentId' => $competition->tournament_id, 'matchOrderReport' => 1]];
                $resultMatchesAfterFirstRound = $this->getFixtures(collect($tournamentDataMatchesAfterFirstRound));

                $resultMatchesTableAfterFirstRound[$competition->id] = ['name' => $competition['name'], 'results' => $resultMatchesAfterFirstRound['data'], 'actual_competition_type' => $competition['actual_competition_type']];
            }
        }

        $tournamentLogo = null;
        $tournamentDetail = Tournament::find($tournamentId);
        if ($tournamentDetail->logo != null) {
            $tournamentLogo = $this->tournamentLogo.$tournamentDetail->logo;
        }

        $pdfData['leagueTable'] = $leagueTable;
        $pdfData['resultGridTable'] = $resultGridTable;
        $pdfData['resultMatchesTable'] = $resultMatchesTable;
        $pdfData['resultMatchesTableAfterFirstRound'] = $resultMatchesTableAfterFirstRound;

        $pdf = PDF::loadView('age_category.summary_report', ['data' => $pdfData, 'tournamentLogo' => $tournamentLogo])
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOption('footer-spacing', '5')
            ->setOption('footer-font-size', 7)
            ->setOrientation('portrait')
            ->setOption('footer-right', 'Page [page] of [toPage]')
            ->setOption('header-right', $date->format('H:i d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);

        return $pdf->download('Summary report.pdf');
    }

    public function getMatchDetail($matchData)
    {

        $matchResult = $this->matchRepoObj->getMatchDetail($matchData->all()['matchId']);
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $matchResult];
        } else {
            return ['status_code' => '300'];
        }
    }

    public function removeAssignedReferee($matchData)
    {
        $matchResult = $this->matchRepoObj->removeAssignedReferee($matchData->all()['data']);
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $matchResult];
        } else {
            return ['status_code' => '300'];
        }
    }

    public function assignReferee($matchData)
    {
        $matchResult = $this->matchRepoObj->assignReferee($matchData->all()['data']);
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $matchResult];
        } else {
            return ['status_code' => '300'];
        }
    }

    public function saveResult($matchData)
    {
        $matchResult = $this->matchRepoObj->saveResult($matchData->all()['matchData']);
        $fixture = DB::table('temp_fixtures')->where('id', $matchData->all()['matchData']['matchId'])->first();
        $competationId = $this->calculateCupLeagueTable($fixture);

        $result = TempFixture::where('id', $matchData->all()['matchData']['matchId'])->first()->toArray();
        $tournamentId = $result['tournament_id'];
        $ageGroupId = $result['age_group_id'];
        $teamsList = [$result['home_team'], $result['away_team']];

        \Log::info('saveResult-tournamentId'.$tournamentId);
        \Log::info('saveResult-AllMatches'.json_encode($matchData->all()['matchData']));

        $matchData = ['teams' => $teamsList, 'tournamentId' => $tournamentId, 'ageGroupId' => $ageGroupId, 'teamId' => true];

        $matchresult = $this->matchRepoObj->checkTeamIntervalforMatches($matchData);

        $data['competationId'] = $competationId;
        $data['isResultOverride'] = $result['is_result_override'];
        if ($matchResult) {
            return ['status_code' => '200', 'data' => $data];
        } else {
            return ['status_code' => '300'];
        }
    }

    public function saveAllResults($matchData)
    {
        \Log::info('Matches Data '.json_encode($matchData));

        $teamArray = [];
        $competitionIds = [];
        $AllMatches = $matchData->all()['matchData']['matchDataArray'];
        $tournamentId = $matchData->all()['matchData']['tournamentId'];

        \Log::info('saveAllResults-tournamentId '.$tournamentId);
        \Log::info('saveAllResults-AllMatches '.json_encode($AllMatches));

        $matchResult = null;
        $unChangedMatchScoresArray = [];
        $areAllMatchScoreUpdated = false;
        $matchesScoresStatusArray = [];

        foreach ($AllMatches as $match) {

            $matchResult = $this->matchRepoObj->saveAllResults($match);
            array_push($matchesScoresStatusArray, $matchResult['is_score_updated']);
            // $matchesScoresStatusArray['is_score_updated'] = $matchResult['is_score_updated'];
            \Log::info('Matches Result '.$matchResult['status']);
            if ($matchResult['status'] === false) {
                $unChangedMatchScoresArray[] = $matchResult['tempFixture']->match_number;
            }
            $matchData = $matchResult['match_data'];
            if ($matchResult['is_score_updated'] === true && $matchResult['status'] === true) {
                $teamArray[$matchData['age_group_id']][] = $matchData['home_team_id'];
                $teamArray[$matchData['age_group_id']][] = $matchData['away_team_id'];
                $competitionIds[$matchData['age_group_id']][] = $matchData['competition_id'];
            }
        }

        \Log::info('team Array '.json_encode($teamArray));
        \Log::info('competition Ids '.json_encode($competitionIds));

        $changedScoresCount = count(array_filter($matchesScoresStatusArray, function ($x) {
            return $x == true;
        }));

        \Log::info('changedScoresCount '.$changedScoresCount);

        if (count($unChangedMatchScoresArray) === 0) {
            $areAllMatchScoreUpdated = true;
        }

        \Log::info('areAllMatchScoreUpdated '.$areAllMatchScoreUpdated);

        foreach ($competitionIds as $ageGroupId => $cids) {
            // $lowerCompetitionId = min(array_unique($cids));
            // $allCompetitionsIds = Competition::where('tournament_id', '=', $tournamentId)->where('tournament_competation_template_id', '=', $ageGroupId)->where('id', '>=', $lowerCompetitionId)->pluck('id')->toArray();
            $allCompetitionsIds = array_unique($cids);
            sort($allCompetitionsIds);
            foreach ($allCompetitionsIds as $id) {
                $data = ['tournamentId' => $tournamentId, 'competitionId' => $id];
                $this->refreshCompetitionStandings($data);
            }
        }

        foreach ($teamArray as $ageGroupId => $teamsList) {
            $teamsList = array_unique($teamsList);
            $matchData = ['teams' => $teamsList, 'tournamentId' => $tournamentId, 'ageGroupId' => $ageGroupId, 'teamId' => true];
            $matchresult = $this->matchRepoObj->checkTeamIntervalforMatches($matchData);
        }

        if ($matchResult) {
            \Log::info('Final response '.json_encode(['status_code' => '200', 'data' => $matchResult, 'unChangedScores' => $unChangedMatchScoresArray, 'areAllMatchScoreUpdated' => $areAllMatchScoreUpdated]));

            return ['status_code' => '200', 'data' => $matchResult, 'unChangedScores' => $unChangedMatchScoresArray, 'areAllMatchScoreUpdated' => $areAllMatchScoreUpdated];
        } else {
            \Log::info('Final response '.json_encode(['status_code' => '300']));

            return ['status_code' => '300'];
        }
    }

    public function unscheduleMatch($matchData)
    {
        $scheduledResult = $this->matchRepoObj->matchUnschedule($matchData->all()['matchData']);

        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Match scheduled successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }

    public function saveUnavailableBlock($matchData)
    {
        $scheduledResult = $this->matchRepoObj->setUnavailableBlock($matchData->all()['matchData']);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Block added successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }

    public function getUnavailableBlock($matchData)
    {
        $scheduledResult = $this->matchRepoObj->getUnavailableBlock($matchData->all()['matchData']);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Block added successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }

    public function removeBlock($block_id)
    {
        $scheduledResult = $this->matchRepoObj->removeBlock($block_id);
        if ($scheduledResult) {
            return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'Block added successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scheduledResult];
        }
    }

    public function updateScore($matchData)
    {
        $scoreUpdate = $this->matchRepoObj->updateScore($matchData->all()['matchData']);
        $fixture = DB::table('temp_fixtures')->where('id', $matchData->all()['matchData']['matchId'])->first();
        $competationId = $this->calculateCupLeagueTable($fixture);

        $result = TempFixture::where('id', $matchData->all()['matchData']['matchId'])->first()->toArray();
        $tournamentId = $result['tournament_id'];
        $ageGroupId = $result['age_group_id'];
        $teamsList = [$result['home_team'], $result['away_team']];

        $matchData = ['teams' => $teamsList, 'tournamentId' => $tournamentId, 'ageGroupId' => $ageGroupId, 'teamId' => true];

        $matchresult = $this->matchRepoObj->checkTeamIntervalforMatches($matchData);

        $data['competationId'] = $competationId;
        if ($scoreUpdate) {
            return ['status_code' => '200', 'data' => $data, 'message' => 'Score updated successfully'];
        } else {
            return ['status_code' => '300', 'message' => $scoreUpdate];
        }
    }

    private function secondRoundElimination123($fetchRecord, $updatedValue, $var)
    {
        //echo '<br/>hello s3condroundelimination';
        echo '\n<br/>ID:'.$fetchRecord->id;
        echo '\n<br/>ID2:'.$fetchRecord->id;

        // print_r($fetchRecord);
        echo 'updated record';
        //print_r($updatedValue);
        //print_r();
        // explode updated record
        $updatedMatchNumber = explode('-', $updatedValue->match_number);
        $updatedMatchNumber = $updatedMatchNumber[count($updatedMatchNumber) - 1];
        $updatedMatchNumber = substr($updatedMatchNumber, 1, -1);
        $rec = explode('|', $updatedMatchNumber);
        print_r($rec);

        return;
        $updval = (string) $updatedValue->match_number;
        $singleFixTeams = explode('.', $singleFixture->match_number);
        $singleFXTeam = $singleFixTeams[count($singleFixTeams) - 1];

        $doubleElm = explode('.', $updval);
        $doubleElm1 = $doubleElm[count($doubleElm) - 1];

        $vaal = explode('-(', $doubleElm1);

        print_r($vaal);
        $hometeam = substr($vaal[0], 1, -1);
        $awayteam = substr($vaal[1], 0, -1);
        echo '<br>HTSM:'.$hometeam;
        echo '<br>ATSM:'.$awayteam;
        //echo 'ssd'.$singleFXTeam;
        if ($var == 'WR') {
            if (trim($hometeam) == trim($singleFXTeam)) {
                echo 'hi123';
                // here we check the score
                if ($singleFixture->hometeam_score > $singleFixture->awayteam_score) {
                    $hometeamName = $singleFixture->home_team_name;
                    $homeTeamId = $singleFixture->home_team;
                } else {
                    $hometeamName = $singleFixture->away_team_name;
                    $homeTeamId = $singleFixture->away_team;
                }
                $updateArray = ['home_team_name' => $hometeamName, 'home_team' => $homeTeamId];
                DB::table('temp_fixtures')->where('id', $updatedValue->id)->update($updateArray);

            }
            if (trim($awayteam) == trim($singleFXTeam)) {
                echo 'hi246';
                if ($singleFixture->hometeam_score > $singleFixture->awayteam_score) {
                    $awayteamName = $singleFixture->home_team_name;
                    $awayTeamId = $singleFixture->home_team;
                } else {
                    $awayteamName = $singleFixture->away_team_name;
                    $awayTeamId = $singleFixture->away_team;
                }
                $updateArray = ['away_team_name' => $awayteamName, 'away_team' => $awayTeamId];
                DB::table('temp_fixtures')->where('id', $updatedValue->id)->update($updateArray);

            }
        }
        if ($var == 'LR') {
            if (trim($hometeam) == trim($singleFXTeam)) {
                echo 'hi123';
                // here we check the score
                if ($singleFixture->hometeam_score < $singleFixture->awayteam_score) {
                    $hometeamName = $singleFixture->home_team_name;
                    $homeTeamId = $singleFixture->home_team;
                } else {
                    $hometeamName = $singleFixture->away_team_name;
                    $homeTeamId = $singleFixture->away_team;
                }
                $updateArray = ['home_team_name' => $hometeamName, 'home_team' => $homeTeamId];
                DB::table('temp_fixtures')->where('id', $updatedValue->id)->update($updateArray);

            }
            if (trim($awayteam) == trim($singleFXTeam)) {
                echo 'hi246';
                if ($singleFixture->hometeam_score < $singleFixture->awayteam_score) {
                    $awayteamName = $singleFixture->home_team_name;
                    $awayTeamId = $singleFixture->home_team;
                } else {
                    $awayteamName = $singleFixture->away_team_name;
                    $awayTeamId = $singleFixture->away_team;
                }
                $updateArray = ['away_team_name' => $awayteamName, 'away_team' => $awayTeamId];
                DB::table('temp_fixtures')->where('id', $updatedValue->id)->update($updateArray);

            }
        }
    }

    private function secondRoundElimination($singleFixture)
    {
        $age_category_id = $singleFixture->age_group_id;
        $tournament_id = $singleFixture->tournament_id;

        // We have to find the record of it for winner and looser
        $match_number = $singleFixture->match_number;
        $match_number = explode('.', $match_number);
        $frs = explode('-', $match_number[0]);

        $val = $frs[count($frs) - 1].'_'.$match_number[1];
        // $val = $frs[count($frs)-1]."_".$match_number[count($match_number)-1];
        // print_r($match_number);exit;
        $home_team_score = $singleFixture->hometeam_score;
        $away_team_score = $singleFixture->awayteam_score;

        $winnerTeam = $singleFixture->home_team_name;
        $winnerId = $singleFixture->home_team;
        $takeTeam = null;
        // For Winner Conditions
        if ($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
            $takeTeam = 'home';

            if ($singleFixture->match_winner == $singleFixture->away_team) {
                $takeTeam = 'away';
            }
        } elseif ($home_team_score !== null && $away_team_score !== null) {
            $takeTeam = 'home';

            if ($home_team_score < $away_team_score) {
                $takeTeam = 'away';
            }
        }
        if ($takeTeam == 'away') {
            $winnerTeam = $singleFixture->away_team_name;
            $winnerId = $singleFixture->away_team;
        }

        $looserTeam = $singleFixture->home_team_name;
        $looserId = $singleFixture->home_team;
        $takeTeam = null;
        // For Looser Conditions
        if ($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
            $takeTeam = 'home';

            if ($singleFixture->match_winner != $singleFixture->away_team) {
                $takeTeam = 'away';
            }
        } elseif ($home_team_score !== null && $away_team_score !== null) {
            $takeTeam = 'home';

            if ($home_team_score >= $away_team_score) {
                $takeTeam = 'away';
            }
        }
        if ($takeTeam == 'away') {
            $looserTeam = $singleFixture->away_team_name;
            $looserId = $singleFixture->away_team;
        }

        if (($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') || ($home_team_score !== null && $away_team_score !== null)) {
            // Now fire a query which gives two record Winner and Looser
            $results = DB::table('temp_fixtures')->where('age_group_id', '=', $age_category_id)->where('tournament_id', '=', $tournament_id)
                ->where(function ($query) use ($val) {
                    $query->whereRaw(DB::raw("match_number like '%(".$val."_WR)%' OR  match_number like '%(".$val."_LR)%' "));
                })->get();
            $processFixtures = [];
            // here we get two records 1 for Winner and other for looser
            foreach ($results as $record) {
                $rec_mtchNumber = explode('.', $record->match_number);
                $teams = $rec_mtchNumber[2];
                $teams = explode('-', $teams);
                $homeTeam = $teams[0];
                $awayTeam = $teams[1];
                // if its winner then
                if (strpos($record->match_number, 'WR') !== false) {
                    // its Home team
                    if (trim('('.$val.'_WR)') == trim($homeTeam)) {
                        // echo 'homeW';
                        TempFixture::where('id', $record->id)->update([
                            'home_team_name' => $winnerTeam,
                            'home_team' => $winnerId,
                        ]);
                        $processFixtures[] = $record->id;
                    }

                    if (trim('('.$val.'_WR)') == trim($awayTeam)) {
                        TempFixture::where('id', $record->id)->update([
                            'away_team_name' => $winnerTeam,
                            'away_team' => $winnerId,
                        ]);
                        $processFixtures[] = $record->id;
                    }

                    // its away team
                }
                // if its looser then
                if (strpos($record->match_number, 'LR') !== false) {
                    if (trim('('.$val.'_LR)') == trim($homeTeam)) {
                        TempFixture::where('id', $record->id)->update([
                            'home_team_name' => $looserTeam,
                            'home_team' => $looserId,
                        ]);
                        $processFixtures[] = $record->id;
                    }

                    if (trim('('.$val.'_LR)') == trim($awayTeam)) {
                        TempFixture::where('id', $record->id)->update([
                            'away_team_name' => $looserTeam,
                            'away_team' => $looserId,
                        ]);
                        $processFixtures[] = $record->id;
                    }
                }
            }
            $this->processFixtures($processFixtures);
        } elseif ($home_team_score === null || $away_team_score === null) {
            // Now fire a query which gives two record Winner and Looser
            $results = DB::table('temp_fixtures')->where('age_group_id', '=', $age_category_id)->where('tournament_id', '=', $tournament_id)
                ->where(function ($query) use ($val) {
                    $query->whereRaw(DB::raw("match_number like '%(".$val."_WR)%' OR  match_number like '%(".$val."_LR)%' "));
                })->get();
            $processFixtures = [];
            // here we get two records 1 for Winner and other for looser
            foreach ($results as $record) {
                $rec_mtchNumber = explode('.', $record->match_number);
                $teams = $rec_mtchNumber[2];
                $teams = explode('-', $teams);
                $homeTeam = $teams[0];
                $awayTeam = $teams[1];
                // if its winner then
                if (strpos($record->match_number, 'WR') !== false) {
                    // its Home team
                    if (trim('('.$val.'_WR)') == trim($homeTeam)) {
                        // echo 'homeW';
                        TempFixture::where('id', $record->id)->update([
                            'home_team_name' => $record->home_team_placeholder_name,
                            'home_team' => 0,
                            'hometeam_score' => null,
                            'awayteam_score' => null,
                        ]);
                        $processFixtures[] = $record->id;
                    }

                    if (trim('('.$val.'_WR)') == trim($awayTeam)) {
                        TempFixture::where('id', $record->id)->update([
                            'away_team_name' => $record->away_team_placeholder_name,
                            'away_team' => 0,
                            'hometeam_score' => null,
                            'awayteam_score' => null,
                        ]);
                        $processFixtures[] = $record->id;
                    }

                    // its away team
                }
                // if its looser then
                if (strpos($record->match_number, 'LR') !== false) {
                    if (trim('('.$val.'_LR)') == trim($homeTeam)) {
                        TempFixture::where('id', $record->id)->update([
                            'home_team_name' => $record->home_team_placeholder_name,
                            'home_team' => 0,
                            'hometeam_score' => null,
                            'awayteam_score' => null,
                        ]);
                        $processFixtures[] = $record->id;
                    }

                    if (trim('('.$val.'_LR)') == trim($awayTeam)) {
                        TempFixture::where('id', $record->id)->update([
                            'away_team_name' => $record->away_team_placeholder_name,
                            'away_team' => 0,
                            'hometeam_score' => null,
                            'awayteam_score' => null,
                        ]);
                        $processFixtures[] = $record->id;
                    }
                }
            }
            $this->processFixtures($processFixtures);
        }

    }

    private function calculateEliminationTeams($singleFixture)
    {

        //$singleFixture = $singleFixture[0];
        $tournament_id = $singleFixture->tournament_id;
        $ageGroupId = $singleFixture->age_group_id;
        //$compIds = $this->getCompeIds($singleFixture->competition_id);
        //print_r($compIds);exit;
        $matches = DB::table('temp_fixtures')
            ->where('tournament_id', '=', $tournament_id)
                  // ->where('round','=' , 'Elimination')
            ->where('age_group_id', '=', $ageGroupId)
            ->get();
        $processFixtures = [];

        $matchArr = [];
        $teams_arr = explode('.', $singleFixture->match_number);

        $teams = $teams_arr[count($teams_arr) - 1];
        $selTeams = explode('-', $teams);
        $SelhomeTeam = $selTeams[0];
        $SelawayTeam = $selTeams[1];
        $modifiedTeams = str_replace('-', '_', $teams);
        // echo "<pre>"; print_r($selTeams); echo "</pre>";
        if (strpos($modifiedTeams, 'WR') !== false || strpos($modifiedTeams, 'LR') !== false) {
            $this->secondRoundElimination($singleFixture);
        }
        foreach ($matches as $match) {

            $matchNumber = explode('.', $match->match_number);
            //print_r($matchNumber);
            $matchTeams = $matchNumber[count($matchNumber) - 1];
            $mtsTeams = explode('-', $matchTeams);
            //print_r($mtsTeams);
            // Teams For that Matches
            // echo "<pre>"; print_r($mtsTeams); echo "</pre>";
            $homeTeam = $mtsTeams[0];
            $awayTeam = $mtsTeams[1];
            // Get hometeam=1A awayTeam =2B
            // here we check it For 2nd round Eliminbation
            // echo "<pre>"; print_r($mtsTeams); echo "</pre>";

            // First For Winner
            //  echo 'Hi MOD Teams<br>';
            //print_r($modifiedTeams);exit;
            // if (strpos($modifiedTeams, 'WR') !== false) {
            //   $teams_arr = explode('.', $singleFixture->match_number);
            //   $frs = explode("-",$teams_arr[0]);
            //   $checkForTeam = $frs[count($frs)-1]."_".$teams_arr[1].'_';

            //   $var = '';
            //   $match1 = '';
            //   //\Log::info('homeTeam' . $homeTeam);
            //   // if($SelhomeTeam == $homeTeam ) {
            //   if (strpos($homeTeam, $checkForTeam) !== false) {
            //     // echo "SDF";exit;
            //     $match1 = $match;
            //   }
            //   if($homeTeam[0] == '(') {
            //       if(isset($match1) && $match1 != ''){
            //         \Log::info('match 1:' . $match1->id);
            //         $this->secondRoundElimination($match1);
            //       }
            //     }
            //   $match2 = '';
            //   //if($SelawayTeam==$awayTeam) {
            //   if (strpos($awayTeam, $checkForTeam) !== false) {
            //     $match2=$match;
            //   }
            //   if($awayTeam[strlen($awayTeam)-1]==')') {
            //       if(isset($match2) && $match2 != ''){
            //         \Log::info('match 2:' . $match2->id);
            //         $this->secondRoundElimination($match2);
            //       }
            //     }
            //   // here check for Multiple Value for detect the updated record value

            // }

            // if (strpos($modifiedTeams, 'LR') !== false) {
            //   // echo "<pre>"; print_r(); echo "</pre>";
            //   // $selTeams = explode('-',$teams);
            //   // $SelhomeTeam = $selTeams[0];
            //   // $SelawayTeam = $selTeams[1];
            //   $teams_arr = explode('.', $singleFixture->match_number);
            //   $frs = explode("-",$teams_arr[0]);
            //   $checkForTeam = $frs[count($frs)-1]."_".$teams_arr[1].'_';

            //   $var = '';
            //   $match1 = '';
            //   //if($SelhomeTeam == $homeTeam ) {
            //   if (strpos($homeTeam, $checkForTeam) !== false) {
            //     // here we get that Match
            //     $match1 = $match;
            //   }
            //   if($homeTeam[0] == '(') {
            //      if(isset($match1) && $match1 != ''){
            //       $this->secondRoundElimination($match1);
            //     }
            //   }
            //   $match2 = '';
            //   //if($SelawayTeam==$awayTeam) {
            //   if (strpos($awayTeam, $checkForTeam) !== false) {
            //     $match2=$match;
            //   }
            //   if($awayTeam[strlen($awayTeam)-1]==')'){
            //       //echo 'false';exit;
            //      if(isset($match2) && $match2 != ''){
            //         $this->secondRoundElimination($match2);
            //       }
            //     }
            //   // here check for Multiple Value for detect the updated record value

            // }

            $modifiedTeamsWinner = $modifiedTeams.'_WR';

            if ($homeTeam == $modifiedTeamsWinner) {
                $hometeamName = null;
                $homeTeamId = 0;
                $takeTeam = null;

                if ($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
                    $takeTeam = 'home';

                    if ($singleFixture->match_winner == $singleFixture->away_team) {
                        $takeTeam = 'away';
                    }
                } elseif ($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
                    $takeTeam = 'home';

                    if ($singleFixture->hometeam_score < $singleFixture->awayteam_score) {
                        $takeTeam = 'away';
                    }
                }

                if ($takeTeam == 'home') {
                    $hometeamName = $singleFixture->home_team_name;
                    $homeTeamId = $singleFixture->home_team;
                }
                if ($takeTeam == 'away') {
                    $hometeamName = $singleFixture->away_team_name;
                    $homeTeamId = $singleFixture->away_team;
                }

                if ($hometeamName === null && $homeTeamId == 0) {
                    $fixture = TempFixture::where('id', $match->id)->first();
                    $updateArray = ['home_team_name' => $fixture->home_team_placeholder_name, 'home_team' => $homeTeamId, 'hometeam_score' => null, 'awayteam_score' => null];
                    $fixture->update($updateArray);
                } else {
                    $updateArray = ['home_team_name' => $hometeamName, 'home_team' => $homeTeamId];
                    DB::table('temp_fixtures')->where('id', $match->id)->update($updateArray);
                }
                $processFixtures[] = $match->id;
            }
            if ($awayTeam == $modifiedTeamsWinner) {
                $awayteamName = null;
                $awayTeamId = 0;
                $takeTeam = null;

                if ($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
                    $takeTeam = 'home';

                    if ($singleFixture->match_winner == $singleFixture->away_team) {
                        $takeTeam = 'away';
                    }
                } elseif ($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
                    $takeTeam = 'home';

                    if ($singleFixture->hometeam_score < $singleFixture->awayteam_score) {
                        $takeTeam = 'away';
                    }
                }

                if ($takeTeam == 'home') {
                    $awayteamName = $singleFixture->home_team_name;
                    $awayTeamId = $singleFixture->home_team;
                }
                if ($takeTeam == 'away') {
                    $awayteamName = $singleFixture->away_team_name;
                    $awayTeamId = $singleFixture->away_team;
                }

                if ($awayteamName === null && $awayTeamId == 0) {
                    $fixture = TempFixture::where('id', $match->id)->first();
                    $updateArray = ['away_team_name' => $fixture->away_team_placeholder_name, 'away_team' => $awayTeamId, 'hometeam_score' => null, 'awayteam_score' => null];
                    $fixture->update($updateArray);
                } else {
                    $updateArray = ['away_team_name' => $awayteamName, 'away_team' => $awayTeamId];
                    DB::table('temp_fixtures')->where('id', $match->id)->update($updateArray);
                }
                $processFixtures[] = $match->id;
            }
            // For Looser
            $modifiedTeamsLooser = $modifiedTeams.'_LR';

            if ($homeTeam == $modifiedTeamsLooser) {
                $hometeamName = null;
                $homeTeamId = 0;
                $takeTeam = null;

                if ($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
                    $takeTeam = 'home';

                    if ($singleFixture->match_winner != $singleFixture->away_team) {
                        $takeTeam = 'away';
                    }
                } elseif ($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
                    $takeTeam = 'home';

                    if ($singleFixture->hometeam_score >= $singleFixture->awayteam_score) {
                        $takeTeam = 'away';
                    }
                }

                if ($takeTeam == 'home') {
                    $hometeamName = $singleFixture->home_team_name;
                    $homeTeamId = $singleFixture->home_team;
                }
                if ($takeTeam == 'away') {
                    $hometeamName = $singleFixture->away_team_name;
                    $homeTeamId = $singleFixture->away_team;
                }

                if ($hometeamName === null && $homeTeamId == 0) {
                    $fixture = TempFixture::where('id', $match->id)->first();
                    $updateArray = ['home_team_name' => $fixture->home_team_placeholder_name, 'home_team' => $homeTeamId, 'hometeam_score' => null, 'awayteam_score' => null];
                    $fixture->update($updateArray);
                } else {
                    $updateArray = ['home_team_name' => $hometeamName, 'home_team' => $homeTeamId];
                    DB::table('temp_fixtures')->where('id', $match->id)->update($updateArray);
                }
                $processFixtures[] = $match->id;
            }
            if ($awayTeam == $modifiedTeamsLooser) {
                $awayteamName = null;
                $awayTeamId = 0;
                $takeTeam = null;

                if ($singleFixture->is_result_override == 1 && $singleFixture->match_status == 'Penalties') {
                    $takeTeam = 'home';

                    if ($singleFixture->match_winner != $singleFixture->away_team) {
                        $takeTeam = 'away';
                    }
                } elseif ($singleFixture->hometeam_score !== null && $singleFixture->awayteam_score !== null) {
                    $takeTeam = 'home';

                    if ($singleFixture->hometeam_score >= $singleFixture->awayteam_score) {
                        $takeTeam = 'away';
                    }
                }

                if ($takeTeam == 'home') {
                    $awayteamName = $singleFixture->home_team_name;
                    $awayTeamId = $singleFixture->home_team;
                }
                if ($takeTeam == 'away') {
                    $awayteamName = $singleFixture->away_team_name;
                    $awayTeamId = $singleFixture->away_team;
                }

                if ($awayteamName === null && $awayTeamId == 0) {
                    $fixture = TempFixture::where('id', $match->id)->first();
                    $updateArray = ['away_team_name' => $fixture->away_team_placeholder_name, 'away_team' => $awayTeamId, 'hometeam_score' => null, 'awayteam_score' => null];
                    $fixture->update($updateArray);
                } else {
                    $updateArray = ['away_team_name' => $awayteamName, 'away_team' => $awayTeamId];
                    DB::table('temp_fixtures')->where('id', $match->id)->update($updateArray);
                }
                $processFixtures[] = $match->id;
            }
        }
        $this->processFixtures($processFixtures);

        return $singleFixture->competition_id;
    }

    public function refreshStanding($data)
    {
        $data = $data['tournamentData'];

        // Following query will not be not needed if we will send age category id.
        $competition = Competition::find($data['competitionId']);
        $ageCategoryId = $competition->tournament_competation_template_id;

        $firstCompetition = Competition::where('tournament_competation_template_id', $ageCategoryId)->orderBy('id')->first();
        $groupFixture = DB::table('temp_fixtures')->select('temp_fixtures.*')->where('tournament_id', '=', $data['tournamentId'])->where('competition_id', $data['competitionId'])->get();

        if ($competition->actual_competition_type === 'Round Robin') {
            $findTeams = [];
            foreach ($groupFixture as $key => $value) {
                if ($value->home_team == 0 || $value->away_team == 0) {
                    continue;
                }
                $findTeams[] = $value->home_team;
                $findTeams[] = $value->away_team;
            }

            if (count($findTeams) > 0) {
                $findTeams = array_unique($findTeams);
                $this->moveMatchStandings($data['tournamentId'], $ageCategoryId, $data['competitionId']);
                $this->generateStandingsForCompetitions($data['tournamentId'], $data['competitionId'], $ageCategoryId, $findTeams, $competition->competation_type, false);
                $this->updateCategoryPositions($data['competitionId'], $ageCategoryId);
            }
        }

        $standingResData = $this->matchRepoObj->getStanding($data);
        if ($standingResData) {
            return ['status_code' => '200', 'data' => $standingResData, 'message' => 'Match Standing data'];
        }
    }

    public function calculateCupLeagueTable($fixture, $isGenerateStandingRequired = true)
    {
        $ageCategoryId = 0;
        $competitionId = 0;
        $fix1 = [];
        $competitionId = $fix1['CupFixture']['cupcompetition'] = $fixture->competition_id;
        $fix1['CupFixture']['hometeam'] = $fixture->home_team;
        $fix1['CupFixture']['awayteam'] = $fixture->away_team;
        $fix1['CupFixture']['tournamentId'] = $fixture->tournament_id;
        $fix1['CupFixture']['match_round'] = $fixture->round;
        $ageCategoryId = $fix1['CupFixture']['age_group_id'] = $fixture->age_group_id;
        $competition = Competition::where('id', $fixture->competition_id)->first();

        if ($fix1['CupFixture']['match_round'] == 'Round Robin' || ($competition->competation_type == 'Elimination' && $competition->actual_competition_type == 'Round Robin')) {
            if ($fix1['CupFixture']['hometeam'] == 0 || $fix1['CupFixture']['awayteam'] == 0) {
                return $fixture->competition_id;
            }
        }

        $cup_competition_id = $fix1['CupFixture']['cupcompetition'];
        $home = false;
        // Home team Id, away team id
        $home_tema_id[] = $fix1['CupFixture']['hometeam'];
        $away_team_id[] = $fix1['CupFixture']['awayteam'];
        // merge it
        $findTeams = array_merge($home_tema_id, $away_team_id);
        if ($fix1['CupFixture']['match_round'] == 'Elimination') {
            // So here we have to Call Function For Elimination Matches
            $competitionId = $this->calculateEliminationTeams($fixture);

            // changes for #247
            if ($competition->competation_type == 'Elimination' && $competition->actual_competition_type == 'Round Robin' && $isGenerateStandingRequired) {
                $this->generateStandingsForCompetitions($fix1['CupFixture']['tournamentId'], $cup_competition_id, $ageCategoryId, $findTeams, 'Elimination');
            }
            $this->updateCategoryPositions($competitionId, $ageCategoryId);

            return $competitionId;
            // end #247
        }

        $this->moveMatchStandings($fix1['CupFixture']['tournamentId'], $fix1['CupFixture']['age_group_id'], $cup_competition_id);

        if ($isGenerateStandingRequired) {
            $this->generateStandingsForCompetitions($fix1['CupFixture']['tournamentId'], $cup_competition_id, $ageCategoryId, $findTeams, 'Round Robin');
        }

        $this->updateCategoryPositions($competitionId, $ageCategoryId);

        return $cup_competition_id;
    }

    /*
      This function used for Team Assignment For Placing Matches
     */
    private function PMTeamAssignment($sendData)
    {

        $competition_id = $sendData['home']['competition_id'];
        $teams = DB::table('teams')->where('teams.competation_id', '=', $competition_id)
            ->leftJoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
            ->leftJoin('tournament_template', 'tournament_template.id', '=', 'tournament_competation_template.tournament_template_id')
            ->select('teams.id as TeamId', 'tournament_template.id as TemplateId', 'tournament_template.json_data as TemplateJson',
                'teams.name as TeamName', 'tournament_competation_template.group_name', 'tournament_competation_template.category_age')
            ->get();
        print_r($teams);
        exit;
        $templateJson = $teams[0]->TemplateJson;

    }

    private function TeamPMAssignKp($compId)
    {
        $processFixtures = [];
        $competition = Competition::find($compId);
        $tournament_id = $competition->tournament_id;
        $ageCategoryId = $competition->tournament_competation_template_id;

        $cupId = $compId;
        $calculatedArray = [];
        $head_to_head = false;

        //$cupRoundrobinData = $this->CupRoundrobin->find('first', array('conditions' => array('comp_id' => $cupId)));

        //$groupTeams = json_decode($cupRoundrobinData['CupRoundrobin']['groups'],true);
        $comp = DB::table('temp_fixtures')
                    // ->join('competitions','competitions.id','temp_fixtures.competition_id')
            ->where('temp_fixtures.competition_id', '=', $compId)->get();
        foreach ($comp as $key => $value) {
            $home_team_arr[] = $value->home_team;
            $away_team_arr[] = $value->away_team;
        }
        $teamList = array_unique(array_merge($home_team_arr, $away_team_arr));

        // $teams = DB::table('teams')->where('competation_id','=',$compId)->get();
        $teams = DB::table('teams')->whereIn('id', $teamList)->get();
        $defaultArray = ['Played' => 0, 'Won' => '0', 'Lost' => 0, 'Draw' => 0,
            'home_goal' => 0, 'away_goal' => 0, 'goal_difference' => 0, 'Total' => 0,
            'manual_override' => 0, 'group_winner' => 0, 'manual_order' => 0];
        // foreach ($groupTeams as $gkey => $gvvalue) {
        //    $i =1;
        foreach ($teams as $gkey => $gvalue) {
            //$teamExist = $this->CupLeagueTable->find('first', array('conditions'
            // => array('comp_id' => $cupId, 'team_id' => $gvalue)));
            // check in match standing table for that team and Group Id
            $teamExist = DB::table('match_standing')
                ->Join('teams', 'teams.id', '=', 'match_standing.team_id')
                ->Join('competitions', 'match_standing.competition_id', '=', 'competitions.id')
                ->select('teams.*', 'match_standing.*', 'competitions.name as compName')
                ->where('match_standing.competition_id', $cupId)
                ->where('teams.id', $gvalue->id)
                ->get()->first();
            // $winPoints = 3; $losePoints =0;$drawPoints=1;

            $tournamentCompetationTemplatesRecord = TournamentCompetationTemplates::where('id', $competition->tournament_competation_template_id)->first();
            $winPoints = $tournamentCompetationTemplatesRecord->win_point;
            $losePoints = $tournamentCompetationTemplatesRecord->loss_point;
            $drawPoints = $tournamentCompetationTemplatesRecord->draw_point;

            $assigned_group = '';
            if ($teamExist) {
                $group = explode('-', $teamExist->compName);
                $assigned_group = $group[count($group) - 2].'-'.$group[count($group) - 1];
            } else {
                return;
            }
            // $group = explode('-',$teamExist->compName);
            // echo "<pre>"; print_r($group); echo "</pre>";
            // $assigned_group = $group[2].'-'.$group[3];
            // print_r($teamExist);
            if ($teamExist) {

                $calculatedArray[$compId][$gvalue->id]['Played'] = $teamExist->played;
                $calculatedArray[$compId][$gvalue->id]['Won'] = $teamExist->won;
                $calculatedArray[$compId][$gvalue->id]['Lost'] = $teamExist->lost;
                $calculatedArray[$compId][$gvalue->id]['Draw'] = $teamExist->draws;
                $calculatedArray[$compId][$gvalue->id]['home_goal'] = $teamExist->goal_for;
                $calculatedArray[$compId][$gvalue->id]['away_goal'] = $teamExist->goal_against;
                $total = (((int) $teamExist->won * $winPoints) + ((int) $teamExist->draws * $drawPoints)) + ((int) $teamExist->lost * $losePoints);

                $goal_difference = ((int) $teamExist->goal_for - (int) $teamExist->goal_against);
                $calculatedArray[$compId][$gvalue->id]['goal_ratio'] = $teamExist->played > 0 ? $teamExist->goal_for / $teamExist->played : 0;
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
                $groupAlphabet = explode('-', $assigned_group);
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
                $groupAlphabet = explode('-', $assigned_group);
                $groupAlphabet = $groupAlphabet[1];
                // $calculatedArray[$compId][$gvalue->id]['teamAgeGroupPlaceHolder']
                //  =  $i.$groupAlphabet;

            }
            //  $i++;
        }
        // }
        // echo 'Before Sort';

        //  echo 'After Sort';
        $for_override_condition = [];
        foreach ($calculatedArray as $ckey => $cvalue) {
            $manual_order = $mid = $cid = $did = $eid = $overrride = $group_winner = $matchesWon = $goalRatio = $headToHead = $teamName = [];

            foreach ($cvalue as $cckey => $ccvalue) {
                $manual_order[$cckey] = (int) $ccvalue['manual_order'];
                $mid[$cckey] = (int) $ccvalue['Total'];
                // $cid[$cckey]  = (int)$ccvalue['Played'];

                $did[$cckey] = (int) $ccvalue['goal_difference'];
                $eid[$cckey] = (int) $ccvalue['home_goal'];
                $matchesWon[$cckey] = (int) $ccvalue['Won'];
                $goalRatio[$cckey] = $ccvalue['Played'] > 0 ? $ccvalue['home_goal'] / $ccvalue['Played'] : 0;
                $teamName[$cckey] = $ccvalue['teamName'];
                // $overrride[$cckey]  = (int)$ccvalue['manual_override'];
                // $group_winner[$cckey]  = (int)$ccvalue['group_winner'];
                // $for_override_condition[$ckey][$cckey] = (int)$ccvalue['manual_override'];
            }

            $checkResultEntered = DB::table('temp_fixtures')
                ->where('tournament_id', $tournament_id)
                ->where('competition_id', $compId)->where('round', 'Round Robin')
                ->where(function ($query) {
                    $query->whereNull('hometeam_score')
                        ->orWhereNull('awayteam_score');
                })->count();

            $params = [];
            $rules = $tournamentCompetationTemplatesRecord->rules;

            $head_to_head = false;
            $check_head_to_head_with_key = '';
            $remain_head_to_head_with_key = '';
            $head_to_head_order_atlast = false;
            for ($i = 0; $i < count($rules); $i++) {
                $rule = $rules[$i];

                if ($rule['checked'] == false || ($rule['key'] != 'head_to_head' && $head_to_head == true)) {

                    if ($rule['checked'] == true && ($rule['key'] != 'head_to_head' && $head_to_head == true)) {
                        if ($rule['key'] == 'goal_difference') {
                            $remain_head_to_head_with_key .= '|goal_difference';
                        }
                        if ($rule['key'] == 'goals_for') {
                            $remain_head_to_head_with_key .= '|home_goal';
                        }
                        if ($rule['key'] == 'matches_won') {
                            $remain_head_to_head_with_key .= '|Won';
                        }
                        if ($rule['key'] == 'goal_ratio') {
                            $remain_head_to_head_with_key .= '|goal_ratio';
                        }
                    }

                    continue;
                }

                if ($rule['key'] == 'match_points') {
                    $params[] = $mid;
                    $params[] = SORT_DESC;
                    $check_head_to_head_with_key .= '|Total';
                }

                if ($rule['key'] == 'head_to_head') {
                    if ($checkResultEntered > 0) {
                        $head_to_head_order_atlast = true;
                    } else {
                        $head_to_head = true;
                    }

                }

                if ($rule['key'] == 'goal_difference') {
                    $params[] = $did;
                    $params[] = SORT_DESC;
                    $check_head_to_head_with_key .= '|goal_difference';
                }
                if ($rule['key'] == 'goals_for') {
                    $params[] = $eid;
                    $params[] = SORT_DESC;
                    $check_head_to_head_with_key .= '|home_goal';
                }
                if ($rule['key'] == 'matches_won') {
                    $params[] = $matchesWon;
                    $params[] = SORT_DESC;
                    $check_head_to_head_with_key .= '|Won';
                }
                if ($rule['key'] == 'goal_ratio') {
                    $params[] = $goalRatio;
                    $params[] = SORT_DESC;
                    $check_head_to_head_with_key .= '|goal_ratio';
                }
            }

            if ($head_to_head_order_atlast) {
                $params[] = $teamName;
                $params[] = SORT_ASC;
                $check_head_to_head_with_key .= '|teamName';
            }

            if (! empty($check_head_to_head_with_key)) {
                $check_head_to_head_with_key = ltrim($check_head_to_head_with_key, '|');
            }

            if (! empty($remain_head_to_head_with_key)) {
                $remain_head_to_head_with_key = ltrim($remain_head_to_head_with_key, '|');
            }

            $params[] = &$cvalue;
            if ($competition->is_manual_override_standing == 1) {
                $params = [];
                $params[] = &$cvalue;
                $params = array_merge([$manual_order, SORT_ASC], $params);
            }

            array_multisort(...$params);
            $calculatedArray[$ckey] = $cvalue;
        }

        if ($head_to_head && $competition->is_manual_override_standing == 0) {

            $calculatedArray = array_shift($calculatedArray);
            [$calculatedArray, $sort_head_to_head] = $this->matchRepoObj->sortByHeadtoHead($calculatedArray, $check_head_to_head_with_key, $tournament_id, $compId, $tournamentCompetationTemplatesRecord, $remain_head_to_head_with_key);

            if ($sort_head_to_head == '1') {
                $head_to_head_position_sorting = [];
                $internal_head_to_head_position_sorting = [];
                foreach ($calculatedArray as $comp_stand_key => $comp_stand_value) {
                    $head_to_head_position_sorting[$comp_stand_key] = (int) $comp_stand_value['head_to_head_position'];
                    $internal_head_to_head_position_sorting[$comp_stand_key] = (int) $comp_stand_value['internal_head_to_head_position'];
                }
                array_multisort($internal_head_to_head_position_sorting, SORT_ASC, $calculatedArray);
            }

            $setCalculatedArray[$cupId] = $calculatedArray;
            $calculatedArray = $setCalculatedArray;
        }

        $i = 1;
        if (count($calculatedArray) > 0) {
            foreach ($calculatedArray[$cupId] as $kky => $data) {
                $groupAlphabet = explode('-', $data['teamGroup']);
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
        $temptournamentId = 0;
        $particularGroup = '';
        if (isset($calculatedArray[$cupId][0]['teamAgeGroup'])) {
            $temptournamentId = $teamExist->tournament_id;
            $ageGroupId = $calculatedArray[$cupId][0]['teamAgeGroup'];
            $particularGroup = $calculatedArray[$cupId][0]['teamGroup'];
        }

        $reportQuery = DB::table('temp_fixtures')
            ->select('temp_fixtures.id as matchID', 'temp_fixtures.match_number as MatchNumber', 'temp_fixtures.home_team_name as HomeTeam', 'temp_fixtures.home_team as HomeTeamId', 'temp_fixtures.away_team_name as AwayTeam',
                'temp_fixtures.home_team_placeholder_name as HomeTeamPlaceHolderName', 'temp_fixtures.away_team_placeholder_name as AwayTeamPlaceHolderName',
                'temp_fixtures.away_team as AwayTeamId', 'competitions.competation_round_no as competition_round')
            ->leftJoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
            ->leftJoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
            ->leftJoin('tournament_template', 'tournament_template.id', '=', 'tournament_competation_template.tournament_template_id')
            ->where('competitions.tournament_competation_template_id', '=', $ageGroupId)
            ->where('temp_fixtures.tournament_id', '=', $temptournamentId)
            ->get();
        $matches = $reportQuery;

        $tournamentCompetationTemplate = TournamentCompetationTemplates::find($ageCategoryId);
        $isBestRankingExist = $this->checkBestRankingExistInSecondRound($ageGroupId, $temptournamentId);
        if ($isBestRankingExist) {
            $firstRoundPositionWiseStandings = $this->getPositionWiseStandingsForAllGroupsOfRound($ageCategoryId, $tournamentCompetationTemplate->tournament_id, $tournamentCompetationTemplate->competition_type);
        }

        // print_r($matches);exit;
        //print_r($matches);exit;
        // dd($calculatedArray[$cupId]);
        if ($matches) {
            foreach ($matches as $key => $match) {
                //$templateData = json_decode($match->JsonData,true);
                // echo "<pre>"; print_r($match); echo "</pre>";
                $exmatchNumber = explode('.', $match->MatchNumber);
                $value = explode('-', $exmatchNumber[2]);
                $homeTeam = $value[0];
                //$homeTeam = $match->HomeTeam;
                if ($homeTeam) {
                    $isRankingPosition = false;
                    if ($isBestRankingExist && $firstRoundPositionWiseStandings['areAllCompetitionEnded'] === true && $match->competition_round === 'Round 2') {
                        if (strpos($homeTeam, '#') > 0) {
                            $this->updateRankingPositionInMatchForKnockout($homeTeam, $firstRoundPositionWiseStandings, $match, 'home');
                            $processFixtures[] = $match->matchID;
                            $isRankingPosition = true;
                        }
                    }
                    foreach ($calculatedArray[$cupId] as $dd1) {
                        if (! $isRankingPosition && $homeTeam == $dd1['teamAgeGroupPlaceHolder']) {
                            $processFixtures[] = $match->matchID;
                            //echo $matchId = $match->matchID;
                            //echo $matchNumber = $match->MatchNumber;

                            $homeTeamId = $dd1['teamid'];
                            $updateArray = [
                                'home_team_name' => $dd1['teamName'],
                                'home_team' => $dd1['teamid'],
                            ];

                            if ($this->checkForEndRR($cupId) == true) {
                                DB::table('temp_fixtures')->where('id', $match->matchID)->update($updateArray);
                            } else {
                                DB::table('temp_fixtures')->where('id', $match->matchID)->update(
                                    [
                                        'home_team_name' => $match->HomeTeamPlaceHolderName,
                                        'home_team' => 0,
                                        'hometeam_score' => null,
                                        'awayteam_score' => null,
                                    ]
                                );
                            }
                            unset($updateArray);
                            //echo '<br>';
                        }
                        // check if value is changed
                        //if($match->HomeTeamId != $dd1[''])

                    }
                }
                $awayTeam = $value[1];
                //$awayTeam = $match->AwayTeam;
                if ($awayTeam) {
                    $isRankingPosition = false;
                    if ($isBestRankingExist && $firstRoundPositionWiseStandings['areAllCompetitionEnded'] === true && $match->competition_round === 'Round 2') {
                        if (strpos($awayTeam, '#') > 0) {
                            $this->updateRankingPositionInMatchForKnockout($awayTeam, $firstRoundPositionWiseStandings, $match, 'away');
                            $processFixtures[] = $match->matchID;
                            $isRankingPosition = true;
                        }
                    }
                    foreach ($calculatedArray[$cupId] as $dd1) {
                        if (! $isRankingPosition && $awayTeam == $dd1['teamAgeGroupPlaceHolder']) {
                            $processFixtures[] = $match->matchID;

                            $awayTeamId = $dd1['teamid'];
                            $updateArray = [
                                'away_team_name' => $dd1['teamName'],
                                'away_team' => $dd1['teamid'],
                            ];
                            if ($this->checkForEndRR($cupId) == true) {
                                DB::table('temp_fixtures')->where('id', $match->matchID)->update($updateArray);
                            } else {
                                DB::table('temp_fixtures')->where('id', $match->matchID)->update(
                                    [
                                        'away_team_name' => $match->AwayTeamPlaceHolderName,
                                        'away_team' => 0,
                                        'hometeam_score' => null,
                                        'awayteam_score' => null,
                                    ]
                                );
                            }
                            unset($updateArray);
                        } else {
                            // echo 'hi-Away';
                        }
                    }
                }
                // else check if its new change
            }
            $this->processFixtures($processFixtures);
        }

    }

    private function TeamPMAssign($data)
    {
        $compId = $data['home']['competition_id'];
        $cupId = $compId;

        //$cupRoundrobinData = $this->CupRoundrobin->find('first', array('conditions' => array('comp_id' => $cupId)));

        //$groupTeams = json_decode($cupRoundrobinData['CupRoundrobin']['groups'],true);
        $teams = DB::table('teams')->where('competation_id', '=', $compId)->get();
        // print_r($teams);exit;
        $defaultArray = ['Played' => 0, 'Won' => '0', 'Lost' => 0, 'Draw' => 0,
            'home_goal' => 0, 'away_goal' => 0, 'goal_difference' => 0, 'Total' => 0,
            'manual_override' => 0, 'group_winner' => 0];
        $calculatedArray = [];
        // foreach ($groupTeams as $gkey => $gvvalue) {
        //    $i =1;
        foreach ($teams as $gkey => $gvalue) {
            //$teamExist = $this->CupLeagueTable->find('first', array('conditions'
            // => array('comp_id' => $cupId, 'team_id' => $gvalue)));
            // check in match standing table for that team and Group Id
            $teamExist = DB::table('match_standing')
                ->leftJoin('teams', 'teams.id', '=', 'match_standing.team_id')
                ->select('teams.*', 'match_standing.*')
                ->where('match_standing.competition_id', $cupId)
                ->where('teams.id', $gvalue->id)
                ->get()->first();
            //$winPoints = 3; $losePoints =0;$drawPoints=1;

            $tournamentCompetationTemplatesRecord = TournamentCompetationTemplates::where('id', $teamExist->age_group_id)->get()->first();

            $winPoints = $tournamentCompetationTemplatesRecord->win_point;
            $losePoints = $tournamentCompetationTemplatesRecord->loss_point;
            $drawPoints = $tournamentCompetationTemplatesRecord->draw_point;
            //print_r($teamExist);
            if (count($teamExist) > 0) {

                $calculatedArray[$gvalue->competation_id][$gvalue->id]['Played'] = $teamExist->played;
                $calculatedArray[$gvalue->competation_id][$gvalue->id]['Won'] = $teamExist->won;
                $calculatedArray[$gvalue->competation_id][$gvalue->id]['Lost'] = $teamExist->lost;
                $calculatedArray[$gvalue->competation_id][$gvalue->id]['Draw'] = $teamExist->draws;
                $calculatedArray[$gvalue->competation_id][$gvalue->id]['home_goal'] = $teamExist->goal_for;
                $calculatedArray[$gvalue->competation_id][$gvalue->id]['away_goal'] = $teamExist->goal_against;
                $total = (((int) $teamExist->won * $winPoints) + ((int) $teamExist->draws * $drawPoints)) + ((int) $teamExist->lost * $losePoints);

                $goal_difference = ((int) $teamExist->goal_for - (int) $teamExist->goal_against);
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
                $groupAlphabet = explode('-', $teamExist->assigned_group);
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
                $groupAlphabet = explode('-', $teamExist->assigned_group);
                $groupAlphabet = $groupAlphabet[1];
                // $calculatedArray[$gvalue->competation_id][$gvalue->id]['teamAgeGroupPlaceHolder']
                //  =  $i.$groupAlphabet;

            }
            //  $i++;
        }
        // }
        // echo 'Before Sort';

        //  echo 'After Sort';
        $for_override_condition = [];
        foreach ($calculatedArray as $ckey => $cvalue) {
            $mid = $cid = $did = $overrride = $group_winner = [];
            foreach ($cvalue as $cckey => $ccvalue) {

                $mid[$cckey] = (int) $ccvalue['Total'];
                $cid[$cckey] = (int) $ccvalue['Played'];
                $did[$cckey] = (int) $ccvalue['goal_difference'];
                // $overrride[$cckey]  = (int)$ccvalue['manual_override'];
                // $group_winner[$cckey]  = (int)$ccvalue['group_winner'];
                // $for_override_condition[$ckey][$cckey] = (int)$ccvalue['manual_override'];
            }

            array_multisort($mid, SORT_DESC, $did, SORT_DESC, $cid, SORT_DESC, $cvalue);
            $calculatedArray[$ckey] = $cvalue;
        }
        $i = 1;

        if (count($calculatedArray) > 0) {
            foreach ($calculatedArray[$cupId] as $kky => $data) {
                $groupAlphabet = explode('-', $data['teamGroup']);
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
        $temptournamentId = 0;
        $particularGroup = '';
        if (isset($calculatedArray[$cupId][0]['teamAgeGroup'])) {
            $temptournamentId = $teamExist->tournament_id;
            $ageGroupId = $calculatedArray[$cupId][0]['teamAgeGroup'];
            $particularGroup = $calculatedArray[$cupId][0]['teamGroup'];
        }

        $reportQuery = DB::table('temp_fixtures')
            ->select('temp_fixtures.id as matchID', 'temp_fixtures.match_number as MatchNumber', 'temp_fixtures.home_team_name as HomeTeam', 'temp_fixtures.home_team as HomeTeamId', 'temp_fixtures.away_team_name as AwayTeam',

                'temp_fixtures.away_team as AwayTeamId')
            ->leftJoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
            ->leftJoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
            ->leftJoin('tournament_template', 'tournament_template.id', '=', 'tournament_competation_template.tournament_template_id')
            ->where('competitions.tournament_competation_template_id', '=', $ageGroupId)
            ->where('temp_fixtures.tournament_id', '=', $temptournamentId)
            ->get();
        // print_r($calculatedArray);
        //exit;
        // print_r($reportQuery);
        // exit;
        $matches = $reportQuery;
        //print_r($matches);exit;
        //print_r($matches);exit;
        if ($matches) {
            foreach ($matches as $key => $match) {
                //$templateData = json_decode($match->JsonData,true);
                $exmatchNumber = explode('.', $match->MatchNumber);
                //print_r($exmatchNumber);exit;
                $value = explode('-', $exmatchNumber[2]);
                $homeTeam = $value[0];

                //$homeTeam = $match->HomeTeam;

                if ($homeTeam) {
                    foreach ($calculatedArray[$cupId] as $dd1) {

                        if ($homeTeam == $dd1['teamAgeGroupPlaceHolder']) {
                            //echo $matchId = $match->matchID;
                            //echo $matchNumber = $match->MatchNumber;

                            $updatedMatchNumer = str_replace($homeTeam, $dd1['teamName'], $match->MatchNumber);
                            $homeTeamId = $dd1['teamid'];
                            $updateArray = [
                                'home_team_name' => $dd1['teamName'],
                                'home_team' => $dd1['teamid'],
                            ];
                            // echo '<pre>';
                            //  print_r($updateArray);
                            if ($this->checkForEndRR($cupId) == true) {
                                DB::table('temp_fixtures')->where('id', $match->matchID)->update($updateArray);
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
                if ($awayTeam) {
                    foreach ($calculatedArray[$cupId] as $dd1) {
                        if ($awayTeam == $dd1['teamAgeGroupPlaceHolder']) {
                            $updatedMatchNumer = str_replace($awayTeam, $dd1['teamName'], $match->MatchNumber);
                            $awayTeamId = $dd1['teamid'];
                            $updateArray = [
                                'away_team_name' => $dd1['teamName'],
                                'away_team' => $dd1['teamid'],
                            ];
                            if ($this->checkForEndRR($cupId) == true) {
                                DB::table('temp_fixtures')->where('id', $match->matchID)->update($updateArray);
                                unset($updateArray);
                            }
                        } else {
                            // echo 'hi-Away';
                        }
                    }
                }

                // else check if its new change

            }

        }

    }

    private function checkForEndRR($competationId)
    {
        // here we check if any unschedule match for that competations if no return yes else no

        $matches = DB::table('temp_fixtures')
            ->where('competition_id', $competationId)
            ->where('round', '=', 'Round Robin')
            ->whereRaw(Db::raw('(hometeam_score IS NULL OR awayteam_score IS NULL)'));

        if ($matches->exists()) {
            //echo 'hellofalse';
            return false;
        } else {
            //echo 'hellotrue';
            return true;
        }
    }

    public function saveStandingsManually($request)
    {
        $data = $request->all()['data'];
        $this->matchRepoObj->saveStandingsManually($data);
        $data = ['tournamentId' => $data['tournament_id'], 'competitionId' => $data['competitionId']];
        $this->refreshCompetitionStandings($data);

        return ['status_code' => '200', 'message' => 'Ranking has been updated successfully.'];
    }

    public function generateStandingsForCompetitions($tournamentId, $cup_competition_id, $ageCategoryId, $findTeams, $competitionType, $assignTeamsToFurtherRounds = true)
    {
        $matches = DB::table('temp_fixtures')
            ->where('tournament_id', '=', $tournamentId)
            ->where('competition_id', '=', $cup_competition_id)
            ->where(function ($query) use ($findTeams) {
                $query->whereIn('away_team', $findTeams)
                    ->orWhereIn('home_team', $findTeams);
            })->where('round', '=', $competitionType)->get();

        $fixtu = [];
        foreach ($matches as $key1 => $match) {
            $fixtu[$key1]['CupFixture']['hometeamscore'] = (string) $match->hometeam_score;
            $fixtu[$key1]['CupFixture']['awayteamscore'] = (string) $match->awayteam_score;

            $fixtu[$key1]['CupFixture']['hometeam'] = $match->home_team;
            $fixtu[$key1]['CupFixture']['awayteam'] = $match->away_team;
            $fixtu[$key1]['CupFixture']['HomeTeamScoreAfterExtraTime'] = '';
        }

        $comp_fixtures = $fixtu;
        $ageGroupList = [];

        foreach ($findTeams as $team) {
            $ageGroupList[$team] = ['Played' => 0, 'Won' => '0', 'Lost' => 0, 'Draw' => 0, 'home_goal' => 0, 'away_goal' => 0];
        }

        foreach ($findTeams as $team) {
            foreach ($comp_fixtures as $key => $fix) {
                $winnerTeam = 'nd';

                if ($fix['CupFixture']['hometeamscore'] != '' && ($fix['CupFixture']['awayteamscore'] != '') && empty($fix['CupFixture']['Abandoned'])) {
                    if ($fix['CupFixture']['hometeamscore'] == $fix['CupFixture']['awayteamscore']) {
                        if ($fix['CupFixture']['HomeTeamScoreAfterExtraTime'] != '' && $fix['CupFixture']['AwayTeamScoreAfterExtraTime'] != '') {
                            if ($fix['CupFixture']['HomeTeamScoreAfterExtraTime'] == $fix['CupFixture']['AwayTeamScoreAfterExtraTime']) {
                                if ($fix['CupFixture']['HomeTeamScoreAfterPen'] != '' && $fix['CupFixture']['AwayTeamScoreAfterPen'] != '') {
                                    if ($fix['CupFixture']['HomeTeamScoreAfterPen'] == $fix['CupFixture']['AwayTeamScoreAfterPen']) {
                                        $winnerTeam = -1;
                                    } else {
                                        if ($fix['CupFixture']['HomeTeamScoreAfterPen'] > $fix['CupFixture']['AwayTeamScoreAfterPen']) {
                                            $winnerTeam = $fix['CupFixture']['hometeam'];
                                            $home = true;
                                        } else {
                                            $winnerTeam = $fix['CupFixture']['awayteam'];
                                        }
                                    }
                                } else {
                                    $winnerTeam = -1;
                                }
                            } else {

                                // Hometeamscore extratime is greter than awayteamscore
                                if ($fix['CupFixture']['HomeTeamScoreAfterExtraTime'] > $fix['CupFixture']['AwayTeamScoreAfterExtraTime']) {
                                    $winnerTeam = $fix['CupFixture']['hometeam'];
                                    $home = true;
                                } else {
                                    $winnerTeam = $fix['CupFixture']['awayteam'];
                                }
                            }
                        } else {
                            $winnerTeam = -1;
                        }
                    } else {
                        if ($fix['CupFixture']['hometeamscore'] > $fix['CupFixture']['awayteamscore']) {
                            $winnerTeam = $fix['CupFixture']['hometeam'];
                            $home = true;
                        } else {
                            $winnerTeam = $fix['CupFixture']['awayteam'];
                        }
                    }
                } else {
                    if (! empty($fix['CupFixture']['Abandoned'])) {
                        if ($fix['CupFixture']['Abandoned'] == 'HomeWin') {
                            $winnerTeam = $fix['CupFixture']['hometeam'];
                        }

                        if ($fix['CupFixture']['Abandoned'] == 'AwayWin') {
                            $winnerTeam = $fix['CupFixture']['awayteam'];
                        }

                        if ($fix['CupFixture']['Abandoned'] == 'Draw') {
                            $winnerTeam = -1;
                        }
                    }
                }

                if ($winnerTeam != 'nd') {
                    if ($winnerTeam == -1) {
                        // 1. check if has same Home id
                        if ($team == $fix['CupFixture']['hometeam']) {
                            $ageGroupList[$team]['Played'] = (int) $ageGroupList[$team]['Played'] + 1;
                            $ageGroupList[$team]['Draw'] = (int) $ageGroupList[$team]['Draw'] + 1;
                            if ($fix['CupFixture']['hometeamscore'] != '') {
                                $ageGroupList[$team]['home_goal'] = (int) $ageGroupList[$team]['home_goal'] + (int) $fix['CupFixture']['hometeamscore'];
                            }
                            if ($fix['CupFixture']['awayteamscore'] != '') {
                                $ageGroupList[$team]['away_goal'] = (int) $ageGroupList[$team]['away_goal'] + (int) $fix['CupFixture']['awayteamscore'];
                            }
                        } else {
                            if ($team == $fix['CupFixture']['awayteam']) {
                                $ageGroupList[$team]['Played'] =
                                (int) $ageGroupList[$team]['Played'] + 1;
                                $ageGroupList[$team]['Draw'] = (int) $ageGroupList[$team]['Draw'] + 1;

                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$team]['home_goal'] = (int) $ageGroupList[$team]['home_goal'] + (int) $fix['CupFixture']['awayteamscore'];
                                }

                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$team]['away_goal'] = (int) $ageGroupList[$team]['away_goal'] + (int) $fix['CupFixture']['hometeamscore'];
                                }
                            }
                        }
                    } else {
                        // 1 if home team is Winner
                        if ($winnerTeam == $team) {
                            $ageGroupList[$team]['Played'] =
                            (int) $ageGroupList[$team]['Played'] + 1;

                            $ageGroupList[$team]['Won'] = (int) $ageGroupList[$team]['Won'] + 1;

                            if ($team == $fix['CupFixture']['hometeam']) {
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$team]['home_goal'] = (int) $ageGroupList[$team]['home_goal'] + (int) $fix['CupFixture']['hometeamscore'];
                                }
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$team]['away_goal'] = (int) $ageGroupList[$team]['away_goal'] + (int) $fix['CupFixture']['awayteamscore'];
                                }
                            } else {
                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$team]['home_goal'] = (int) $ageGroupList[$team]['home_goal'] + (int) $fix['CupFixture']['awayteamscore'];
                                }
                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$team]['away_goal'] = (int) $ageGroupList[$team]['away_goal'] + (int) $fix['CupFixture']['hometeamscore'];
                                }
                            }
                        } else {
                            if ($team == $fix['CupFixture']['hometeam']) {
                                $ageGroupList[$team]['Played'] = (int) $ageGroupList[$team]['Played'] + 1;
                                $ageGroupList[$team]['Lost'] = (int) $ageGroupList[$team]['Lost'] + 1;

                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$team]['home_goal'] = (int) $ageGroupList[$team]['home_goal'] + (int) $fix['CupFixture']['hometeamscore'];
                                }

                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$team]['away_goal'] = (int) $ageGroupList[$team]['away_goal'] + (int) $fix['CupFixture']['awayteamscore'];
                                }
                            }

                            if ($team == $fix['CupFixture']['awayteam']) {
                                $ageGroupList[$team]['Played'] = (int) $ageGroupList[$team]['Played'] + 1;
                                $ageGroupList[$team]['Lost'] = (int) $ageGroupList[$team]['Lost'] + 1;

                                if ($fix['CupFixture']['awayteamscore'] != '') {
                                    $ageGroupList[$team]['home_goal'] = (int) $ageGroupList[$team]['home_goal'] + (int) $fix['CupFixture']['awayteamscore'];
                                }

                                if ($fix['CupFixture']['hometeamscore'] != '') {
                                    $ageGroupList[$team]['away_goal'] = (int) $ageGroupList[$team]['away_goal'] + (int) $fix['CupFixture']['hometeamscore'];
                                }
                            }
                        }
                    }
                }
            }
        }

        $tournamentCompetationTemplatesRecord = TournamentCompetationTemplates::where('id', $ageCategoryId)->get()->first();
        $winningPoints = $tournamentCompetationTemplatesRecord->win_point;
        $losePoints = $tournamentCompetationTemplatesRecord->loss_point;
        $drawPoints = $tournamentCompetationTemplatesRecord->draw_point;

        foreach ($findTeams as $team) {
            $teamExist = DB::table('match_standing')
                ->where('tournament_id', '=', $tournamentId)
                ->where('competition_id', '=', $cup_competition_id)
                ->where('team_id', $team)
                ->get()->first();

            if ($teamExist) {
                $data = [];

                $data['points'] = $ageGroupList[$team]['Won'] * $winningPoints + $ageGroupList[$team]['Draw'] * $drawPoints + $ageGroupList[$team]['Lost'] * $losePoints;
                $data['played'] = $ageGroupList[$team]['Played'];
                $data['won'] = $ageGroupList[$team]['Won'];
                $data['draws'] = $ageGroupList[$team]['Draw'];
                $data['lost'] = $ageGroupList[$team]['Lost'];
                $data['goal_for'] = $ageGroupList[$team]['home_goal'];
                $data['goal_against'] = $ageGroupList[$team]['away_goal'];

                DB::table('match_standing')->where('id', $teamExist->id)->update($data);
            } else {
                $data3 = [];
                $data3['competition_id'] = $cup_competition_id;
                $data3['tournament_id'] = $tournamentId;
                $data3['team_id'] = $team;
                $data3['points'] = $ageGroupList[$team]['Won'] * $winningPoints + $ageGroupList[$team]['Draw'] * $drawPoints + $ageGroupList[$team]['Lost'] * $losePoints;
                $data3['played'] = $ageGroupList[$team]['Played'];
                $data3['won'] = $ageGroupList[$team]['Won'];
                $data3['draws'] = $ageGroupList[$team]['Draw'];
                $data3['lost'] = $ageGroupList[$team]['Lost'];
                $data3['goal_for'] = $ageGroupList[$team]['home_goal'];
                $data3['goal_against'] = $ageGroupList[$team]['away_goal'];

                $teamManualRanking = TeamManualRanking::where('tournament_id', '=', $tournamentId)->where('competition_id', '=', $cup_competition_id)->where('team_id', '=', $team)->first();
                if ($teamManualRanking) {
                    $data3['manual_order'] = $teamManualRanking->manual_order;
                    $teamManualRanking->delete();
                }

                DB::table('match_standing')->insert($data3);
            }
        }

        if ($assignTeamsToFurtherRounds) {
            $this->TeamPMAssignKp($cup_competition_id);
        }
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

        if ($roundName == 'Round 1' && $notToAllowRoundOne == false) {
            $splittedMatchNumber[3] = '@HOME-@AWAY';
            $displayHomeTeamPlaceHolderName = $homeAwayTeamPlaceHolder[0];
            $displayAwayTeamPlaceHolderName = $homeAwayTeamPlaceHolder[1];
        }

        if ($roundName != 'Round 1' || $notToAllowRoundOne) {
            if (strpos($homeTeamPlaceHolder, '(') !== false && strpos($awayTeamPlaceHolder, '(') !== false) {
                $bracketStarted = false;

                // For home team
                $isWinnerOrLooser = null;
                if ((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                    $isWinnerOrLooser = '_WR';
                }
                if ((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                    $isWinnerOrLooser = '_LR';
                }

                $changedHomeTeamPlaceHolder = str_replace('(', '', $homeTeamPlaceHolder);
                $changedHomeTeamPlaceHolder = str_replace(')', '', $changedHomeTeamPlaceHolder);
                $changedHomeTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedHomeTeamPlaceHolder));
                $changedHomeTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedHomeTeamPlaceHolder[0]));
                $changedHomeTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedHomeTeamPlaceHolder[1]));

                $displayHomeTeamPlaceHolderName = $changedHomeTeamPlaceHolder[0].'.'.$changedHomeTeamPlaceHolder[1];

                if ($isWinnerOrLooser == '_WR') {
                    $splittedMatchNumber[3] = 'wrs.(@HOME';
                    $bracketStarted = true;
                } elseif ($isWinnerOrLooser == '_LR') {
                    $splittedMatchNumber[3] = 'lrs.(@HOME';
                    $bracketStarted = true;
                }

                // For away team
                $isWinnerOrLooser = null;
                if ((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                    $isWinnerOrLooser = '_WR';
                }
                if ((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                    $isWinnerOrLooser = '_LR';
                }

                $changedAwayTeamPlaceHolder = str_replace('(', '', $awayTeamPlaceHolder);
                $changedAwayTeamPlaceHolder = str_replace(')', '', $changedAwayTeamPlaceHolder);
                $changedAwayTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedAwayTeamPlaceHolder));
                $changedAwayTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedAwayTeamPlaceHolder[0]));
                $changedAwayTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedAwayTeamPlaceHolder[1]));

                $displayAwayTeamPlaceHolderName = $changedAwayTeamPlaceHolder[0].'.'.$changedAwayTeamPlaceHolder[1];

                if ($bracketStarted) {
                    $splittedMatchNumber[3] .= '-@AWAY)';
                } else {
                    if ($isWinnerOrLooser == '_WR') {
                        $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                    } elseif ($isWinnerOrLooser == '_LR') {
                        $splittedMatchNumber[3] .= '-lrs.(@AWAY)';
                    }
                }
            } elseif (strpos($homeTeamPlaceHolder, '(') === false && strpos($awayTeamPlaceHolder, '(') === false) {
                if (strpos($homeTeamPlaceHolder, '_WR') === false && strpos($homeTeamPlaceHolder, '_LR') === false && strpos($awayTeamPlaceHolder, '_WR') === false && strpos($awayTeamPlaceHolder, '_LR') === false) {
                    $splittedMatchNumber[3] = '@HOME-@AWAY';
                    $displayMatchNumber = $splittedMatchNumber[0].'.'.$splittedMatchNumber[1].'.'.$splittedMatchNumber[2].'.'.$splittedMatchNumber[3];

                    $displayHomeTeamPlaceHolderName = '#'.$homeAwayTeamPlaceHolder[0];
                    $displayAwayTeamPlaceHolderName = '#'.$homeAwayTeamPlaceHolder[1];
                } elseif (strpos($homeTeamPlaceHolder, '_WR') !== false && strpos($awayTeamPlaceHolder, '_WR') !== false) {
                    $splittedMatchNumber[3] = 'wrs.(@HOME-@AWAY)';
                    $displayMatchNumber = $splittedMatchNumber[0].'.'.$splittedMatchNumber[1].'.'.$splittedMatchNumber[2].'.'.$splittedMatchNumber[3];

                    // Get home placeholder
                    $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace('_WR', '', $homeTeamPlaceHolder));
                    $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchHomeTeamPlaceHolder) {
                        if (strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if (count($searchHomeTeamMatchNumber) == 1) {
                        $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                        $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                        $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                        $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1].'.'.$splittedSearchHomeTeamMatchNumber[2];
                    } else {
                        echo 'issue found'.$homeTeamPlaceHolder;
                    }

                    // Get away placeholder
                    $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace('_WR', '', $awayTeamPlaceHolder));
                    $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchAwayTeamPlaceHolder) {
                        if (strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if (count($searchAwayTeamMatchNumber) == 1) {
                        $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                        $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                        $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                        $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1].'.'.$splittedSearchAwayTeamMatchNumber[2];
                    } else {
                        echo 'issue found'.$awayTeamPlaceHolder;
                    }
                } elseif (strpos($homeTeamPlaceHolder, '_LR') !== false && strpos($awayTeamPlaceHolder, '_LR') !== false) {
                    $splittedMatchNumber[3] = 'lrs.(@HOME-@AWAY)';
                    $displayMatchNumber = $splittedMatchNumber[0].'.'.$splittedMatchNumber[1].'.'.$splittedMatchNumber[2].'.'.$splittedMatchNumber[3];

                    // Get home placeholder
                    $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace('_LR', '', $homeTeamPlaceHolder));
                    $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchHomeTeamPlaceHolder) {
                        if (strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if (count($searchHomeTeamMatchNumber) == 1) {
                        $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                        $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                        $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                        $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1].'.'.$splittedSearchHomeTeamMatchNumber[2];
                    } else {
                        echo 'issue found'.$homeTeamPlaceHolder;
                    }

                    // Get away placeholder
                    $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace('_LR', '', $awayTeamPlaceHolder));
                    $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchAwayTeamPlaceHolder) {
                        if (strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                            return $value;
                        }
                    });
                    if (count($searchAwayTeamMatchNumber) == 1) {
                        $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                        $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                        $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                        $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1].'.'.$splittedSearchAwayTeamMatchNumber[2];
                    } else {
                        echo 'issue found'.$awayTeamPlaceHolder;
                    }
                } elseif (strpos($homeTeamPlaceHolder, '_WR') !== false || strpos($homeTeamPlaceHolder, '_LR') !== false || strpos($awayTeamPlaceHolder, '_WR') !== false || strpos($awayTeamPlaceHolder, '_LR') !== false) {
                    $bracketStarted = false;
                    if ((strpos($homeTeamPlaceHolder, '_WR') !== false || strpos($homeTeamPlaceHolder, '_LR') !== false)) {

                        $isWinnerOrLooser = null;
                        if ((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if ((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get home placeholder
                        $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $homeTeamPlaceHolder));
                        $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchHomeTeamPlaceHolder) {
                            if (strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if (count($searchHomeTeamMatchNumber) == 1) {
                            $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                            $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                            $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                            $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1].'.'.$splittedSearchHomeTeamMatchNumber[2];

                            if ($isWinnerOrLooser == '_WR') {
                                $splittedMatchNumber[3] = 'wrs.(@HOME';
                                $bracketStarted = true;
                            } elseif ($isWinnerOrLooser == '_LR') {
                                $splittedMatchNumber[3] = 'lrs.(@HOME';
                                $bracketStarted = true;
                            }
                        } else {
                            echo 'issue found';
                        }
                    } else {
                        $displayHomeTeamPlaceHolderName = '#'.$homeAwayTeamPlaceHolder[0];
                        $splittedMatchNumber[3] = '@HOME';
                    }

                    if ((strpos($awayTeamPlaceHolder, '_WR') !== false || strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                        $isWinnerOrLooser = null;
                        if ((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if ((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get away placeholder
                        $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $awayTeamPlaceHolder));
                        $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchAwayTeamPlaceHolder) {
                            if (strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if (count($searchAwayTeamMatchNumber) == 1) {
                            $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                            $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                            $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                            $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1].'.'.$splittedSearchAwayTeamMatchNumber[2];

                            if ($bracketStarted) {
                                $splittedMatchNumber[3] .= '-@AWAY)';
                            } else {
                                if ($isWinnerOrLooser == '_WR') {
                                    $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                                } elseif ($isWinnerOrLooser == '_LR') {
                                    $splittedMatchNumber[3] .= '-lrs.(@AWAY)';
                                }
                            }
                        } else {
                            echo 'issue found';
                        }
                    } else {
                        $displayAwayTeamPlaceHolderName = '#'.$homeAwayTeamPlaceHolder[1];
                        $splittedMatchNumber[3] = '-@AWAY';
                    }
                }
            } elseif (strpos($homeTeamPlaceHolder, '(') !== false || strpos($awayTeamPlaceHolder, '(') !== false) {
                $bracketStarted = false;
                if (strpos($homeTeamPlaceHolder, '(') === false) {
                    if ((strpos($homeTeamPlaceHolder, '_WR') !== false || strpos($homeTeamPlaceHolder, '_LR') !== false)) {

                        $isWinnerOrLooser = null;
                        if ((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if ((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get home placeholder
                        $searchHomeTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $homeTeamPlaceHolder));
                        $searchHomeTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchHomeTeamPlaceHolder) {
                            if (strpos($value, $searchHomeTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if (count($searchHomeTeamMatchNumber) == 1) {
                            $splittedSearchHomeTeamMatchNumber = explode('.', array_values($searchHomeTeamMatchNumber)[0]);
                            $splittedSearchHomeTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchHomeTeamMatchNumber[1]));
                            $splittedSearchHomeTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchHomeTeamMatchNumber[2]));
                            $displayHomeTeamPlaceHolderName = $splittedSearchHomeTeamMatchNumber[1].'.'.$splittedSearchHomeTeamMatchNumber[2];

                            if ($isWinnerOrLooser == '_WR') {
                                $splittedMatchNumber[3] = 'wrs.(@HOME';
                                $bracketStarted = true;
                            } elseif ($isWinnerOrLooser == '_LR') {
                                $splittedMatchNumber[3] = 'lrs.(@HOME';
                                $bracketStarted = true;
                            }
                        } else {
                            echo 'issue found'.$homeTeamPlaceHolder;
                        }

                    } else {
                        $displayHomeTeamPlaceHolderName = '#'.$homeAwayTeamPlaceHolder[0];
                        $splittedMatchNumber[3] = '@HOME';
                    }
                } else {
                    $isWinnerOrLooser = null;
                    if ((strpos($homeTeamPlaceHolder, '_WR') !== false)) {
                        $isWinnerOrLooser = '_WR';
                    }
                    if ((strpos($homeTeamPlaceHolder, '_LR') !== false)) {
                        $isWinnerOrLooser = '_LR';
                    }

                    $changedHomeTeamPlaceHolder = str_replace('(', '', $homeTeamPlaceHolder);
                    $changedHomeTeamPlaceHolder = str_replace(')', '', $changedHomeTeamPlaceHolder);
                    $changedHomeTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedHomeTeamPlaceHolder));
                    $changedHomeTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedHomeTeamPlaceHolder[0]));
                    $changedHomeTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedHomeTeamPlaceHolder[1]));

                    $displayHomeTeamPlaceHolderName = $changedHomeTeamPlaceHolder[0].'.'.$changedHomeTeamPlaceHolder[1];

                    if ($isWinnerOrLooser == '_WR') {
                        $splittedMatchNumber[3] = 'wrs.(@HOME';
                        $bracketStarted = true;
                    } elseif ($isWinnerOrLooser == '_LR') {
                        $splittedMatchNumber[3] = 'lrs.(@HOME';
                        $bracketStarted = true;
                    }
                }

                if (strpos($awayTeamPlaceHolder, '(') === false) {
                    if ((strpos($awayTeamPlaceHolder, '_WR') !== false || strpos($awayTeamPlaceHolder, '_LR') !== false)) {

                        $isWinnerOrLooser = null;
                        if ((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                            $isWinnerOrLooser = '_WR';
                        }
                        if ((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                            $isWinnerOrLooser = '_LR';
                        }

                        // Get away placeholder
                        $searchAwayTeamPlaceHolder = str_replace('_', '-', str_replace($isWinnerOrLooser, '', $awayTeamPlaceHolder));
                        $searchAwayTeamMatchNumber = array_filter($allTemplateMatchNumber, function ($value) use ($searchAwayTeamPlaceHolder) {
                            if (strpos($value, $searchAwayTeamPlaceHolder) !== false) {
                                return $value;
                            }
                        });
                        if (count($searchAwayTeamMatchNumber) == 1) {
                            $splittedSearchAwayTeamMatchNumber = explode('.', array_values($searchAwayTeamMatchNumber)[0]);
                            $splittedSearchAwayTeamMatchNumber[1] = str_replace('RR', '', str_replace('PM', '', $splittedSearchAwayTeamMatchNumber[1]));
                            $splittedSearchAwayTeamMatchNumber[2] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $splittedSearchAwayTeamMatchNumber[2]));
                            $displayAwayTeamPlaceHolderName = $splittedSearchAwayTeamMatchNumber[1].'.'.$splittedSearchAwayTeamMatchNumber[2];

                            if ($bracketStarted) {
                                $splittedMatchNumber[3] .= '-@AWAY)';
                            } else {
                                if ($isWinnerOrLooser == '_WR') {
                                    $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                                } elseif ($isWinnerOrLooser == '_LR') {
                                    $splittedMatchNumber[3] .= '-lrs.(@AWAY)';
                                }
                            }
                        } else {
                            echo 'issue found'.$awayTeamPlaceHolder;
                        }
                    } else {
                        $displayAwayTeamPlaceHolderName = '#'.$homeAwayTeamPlaceHolder[1];

                        if ($bracketStarted) {
                            $splittedMatchNumber[3] .= ')-@AWAY';
                        } else {
                            $splittedMatchNumber[3] .= '-@AWAY';
                        }
                    }
                } else {
                    $isWinnerOrLooser = null;
                    if ((strpos($awayTeamPlaceHolder, '_WR') !== false)) {
                        $isWinnerOrLooser = '_WR';
                    }
                    if ((strpos($awayTeamPlaceHolder, '_LR') !== false)) {
                        $isWinnerOrLooser = '_LR';
                    }

                    $changedAwayTeamPlaceHolder = str_replace('(', '', $awayTeamPlaceHolder);
                    $changedAwayTeamPlaceHolder = str_replace(')', '', $changedAwayTeamPlaceHolder);
                    $changedAwayTeamPlaceHolder = explode('_', str_replace($isWinnerOrLooser, '', $changedAwayTeamPlaceHolder));
                    $changedAwayTeamPlaceHolder[0] = str_replace('RR', '', str_replace('PM', '', $changedAwayTeamPlaceHolder[0]));
                    $changedAwayTeamPlaceHolder[1] = preg_replace('/^0+/', '', preg_replace('/^G+/', '', $changedAwayTeamPlaceHolder[1]));

                    $displayAwayTeamPlaceHolderName = $changedAwayTeamPlaceHolder[0].'.'.$changedAwayTeamPlaceHolder[1];

                    if ($bracketStarted) {
                        $splittedMatchNumber[3] .= '-@AWAY)';
                    } else {
                        if ($isWinnerOrLooser == '_WR') {
                            $splittedMatchNumber[3] .= '-wrs.(@AWAY)';
                        } elseif ($isWinnerOrLooser == '_LR') {
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
        foreach ($files as $file) {
            $allTemplateMatchNumber = [];
            $filePath = (string) $file;
            $updatedFilePath = str_replace('templates/', 'updatedtemplates/', $filePath);
            $json = json_decode(file_get_contents($filePath), true);
            $updatedJson = $json;

            $allRounds = $json['tournament_competation_format']['format_name'];
            $allUpdatedRounds = $allRounds;
            $lastRound = $allRounds[count($allRounds) - 1];
            $lastMatchType = $lastRound['match_type'][count($lastRound['match_type']) - 1];

            $matchTypeName = $lastMatchType['name'];
            if (isset($lastMatchType['actual_name'])) {
                $matchTypeName = $lastMatchType['actual_name'];
            }
            $isPlacingMatch = strpos($matchTypeName, 'PM');

            if ($isPlacingMatch !== false) {
                echo $file.'<br/>';
                $matches = $lastMatchType['groups']['match'];
                $position = 1;
                foreach ($matches as $matchKey => $match) {
                    $updatedMatchDetail = $match;
                    $updatedMatchDetail['position'] = ($position++).'-'.($position++);
                    $allUpdatedRounds[count($allRounds) - 1]['match_type'][count($lastRound['match_type']) - 1]['groups']['match'][$matchKey] = $updatedMatchDetail;
                }
            }

            $updatedJson['tournament_competation_format']['format_name'] = $allUpdatedRounds;

            Storage::put($updatedFilePath, json_encode($updatedJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        echo 'All templates processed.';
        exit;
    }

    /**
     * Check for competition end.
     *
     * @param  int  $competitionId
     * @return bool
     */
    private function checkForCompetitionEnd($competitionId)
    {
        $matches = DB::table('temp_fixtures')
            ->where('competition_id', $competitionId)
            ->whereRaw(Db::raw('(hometeam_score IS NULL OR awayteam_score IS NULL)'));

        if ($matches->exists()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Update competition positions
     *
     * @param  int  $competitionId
     * @param  int  $ageCategoryId
     */
    public function updateCategoryPositions($competitionId, $ageCategoryId)
    {
        $ageCategory = TournamentCompetationTemplates::find($ageCategoryId);

        if ($ageCategory->tournament_template_id == null && $ageCategory->template_json_data != null) {
            $tournamentTemplate = json_decode($ageCategory->template_json_data, true);
        } else {
            $tournamentTemplate = json_decode($ageCategory->TournamentTemplate->json_data, true);
        }

        if ($tournamentTemplate['position_type'] == 'final' || $tournamentTemplate['position_type'] == 'final_and_group_ranking') {
            $matchPositions = Position::where('age_category_id', $ageCategoryId)->where('dependent_type', 'match')->get();
            $this->updatePlacingMatchPositions($ageCategory, $matchPositions);
        }
        if ($tournamentTemplate['position_type'] == 'final_and_group_ranking' || $tournamentTemplate['position_type'] == 'group_ranking') {
            $rankingPositions = Position::where('age_category_id', $ageCategoryId)->where('dependent_type', 'ranking')->get();
            $this->updateGroupRankingPositions($ageCategory, $rankingPositions);
        }
    }

    /**
     * Update placing match positions
     *
     * @param  object  $ageCategory
     */
    public function updatePlacingMatchPositions($ageCategory, $positions)
    {
        $prefixMatchName = $ageCategory->group_name.'-'.$ageCategory->category_age.'-';
        for ($i = 0; $i < count($positions); $i++) {
            $matchNumber = str_replace('CAT.', $prefixMatchName, $positions[$i]->match_number);
            $fixture = DB::table('temp_fixtures')->where('match_number', $matchNumber)->where('age_group_id', $ageCategory->id)->get()->first();

            $winner = null;
            $looser = null;
            if ($fixture && $fixture->is_result_override == 1 && $fixture->match_status == 'Penalties') {
                $winner = $fixture->home_team;
                $looser = $fixture->away_team;
                if ($fixture->match_winner == $fixture->away_team) {
                    $winner = $fixture->away_team;
                    $looser = $fixture->home_team;
                }
            } elseif ($fixture && $fixture->hometeam_score !== null && $fixture->awayteam_score !== null) {
                if ($fixture->hometeam_score >= $fixture->awayteam_score) {
                    $winner = $fixture->home_team != 0 ? $fixture->home_team : null;
                    $looser = $fixture->away_team != 0 ? $fixture->away_team : null;
                } else {
                    $winner = $fixture->away_team != 0 ? $fixture->away_team : null;
                    $looser = $fixture->home_team != 0 ? $fixture->home_team : null;
                }
            }

            if ($winner !== null && $looser !== null) {
                if ($positions[$i]->result_type === 'winner') {
                    // Update winner team
                    $positions[$i]->team_id = $winner;
                    $positions[$i]->save();
                }

                if ($positions[$i]->result_type === 'loser') {
                    $positions[$i]->team_id = $looser;
                    $positions[$i]->save();
                }
            } else {
                $positions[$i]->team_id = null;
                $positions[$i]->save();
            }
        }
    }

    /**
     * Update group ranking positions
     *
     * @param  object  $ageCategory
     */
    public function updateGroupRankingPositions($ageCategory, $positions)
    {
        $positionCalculatingGroups = $this->getPositionCalculatingGroups($ageCategory, $positions);
        $competitionIds = $positionCalculatingGroups['competitionIds'];
        $groups = $positionCalculatingGroups['groups'];
        if (count($competitionIds) != count($groups)) {
            return false;
        }
        $data['tournamentId'] = $ageCategory->tournament_id;
        $standingResData = [];
        // $competitionEndFlag = 0;

        for ($i = 0; $i < count($competitionIds); $i++) {
            $competitionId = $competitionIds[$i];
            $standingResData[$groups[$i]] = [];
            if ($this->checkForCompetitionEnd($competitionId)) {
                // $competitionEndFlag = 1;
                $data['competitionId'] = $competitionId;
                $tournamentData['tournamentData'] = $data;
                $standingResData[$groups[$i]] = $this->getStanding(collect($tournamentData), 'yes')['data']->toArray();
            }
        }

        // if($competitionEndFlag == 1) {
        foreach ($positions as $position) {
            $ranking = $position->ranking;
            $group = substr($ranking, -1);
            $rankingNumber = intval(substr($ranking, 0, strlen($ranking) - 1));
            $standing = isset($standingResData[$group][$rankingNumber - 1]) ? ((array) $standingResData[$group][$rankingNumber - 1]) : [];
            if (isset($standing['id']) && $standing['id'] != null) {
                $position->team_id = $standing['id'];
                $position->save();
            } else {
                $position->team_id = null;
                $position->save();
            }
        }
        // }
    }

    /**
     * Update group ranking positions
     *
     * @param  object  $ageCategory
     */
    public function getPositionCalculatingGroups($ageCategory, $positions)
    {
        $ageCategoryPrefix = $ageCategory->group_name.'-'.$ageCategory->category_age.'-';
        $groupNames = [];
        $groups = [];

        foreach ($positions as $position) {
            $group = substr($position->ranking, -1);
            if (! in_array($group, $groups)) {
                $groups[] = $group;
                $groupName = $ageCategoryPrefix.'Group-'.$group;
                $groupNames[] = $groupName;
            }
        }

        $competitionIds = Competition::where('tournament_competation_template_id', $ageCategory->id)->whereIn('name', $groupNames)->pluck('id')->toArray();

        return ['groups' => $groups, 'competitionIds' => $competitionIds];
    }

    /**
     * Update competition stndings
     *
     * @param  array  $data
     */
    public function refreshCompetitionStandings($data)
    {
        $standingCount = DB::table('match_standing')
            ->where('tournament_id', '=', $data['tournamentId'])
            ->where('competition_id', '=', $data['competitionId'])->count();

        $competition = Competition::find($data['competitionId']);
        $ageCategoryId = $competition->tournament_competation_template_id;
        $groupFixture = DB::table('temp_fixtures')->select('temp_fixtures.*')->where('tournament_id', '=', $data['tournamentId'])->where('competition_id', $data['competitionId'])->get();
        if ($competition->actual_competition_type === 'Round Robin') {
            $findTeams = [];
            foreach ($groupFixture as $key => $value) {
                if ($value->home_team == 0 || $value->away_team == 0) {
                    continue;
                }
                $findTeams[] = $value->home_team;
                $findTeams[] = $value->away_team;
            }
            $findTeams = array_unique($findTeams);
            $this->moveMatchStandings($data['tournamentId'], $ageCategoryId, $data['competitionId']);
            $this->generateStandingsForCompetitions($data['tournamentId'], $data['competitionId'], $ageCategoryId, $findTeams, $competition->competation_type);
            $this->updateCategoryPositions($data['competitionId'], $ageCategoryId);
        } else {
            foreach ($groupFixture as $key => $value) {
                $this->calculateCupLeagueTable($value);
            }
        }
    }

    public function matchUnscheduledFixtures($matchData)
    {
        $areAllMatchFixtureUnScheduled = false;
        $result = $this->matchRepoObj->matchUnscheduledFixtures($matchData);
        if (count($result['conflictedFixtureMatchNumber']) === 0) {
            $areAllMatchFixtureUnScheduled = true;
        }

        return ['status_code' => '200', 'data' => $result, 'message' => 'Match has been unscheduled successfully', 'conflictedFixturesArray' => $result['conflictedFixtureMatchNumber'], 'areAllMatchFixtureUnScheduled' => $areAllMatchFixtureUnScheduled];
    }

    public function unscheduleFixturesByAgeCategory($matchData)
    {
        $result = $this->matchRepoObj->unscheduleFixturesByAgeCategory($matchData);

        return ['status_code' => '200', 'data' => $result, 'message' => 'Unscheduled successfully'];
    }

    public function getAgeCategoriesToUnscheduleFixtures($matchData)
    {

        $result = $this->matchRepoObj->getAgeCategoriesToUnscheduleFixtures($matchData);

        if ($result) {
            return ['status_code' => '200', 'data' => $result, 'message' => ''];
        }
    }

    public function unscheduleAllFixtures($tournamentId)
    {
        $competitionIds = $this->matchRepoObj->unscheduleAllFixtures($tournamentId, true);
        foreach ($competitionIds as $ageGroupId => $cids) {
            $allCompetitionsIds = array_unique($cids);
            sort($allCompetitionsIds);
            foreach ($allCompetitionsIds as $id) {
                $data = ['tournamentId' => $tournamentId['tournamentId'], 'competitionId' => $id];
                $this->refreshCompetitionStandings($data);
            }
        }

        return ['status_code' => '200', 'message' => 'All matches have been unscheduled successfully'];
    }

    public function processFixtures($fixtures)
    {
        $fixtures = array_unique($fixtures);
        if (count($fixtures) > 0) {
            $allFixtures = DB::table('temp_fixtures')->whereIn('id', $fixtures)->get();
            foreach ($allFixtures as $fixture) {
                $this->calculateCupLeagueTable($fixture, false);
            }
        }
    }

    public function saveScheduleMatches($scheduleMatches)
    {
        $matchFixturesStatusArray = [];
        $areAllMatchFixtureScheduled = false;
        $ageCategories = [];
        $tournamentId = '';
        foreach ($scheduleMatches as $scheduleMatch) {
            $scheduledResult = $this->matchRepoObj->setMatchSchedule(['isMultiSchedule' => true, 'matchData' => ['ageGroupId' => $scheduleMatch['ageGroupId'], 'matchEndDate' => $scheduleMatch['matchEndDate'], 'matchId' => $scheduleMatch['matchId'], 'matchStartDate' => $scheduleMatch['matchStartDate'], 'pitchId' => $scheduleMatch['pitchId'], 'scheduleLastUpdateDateTime' => $scheduleMatch['scheduleLastUpdateDateTime'], 'tournamentId' => $scheduleMatch['tournamentId']], 'scheduleMatchesArray' => []]);
            if ($scheduledResult == -1) {
                return ['status_code' => '200', 'data' => $scheduledResult, 'message' => 'One or both teams are scheduled for a team interval.', 'conflictedFixturesArray' => [], 'areAllMatchFixtureScheduled' => false];
            }

            $scheduleMatch['venue_id'] = Pitch::find($scheduleMatch['pitchId'])->venue_id;
            $data = $this->matchRepoObj->saveScheduleMatches($scheduleMatch);

            if ($data['status'] == false) {
                $matchFixturesStatusArray[] = $data['match_data']->match_number;
            }

            $ageCategories[] = $scheduleMatch['ageGroupId'];
            $tournamentId = $scheduleMatch['tournamentId'];
        }

        if (count($matchFixturesStatusArray) === 0) {
            $areAllMatchFixtureScheduled = true;
        }

        foreach ($ageCategories as $ageCategoryId) {
            $matchData = ['tournamentId' => $tournamentId, 'ageGroupId' => $ageCategoryId];
            $this->matchRepoObj->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);
            $this->matchRepoObj->checkMaximumTeamIntervalForMatchesOnCategoryUpdate($matchData);
        }

        return ['status_code' => '200', 'message' => 'Match has been scheduled successfully', 'conflictedFixturesArray' => $matchFixturesStatusArray, 'areAllMatchFixtureScheduled' => $areAllMatchFixtureScheduled];
    }

    public function getScheduledMatch($data)
    {
        return $this->matchRepoObj->getScheduledMatch($data);
    }

    public function moveMatchStandings($tournamentId, $ageCategoryId, $competitionId)
    {
        // Manual standing insert - start
        $allCompetitions = Competition::where('tournament_id', '=', $tournamentId)->where('tournament_competation_template_id', '=', $ageCategoryId)->where('id', '>', $competitionId)->get();

        foreach ($allCompetitions as $competition) {
            if ($competition->is_manual_override_standing == 1) {
                $allCompetitionStandings = DB::table('match_standing')->where('tournament_id', '=', $tournamentId)->where('competition_id', '=', $competition->id)->get();

                foreach ($allCompetitionStandings as $standing) {
                    $teamManualRanking = TeamManualRanking::where('tournament_id', '=', $standing->tournament_id)->where('competition_id', '=', $standing->competition_id)->where('team_id', '=', $standing->team_id)->first();

                    if ($teamManualRanking) {
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
            ->where('match_standing.tournament_id', '=', $tournamentId)
            ->where('match_standing.competition_id', '>', $competitionId)
            ->where('competitions.tournament_competation_template_id', '=', $ageCategoryId)->delete();
    }

    public function getPositionWiseStandingsForAllGroupsOfRound($ageCategoryId, $tournamentId, $competitionType)
    {
        $standingResData = [];
        $standingsPositionWise = [];
        $competitions = Competition::where('tournament_competation_template_id', $ageCategoryId)->where('competation_round_no', 'Round 1')->get();
        $areAllCompetitionEnded = true;
        foreach ($competitions as $competition) {
            if (! $this->checkForCompetitionEnd($competition->id)) {
                $areAllCompetitionEnded = false;
                break;
            }
        }
        if ($areAllCompetitionEnded) {
            foreach ($competitions as $competition) {
                $data['tournamentId'] = $competition->tournament_id;
                $data['competitionId'] = $competition->id;
                $tournamentData['tournamentData'] = $data;
                $standingResData[$competition->id] = $this->getStanding(collect($tournamentData), 'yes')['data']->toArray();
            }
            $pointsTeamWise = [];
            $gdTeamWise = [];
            $gfTeamWise = [];

            foreach ($standingResData as $competitionId => $standings) {
                $index = 1;
                foreach ($standings as $standing) {
                    $standing = (array) $standing;
                    $standingsPositionWise[$index][$standing['team_id']] = $standing;
                    $pointsTeamWise[$index][$standing['team_id']] = (int) $standing['points'];
                    $gdTeamWise[$index][$standing['team_id']] = (int) $standing['GoalDifference'];
                    $gfTeamWise[$index][$standing['team_id']] = (int) $standing['goal_for'];
                    $index++;
                }
            }

            foreach ($standingsPositionWise as $position => $teamStandings) {
                $standings = $teamStandings;
                $params = [];
                //if ($competitionType != 'knockout') {
                $params[] = $pointsTeamWise[$position];
                $params[] = SORT_DESC;
                $params[] = $gdTeamWise[$position];
                $params[] = SORT_DESC;
                $params[] = $gfTeamWise[$position];
                $params[] = SORT_DESC;
                $params[] = &$standings;
                array_multisort(...$params);
                //}
                $standingsPositionWise[$position] = array_values($standings);
            }

        } else {
            $allMatches = DB::table('temp_fixtures')
                ->select('temp_fixtures.id as matchID', 'temp_fixtures.match_number as MatchNumber',
                    'temp_fixtures.home_team_placeholder_name as HomeTeamPlaceHolderName', 'temp_fixtures.away_team_placeholder_name as AwayTeamPlaceHolderName')
                ->leftJoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
                ->leftJoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
                ->where('competitions.tournament_competation_template_id', '=', $ageCategoryId)
                ->where('competitions.competation_round_no', '=', 'Round 2')
                ->where('temp_fixtures.tournament_id', '=', $tournamentId)
                ->get();
            foreach ($allMatches as $match) {
                $updateMatchDetails = false;
                $matchNumber = explode('.', $match->MatchNumber);
                $homeAwayTeams = explode('-', $matchNumber[2]);
                $homeTeam = $homeAwayTeams[0];
                $awayTeam = $homeAwayTeams[1];
                if ($homeTeam && strpos($homeTeam, '#') > 0) {
                    DB::table('temp_fixtures')->where('id', $match->matchID)->update(
                        [
                            'home_team_name' => $match->HomeTeamPlaceHolderName,
                            'home_team' => 0,
                        ]
                    );
                }

                if ($awayTeam && strpos($awayTeam, '#') > 0) {
                    DB::table('temp_fixtures')->where('id', $match->matchID)->update(
                        [
                            'away_team_name' => $match->AwayTeamPlaceHolderName,
                            'away_team' => 0,
                        ]
                    );
                }
            }
        }

        return ['areAllCompetitionEnded' => $areAllCompetitionEnded, 'standingsPositionWise' => $standingsPositionWise];
    }

    public function updateRankingPositionInMatchForKnockout($team, $firstRoundPositionWiseStandings, $match, $homeAway)
    {
        $rankingPosition = explode('#', $team);
        $teamStandingDetail = $firstRoundPositionWiseStandings['standingsPositionWise'][$rankingPosition[1]][(int) $rankingPosition[0] - 1];

        $updateArray = [];

        if ($homeAway === 'home') {
            $updateArray = [
                'home_team_name' => $teamStandingDetail['name'],
                'home_team' => $teamStandingDetail['team_id'],
            ];
        }

        if ($homeAway === 'away') {
            $updateArray = [
                'away_team_name' => $teamStandingDetail['name'],
                'away_team' => $teamStandingDetail['team_id'],
            ];
        }

        DB::table('temp_fixtures')->where('id', $match->matchID)->update($updateArray);
    }

    public function checkBestRankingExistInSecondRound($ageGroupId, $temptournamentId)
    {
        $isBestRankingExist = false;
        $matches = DB::table('temp_fixtures')
            ->select('temp_fixtures.id as matchID', 'temp_fixtures.match_number as MatchNumber', 'temp_fixtures.home_team_name as HomeTeam', 'temp_fixtures.home_team as HomeTeamId', 'temp_fixtures.away_team_name as AwayTeam',
                'temp_fixtures.home_team_placeholder_name as HomeTeamPlaceHolderName', 'temp_fixtures.away_team_placeholder_name as AwayTeamPlaceHolderName',
                'temp_fixtures.away_team as AwayTeamId', 'competitions.competation_round_no as competition_round')
            ->leftJoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
            ->leftJoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
            ->leftJoin('tournament_template', 'tournament_template.id', '=', 'tournament_competation_template.tournament_template_id')
            ->where('competitions.tournament_competation_template_id', '=', $ageGroupId)
            ->where('temp_fixtures.tournament_id', '=', $temptournamentId)
            ->where('competitions.competation_round_no', '=', 'Round 2')
            ->get();

        if (count($matches) > 0) {
            foreach ($matches as $key => $match) {
                $exmatchNumber = explode('.', $match->MatchNumber);
                $value = explode('-', $exmatchNumber[2]);

                $homeTeam = $value[0];
                if ($homeTeam) {
                    if (strpos($homeTeam, '#') > 0) {
                        $isBestRankingExist = true;
                        break;
                    }
                }
                $awayTeam = $value[1];
                if ($awayTeam) {
                    if (strpos($awayTeam, '#') > 0) {
                        $isBestRankingExist = true;
                        break;
                    }
                }
            }
        }

        return $isBestRankingExist;
    }

    public function getTodaysMatchesOfAgeCategory($ageCategoryId)
    {
        $ageCategoryMatches = DB::table('temp_fixtures')
            ->where('temp_fixtures.age_group_id', $ageCategoryId)
            ->leftjoin('competitions', 'competitions.id', 'temp_fixtures.competition_id')
            ->leftjoin('venues', 'temp_fixtures.venue_id', 'venues.id')
            ->leftjoin('pitches', 'temp_fixtures.pitch_id', 'pitches.id')
            ->leftjoin('teams as home_team', function ($join) {
                $join->on('home_team.id', '=', 'temp_fixtures.home_team');
            })
            ->leftjoin('teams as away_team', function ($join) {
                $join->on('away_team.id', '=', 'temp_fixtures.away_team');
            })
                    // ->where( DB::raw("DATE(match_datetime) = '" . date('Y-m-d') . "'") )
            ->whereDate('match_datetime', date('Y-m-d'))
                    // ->whereDate('match_datetime', date('2020-05-06'))
            ->orderBy('match_datetime', 'ASC')
            ->select(
                'temp_fixtures.id as id',
                'temp_fixtures.match_datetime as match_datetime',
                'competitions.name as competition_name',
                'temp_fixtures.display_match_number as display_match_number',
                'temp_fixtures.position as position',
                'venues.name as venue_name',
                'venues.country as venue_country',
                'pitches.pitch_number as pitch_number',
                'competitions.actual_name as competition_actual_name',
                'temp_fixtures.display_home_team_placeholder_name as display_home_team_placeholder_name',
                'temp_fixtures.display_away_team_placeholder_name as display_away_team_placeholder_name',
                'home_team.name as home_team',
                'away_team.name as away_team',
                'home_team.shirt_color as home_team_shirt_color', 'away_team.shirt_color as away_team_shirt_color',
                'home_team.shorts_color as home_team_shorts_color', 'away_team.shorts_color as away_team_shorts_color',
                'temp_fixtures.home_team as home_id',
                'temp_fixtures.away_team as away_id',
                'temp_fixtures.hometeam_score as home_score',
                'temp_fixtures.awayteam_score as away_score',
                'temp_fixtures.is_result_override as is_result_override',
                'temp_fixtures.match_status as match_status'
            )
            ->get()
            ->toArray();

        $updatedAgeCategoryMatches = [];
        foreach ($ageCategoryMatches as $key => $res) {
            $res = (array) $res;
            $updatedAgeCategoryMatches[$key] = $res;
            if ($res['home_id'] == 0) {
                $preset = '';
                if (strpos($res['display_home_team_placeholder_name'], '.') != false) {
                    if (strpos($res['display_match_number'], 'wrs') != false) {
                        $preset = 'wrs.';
                    }
                    if (strpos($res['display_match_number'], 'lrs') != false) {
                        $preset = 'lrs.';
                    }
                }

                $updatedAgeCategoryMatches[$key]['display_home_team_placeholder_name'] = $preset.$res['display_home_team_placeholder_name'];

            }
            if ($res['away_id'] == 0) {
                $preset = '';

                if (strpos($res['display_away_team_placeholder_name'], '.') != false) {
                    if (strpos($res['display_match_number'], 'wrs') != false) {
                        $preset = 'wrs.';
                    }
                    if (strpos($res['display_match_number'], 'lrs') != false) {
                        $preset = 'lrs.';
                    }
                }

                $updatedAgeCategoryMatches[$key]['display_away_team_placeholder_name'] = $preset.$res['display_away_team_placeholder_name'];

            }
        }

        return $updatedAgeCategoryMatches;
    }

    public function getStandingsOfAgeCategory($ageCategoryId)
    {
        $competitions = Competition::where('tournament_competation_template_id', $ageCategoryId)->get();
        $leagueTables = [];

        foreach ($competitions as $competition) {
            if ($competition->actual_competition_type == 'Round Robin') {
                $tournamentData = ['tournamentData' => ['competitionId' => $competition->id, 'tournamentId' => $competition->tournament_id]];
                $result = $this->refreshStanding($tournamentData, 'yes');
                $leagueTables[$competition->id] = ['name' => $competition['name'], 'standings' => $result['data']->toArray()];
            }
        }

        return $leagueTables;
    }
}
