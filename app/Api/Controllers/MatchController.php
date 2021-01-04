<?php

namespace Laraspace\Api\Controllers;

// use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Pitch;
use Laraspace\Models\Tournament;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Http\Requests\Match\ScheduleRequest;
use Laraspace\Http\Requests\Match\GetDrawsRequest;
use Laraspace\Http\Requests\Match\SaveResultRequest;
use Laraspace\Http\Requests\Match\AllResultsRequest;
use Laraspace\Http\Requests\Match\MatchDetailRequest;
use Laraspace\Http\Requests\Match\ScoreUpdateRequest;
use Laraspace\Http\Requests\Match\GetFixturesRequest;
use Laraspace\Http\Requests\Match\GetStandingRequest;
use Laraspace\Http\Requests\Match\GetDrawTableRequest;
use Laraspace\Http\Requests\Match\AssignRefereeRequest;
use Laraspace\Http\Requests\Match\RefreshStandingRequest;
use Laraspace\Http\Requests\Match\CheckTeamIntervalRequest;
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;
use Laraspace\Http\Requests\Match\GetUnavailableBlockRequest;
use Laraspace\Http\Requests\Match\RemoveAssignedRefereeRequest;
use Laraspace\Http\Requests\Match\SaveStandingsManuallyRequest;
use Laraspace\Http\Requests\Match\GetSignedUrlForMatchPrintRequest;
use Laraspace\Http\Requests\Match\GetSignedUrlForMatchReportRequest;
use Laraspace\Http\Requests\Match\GetSignedUrlForRefereeReportRequest;
use Laraspace\Models\Referee;
use Laraspace\Http\Requests\Match\UnscheduleMatchRequest;
use File;
use Storage;
use DB;
use PDF;
use UrlSigner;
// Need to Define Only Contracts
use JWTAuth;
use Carbon\Carbon;
use Laraspace\Http\Requests\Match\GetSignedUrlForTeamsSpreadsheetSampleDownloadRequest;

/**
 * Matches Resource Description.
 *
 * @Resource("Matches")
 *
 * @Author Kparikh@aecordigital.com
 */
class MatchController extends BaseController
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Matches.
     *
     * @Get("/matches")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getMatches()
    {

        return $this->matchObj->getAllMatches();
    }

    /**
     * Create New Match Result.
     *
     * @Post("/match/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createMatch(Request $request)
    {
        return $this->matchObj->createMatch($request);
    }

    /**
     * Edit  Match result.
     *
     * @Post("/match/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->matchObj->edit($request);
    }

    public function deleteMatch($deleteId)
    {
        return $this->matchObj->deleteMatch($deleteId);
    }
    public function getDraws(GetDrawsRequest $request){
        $client = new HttpClient();
        $draws = $client->post('/match/getDraws', [], $request->all());
        return $draws;
    }
    public function getFixtures(GetFixturesRequest $request){
        $client = new HttpClient();
        $fixtures = $client->post('/match/getFixtures', [], $request->all());
        return $fixtures;
    }
    public function getStanding(GetStandingRequest $request, $refreshStanding = null){
        return $this->matchObj->getStanding($request, $refreshStanding);
    }
    public function getDrawTable(GetDrawTableRequest $request) {
        $client = new HttpClient();
        $drawTable = $client->post('/match/getDrawTable', [], $request->all());
        return $drawTable;
    }
    public function checkTeamIntervalforMatches(CheckTeamIntervalRequest $request) {
        return $this->matchObj->checkTeamIntervalforMatches($request);
    }

    public function getAllScheduledMatch(Request $request) {
        return $this->matchObj->getAllScheduledMatch($request);
    }
    public function getMatchDetail(MatchDetailRequest $request)
    {
        return $this->matchObj->getMatchDetail($request);
    }
    public function generateMatchPrint(Request $request)
    {
        return $this->matchObj->generateMatchPrint($request->all());
    }

    public function generateCategoryReport(Request $request, $ageGroupId, $tournamentId) {
        return $this->matchObj->generateCategoryReport($ageGroupId, $tournamentId);
    }

    public function removeAssignedReferee(RemoveAssignedRefereeRequest $request)
    {
        return $this->matchObj->removeAssignedReferee($request);
    }
    public function assignReferee(AssignRefereeRequest $request)
    {
        return $this->matchObj->assignReferee($request);
    }
    public function saveResult(SaveResultRequest $request)
    {
        return $this->matchObj->saveResult($request);
    }
    public function saveAllResults(AllResultsRequest $request)
    {
        return $this->matchObj->saveAllResults($request);
    }
    public function saveUnavailableBlock(Request $request)
    {
        return $this->matchObj->saveUnavailableBlock($request);
    }
    public function getUnavailableBlock(GetUnavailableBlockRequest $request)
    {
        return $this->matchObj->getUnavailableBlock($request);
    }
    public function removeBlock($blockId)
    {
        return $this->matchObj->removeBlock($blockId);
    }
    public function updateScore(ScoreUpdateRequest $request)
    {
        return $this->matchObj->updateScore($request);
    }
    public function refreshStanding(RefreshStandingRequest $request)
    {
        $client = new HttpClient();
        $standing = $client->post('/match/refreshStanding', [], $request->all());
        return $standing;
    }

    public function generateRefereeReportCard(Request $request, $refereeId){

        $refereeData = Referee::where('id',$refereeId)->first();

        $referee = TempFixture::with('pitch','referee')->where('referee_id', $refereeId)
                                ->orderBy('match_datetime','asc')->get();

        $resultData = $referee->toArray();

        $date = new \DateTime(date('H:i d M Y'));

        $refereeName = $refereeData['last_name'].", ".$refereeData['first_name'];

        $refereeReportPdf =  "Referee report card - " .$refereeName;

        $pdf = PDF::loadView('pitchplanner.referee_report_card',['resultData' => $resultData])
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOrientation('portrait')
            ->setOption('footer-right', 'Page [page] of [toPage]')
            ->setOption('header-right', $date->format('H:i d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);
        return $pdf->inline($refereeReportPdf);

    }

    public function automateMatchScheduleAndResult(Request $request, $tournamentId = null, $ageGroupId = null)
    {
        if(config('config-variables.is_automate_match_schedule_enabled') == false) {
            abort(404);
        }
        $status = 'success';
        try {
            if($tournamentId === null) {
                $publishedTournaments = Tournament::where('status', 'Published')->get();
                $unpublishedTournaments = Tournament::where('status', 'Unpublished')->get();
                return view('automate_tournament.tournaments', compact('publishedTournaments', 'unpublishedTournaments'));
            }

            if($ageGroupId === null) {
                $tournamentCompetationTemplates = TournamentCompetationTemplates::where('tournament_id', $tournamentId)->get();

                return view('automate_tournament.competetions', compact('tournamentCompetationTemplates'));
            }

            $tournament = Tournament::find($tournamentId);

            $tournamentCompetationTemplate = TournamentCompetationTemplates::find($ageGroupId);

            if(!$tournament) {                
                $status = 'error';
            }

            if(!$tournamentCompetationTemplate) {
                $status = 'error';
            }

            $matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
            $matchServiceObj = new \Laraspace\Api\Services\MatchService();
            $pitchRepoObj = new \Laraspace\Api\Repositories\PitchRepository();

            TempFixture::where('tournament_id', '=', $tournamentId)->update(['is_scheduled' => 0, 'pitch_id' => null, 'hometeam_score' => null, 'awayteam_score' => null, 'minimum_team_interval_flag' => 0, 'maximum_team_interval_flag' => 0]);

            $tournamentFixtures = DB::table('temp_fixtures')
                                        ->where('age_group_id', '=', $ageGroupId)
                                        ->where('tournament_id', '=', $tournamentId)
                                        ->get();

            $pitch = $pitchRepoObj->getAllPitches($tournamentId)->first();
            $pitchAvailability = $pitch->pitchAvailability;
            $pitchAvailabilityIndex = 0;
            $stageMinutes = 0;
            $allCompetitions = [];

            foreach($tournamentFixtures as $fixture) {
                $stageStartDate = $pitchAvailability[$pitchAvailabilityIndex]->stage_start_date;
                $stageStartTime = $pitchAvailability[$pitchAvailabilityIndex]->stage_start_time;
                $stageEndDate = $pitchAvailability[$pitchAvailabilityIndex]->stage_end_date;
                $stageEndTime = $pitchAvailability[$pitchAvailabilityIndex]->stage_end_time;
                $startDate = Carbon::createFromFormat('d/m/Y H:i', $stageStartDate . ' ' . $stageStartTime);
                $endDate = Carbon::createFromFormat('d/m/Y H:i', $stageEndDate . ' ' . $stageEndTime);
                $diffInMinutes = $startDate->diffInMinutes($endDate);

                if( ($stageMinutes + 30) <= $diffInMinutes) {
                    $matchStartDate = $startDate->copy()->addMinutes($stageMinutes)->toDateTimeString();
                    $stageMinutes += 30;
                    $matchEndDate = $startDate->copy()->addMinutes($stageMinutes)->toDateTimeString();
                } else {
                    if(isset($pitchAvailability[$pitchAvailabilityIndex + 1])) {
                        $pitchAvailabilityIndex++;

                        $stageStartDate = $pitchAvailability[$pitchAvailabilityIndex]->stage_start_date;
                        $stageStartTime = $pitchAvailability[$pitchAvailabilityIndex]->stage_start_time;
                        $stageEndDate = $pitchAvailability[$pitchAvailabilityIndex]->stage_end_date;
                        $stageEndTime = $pitchAvailability[$pitchAvailabilityIndex]->stage_end_time;
                        $startDate = Carbon::createFromFormat('d/m/Y H:i', $stageStartDate . ' ' . $stageStartTime);
                        $endDate = Carbon::createFromFormat('d/m/Y H:i', $stageEndDate . ' ' . $stageEndTime);
                        $diffInMinutes = $startDate->diffInMinutes($endDate);

                        $stageMinutes = 0;
                        $matchStartDate = $startDate->copy()->addMinutes($stageMinutes)->toDateTimeString();
                        $stageMinutes += 30;
                        $matchEndDate = $startDate->copy()->addMinutes($stageMinutes)->toDateTimeString();
                    }
                }

                $matchData = [
                    'matchEndDate' => $matchEndDate,
                    'matchId' => $fixture->id,
                    'matchStartDate' => $matchStartDate,
                    'pitchId' => $pitch->id,
                    'tournamentId' => $tournamentId,
                ];

                $matchRepoObj->setAutomaticMatchSchedule($matchData, true);

                $awayTeamScore = rand(1,20);
                $homeTeamScore = rand(1,20);
                while($homeTeamScore == $awayTeamScore) {
                    $homeTeamScore = rand(1,20);
                }

                $matchData = [
                    'awayTeamScore' => $awayTeamScore,
                    'comments' => NULL,
                    'homeTeamScore' => $homeTeamScore,
                    'matchId' => $fixture->id,
                    'matchStatus' => NULL,
                    'matchWinner' => NULL,
                    'is_result_override' => 0,
                    'refereeId' => NULL,
                    'home_yellow_cards' => NULL,
                    'away_yellow_cards' => NULL,
                    'home_red_cards' => NULL,
                    'away_red_cards' => NULL,
                    'age_category_color' => $tournamentCompetationTemplate->category_age_color,
                    'group_color' => NULL,
                ];

                $matchResult = $matchRepoObj->saveResult($matchData);
                $allCompetitions[] = $fixture->competition_id;
                $competationId = $matchServiceObj->calculateCupLeagueTable($fixture);
            }
            $allCompetitions = array_unique($allCompetitions);
            sort($allCompetitions);
            foreach ($allCompetitions as $id) {
                $data = ['tournamentId' => $tournamentId, 'competitionId' => $id];
                $this->matchObj->refreshCompetitionStandings($data);
            }
        } catch(\Exception $e) {
            $status = 'error';
        }

        return view('automate_tournament.success_match_scheduled', compact('status') );
    }

    public function generateDisplayMatchNumber(Request $request)
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

            foreach($allRounds as $round) {
                foreach($round['match_type'] as $matchType) {
                    $allTemplateMatchNumber = array_merge($allTemplateMatchNumber, $matchType['groups']['match']);
                    if(isset($matchType['dependent_groups'])) {
                        foreach($matchType['dependent_groups'] as $dependentMatches) {
                            $allTemplateMatchNumber = array_merge($allTemplateMatchNumber, $dependentMatches['groups']['match']);
                        }
                    }
                }
            }
            $allTemplateMatchNumber = array_column($allTemplateMatchNumber, 'match_number');
            $allUpdatedRounds = [];
            $updatedRoundInfo = null;

            foreach($allRounds as $round) {
                $updatedRoundInfo = $round;
                $roundName = $round['name'];
                $matchTypes = $round['match_type'];

                foreach($matchTypes as $matchTypeKey=>$matchType) {
                    $matches = $matchType['groups']['match'];

                    $data = [];
                    $data['roundName'] = $roundName;
                    $data['allTemplateMatchNumber'] = $allTemplateMatchNumber;

                    foreach($matches as $matchKey=>$match) {
                        $updatedMatchDetail = $this->matchObj->processMatch($data, $match);
                        $updatedRoundInfo['match_type'][$matchTypeKey]['groups']['match'][$matchKey] = $updatedMatchDetail;
                    }

                    if(isset($matchType['dependent_groups'])) {
                        foreach($matchType['dependent_groups'] as $dependentKey => $dependentMatches) {
                            $matches = $dependentMatches['groups']['match'];
                            foreach($matches as $matchKey=>$match) {
                                $dependentData = array_merge($data, ['notToAllowRoundOne' => true]);
                                $updatedMatchDetail = $this->matchObj->processMatch($dependentData, $match);
                                $updatedRoundInfo['match_type'][$matchTypeKey]['dependent_groups'][$dependentKey]['groups']['match'][$matchKey] = $updatedMatchDetail;
                            }
                        }
                    }
                }

                array_push($allUpdatedRounds,$updatedRoundInfo);
            }

            $updatedJson['tournament_competation_format']['format_name'] = $allUpdatedRounds;
            Storage::put($updatedFilePath, json_encode($updatedJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        echo "All templates processed.";exit;
    }

    public function generateDisplayMatchNumberForDB(Request $request)
    {
        $tournamentCompetationTemplates = TournamentCompetationTemplates::all();

        foreach($tournamentCompetationTemplates as $tournamentCompetationTemplate) {
            $tempFixtures = TempFixture::with('competition', 'categoryAge')
                            ->where('temp_fixtures.age_group_id', $tournamentCompetationTemplate->id)
                            ->get();

            $allTemplateMatchNumber = [];
            foreach($tempFixtures as $fixture) {
                $category = $fixture->categoryAge->group_name . '-' . $fixture->categoryAge->category_age . '-';
                $allTemplateMatchNumber[] = str_replace($category, 'CAT.', $fixture->match_number);
            }

            foreach($tempFixtures as $fixture) {
                $category = $fixture->categoryAge->group_name . '-' . $fixture->categoryAge->category_age . '-';

                $data = [];
                $data['roundName'] = $fixture->competition->competation_round_no;
                $data['allTemplateMatchNumber'] = $allTemplateMatchNumber;

                $match = [];
                $match['match_number'] = str_replace($category, 'CAT.', $fixture->match_number);

                $updatedMatchDetail = $this->matchObj->processMatch($data, $match);

                $fixture->display_match_number = $updatedMatchDetail['display_match_number'];
                $fixture->display_home_team_placeholder_name = $updatedMatchDetail['display_home_team_placeholder_name'];
                $fixture->display_away_team_placeholder_name = $updatedMatchDetail['display_away_team_placeholder_name'];
                $fixture->save();
            }
        }
        echo "All DB templates processed.";exit;
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
                        $splittedMatchNumber[3] .= '-@AWAY';
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

    public function insertPositionsForPlacingMatches(Request $request)
    {
        return $this->matchObj->insertPositionsForPlacingMatches($request);
    }


    public function saveStandingsManually(SaveStandingsManuallyRequest $request)
    {
        return $this->matchObj->saveStandingsManually($request);
    }

    public function getSignedUrlForMatchReport(GetSignedUrlForMatchReportRequest $request)
    {
        $requestData = $request->all();
        $signedUrl = UrlSigner::sign(secure_url('api/match/report/generate/' . $requestData['ageCategoryData']['ageCategory'].'/'.$requestData['ageCategoryData']['tournament_id']), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));
        
        return $signedUrl;
    }

    public function getSignedUrlForMatchPrint(GetSignedUrlForMatchPrintRequest $request)
    {
        $reportData = $request->all();
        ksort($reportData);
        $reportData  = http_build_query($reportData);
        
        $signedUrl = UrlSigner::sign(url('api/match/print?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function getSignedUrlForRefereeReport(GetSignedUrlForRefereeReportRequest $request, $refereeId)
    {
        $signedUrl = UrlSigner::sign(url('api/match/reportCard/' . $refereeId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function getRefreeAgecategoryName(Request $request, $refereeId) {
        $refereeData = Referee::where('id',$refereeId)->each(function ($item, $key) {
        $ageGroupId = $item->age_group_id;
        $ageGroupIdArray = explode(',', $ageGroupId);
       
        foreach ($ageGroupIdArray as $ageGroupId) {
            $categoryName = TournamentCompetationTemplates::where('id',$ageGroupId)->pluck('category_age')->toArray();
        }

        });
    }

    /**
     * Get signed url for team spreadsheet download
     */
    public function getSignedUrlForTeamsSpreadsheetSampleDownload(GetSignedUrlForTeamsSpreadsheetSampleDownloadRequest $request)
    {
        $signedUrl = UrlSigner::sign(secure_url('api/match/downloadSampleUploadSheet'), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    /**
     * Download sample upload sheet
     */
    public function downloadSampleUploadSheet(Request $request)
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename='TeamsUploadSpreadsheet.xls'"
        ];

        return response()->download(base_path('resources/sample_uploads/TeamsUploadSpreadsheet.xls'), 'TeamsUploadSpreadsheet.xls', $headers);
    }

    public function getScheduledMatch(Request $request) 
    {
        return $this->matchObj->getScheduledMatch($request->all());
    }
}
