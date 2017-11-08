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

// Need to Define Only Contracts
use Laraspace\Api\Contracts\MatchContract;
use JWTAuth;
use Carbon\Carbon;

/**
 * Matches Resource Description.
 *
 * @Resource("Matches")
 *
 * @Author Kparikh@aecordigital.com
 */
class MatchController extends BaseController
{
    public function __construct(MatchContract $matchObj)
    {

        $this->matchObj = $matchObj;
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
    public function getDraws(Request $request){
        return $this->matchObj->getDraws($request);
    }
    public function getFixtures(Request $request){
        return $this->matchObj->getFixtures($request);
    }
    public function getStanding(Request $request, $refreshStanding = null){
        return $this->matchObj->getStanding($request, $refreshStanding);
    }
    public function getDrawTable(Request $request) {
        return $this->matchObj->getDrawTable($request);
    }
    public function scheduleMatch(Request $request) {
         return $this->matchObj->scheduleMatch($request);
    }
    public function unscheduleMatch(Request $request) {
        return $this->matchObj->unscheduleMatch($request);
    }

    public function getAllScheduledMatch(Request $request) {
        return $this->matchObj->getAllScheduledMatch($request);
    }
    public function getMatchDetail(Request $request)
    {
        return $this->matchObj->getMatchDetail($request);
    }
    public function generateMatchPrint(Request $request)
    {
        return $this->matchObj->generateMatchPrint($request->all());
    }
    public function removeAssignedReferee(Request $request)
    {
        return $this->matchObj->removeAssignedReferee($request);
    }
    public function assignReferee(Request $request) 
    {
        return $this->matchObj->assignReferee($request);
    }
    public function saveResult(Request $request)
    {
        return $this->matchObj->saveResult($request);
    }
    public function saveUnavailableBlock(Request $request)
    {
        return $this->matchObj->saveUnavailableBlock($request);
    }
    public function getUnavailableBlock(Request $request)
    {
        return $this->matchObj->getUnavailableBlock($request);
    }
    public function removeBlock($blockId)
    {
        return $this->matchObj->removeBlock($blockId);
    }
    public function updateScore(Request $request)
    {
        return $this->matchObj->updateScore($request);
    }
    public function refreshStanding(Request $request)
    {
        return $this->matchObj->refreshStanding($request->all());
    }

    public function automateMatchScheduleAndResult(Request $request, $tournamentId = null, $ageGroupId = null)
    {
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

            $matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
            $matchServiceObj = new \Laraspace\Api\Services\MatchService();
            $pitchRepoObj = new \Laraspace\Api\Repositories\PitchRepository();

            TempFixture::where('tournament_id', '=', $tournamentId)->update(['is_scheduled' => 0, 'pitch_id' => null, 'hometeam_score' => null, 'awayteam_score' => null]);

            $tournamentFixtures = TempFixture::where('age_group_id', '=', $ageGroupId)->where('tournament_id', '=', $tournamentId)->get();
            $pitch = $pitchRepoObj->getAllPitches($tournamentId)->first();
            $pitchAvailability = $pitch->pitchAvailability;
            $pitchAvailabilityIndex = 0;
            $stageMinutes = 0;

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
                
                $matchRepoObj->setMatchSchedule($matchData);

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
                    'refereeId' => NULL,
                ];

                $matchResult = $matchRepoObj->saveResult($matchData);
                $competationId = $matchServiceObj->calculateCupLeagueTable($matchData['matchId']);
            }
        } catch(\Exception $e) {
            $status = 'error';
        }

        return view('automate_tournament.success_match_scheduled', compact('status') );
    }

    public function saveStandingsManually(Request $request)
    {
        return $this->matchObj->saveStandingsManually($request);
    }
}
