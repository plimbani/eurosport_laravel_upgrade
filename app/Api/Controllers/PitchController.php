<?php

namespace Laraspace\Api\Controllers;

use JWTAuth;
use UrlSigner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Brotzka\DotenvEditor\DotenvEditor;
use Laraspace\Http\Requests\Pitch\ShowRequest;
use Laraspace\Http\Requests\Pitch\StoreRequest;
use Laraspace\Http\Requests\Pitch\UpdateRequest;
use Laraspace\Http\Requests\Pitch\DeleteRequest;
use Laraspace\Http\Requests\Pitch\GetPitchesRequest;
use Laraspace\Http\Requests\Pitch\GetSignedUrlForPitchMatchReportRequest;
use Laraspace\Http\Requests\Pitch\GetPitchSizeWiseSummaryRequest;
use Laraspace\Http\Requests\Pitch\GetLocationWiseSummaryRequest;
// Need to Define Only Contracts
use Laraspace\Api\Contracts\PitchContract;
use Laraspace\Http\Requests\Pitch\GetSignedUrlForPitchPlannerPrintRequest;
use Laraspace\Models\Tournament;
use PDF;

/**
 * Matches Resource Description.
 *
 * @Resource("Matches")
 *
 * @Author Kparikh@aecordigital.com
 */
class PitchController extends BaseController
{
    public function __construct(PitchContract $pitchObj)
    {
        $this->pitchObj = $pitchObj;
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Pitches.
     *
     * @Get("/pitches")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getPitches(GetPitchesRequest $request, $tournamentId)
    {
        return $this->pitchObj->getAllPitches($tournamentId);
    }

    /**
     * Get pitch size summary
     *
     * Get a JSON representation of all the Pitches.
     *
     * @Get("/getPitchSizeWiseSummary")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getPitchSizeWiseSummary(GetPitchSizeWiseSummaryRequest $request, $tournamentId)
    {
        return $this->pitchObj->getPitchSizeWiseSummary($tournamentId);
    }

    /**
     * Create New Match Result.
     *
     * @Post("/match/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createPitch(StoreRequest $request)
    {
        return $this->pitchObj->createPitch($request);
    }

    /**
     * Edit  Match result.
     *
     * @Post("/match/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(UpdateRequest $request,$pitchId)
    {
        return $this->pitchObj->edit($request,$pitchId);
    }
    public function show(ShowRequest $request, $pitchId)
    {
        return $this->pitchObj->getPitchData($pitchId);
    }
    public function deletePitch(DeleteRequest $request, $deleteid)
    {
        return $this->pitchObj->deletePitch($deleteid);
    }
    public function generatePitchMatchReport($pitchId)
    {
        return $this->pitchObj->generatePitchMatchReport($pitchId);
    }

    public function getSignedUrlForPitchMatchReport(GetSignedUrlForPitchMatchReportRequest $request, $pitchId)
    {
        $signedUrl = UrlSigner::sign(url('api/pitch/reportCard/' . $pitchId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    /**
     * Get lcation wise summary
     *
     * Get a JSON representation of all the Pitches.
     *
     * @Get("/getLocationWiseSummary")
     * @Versions({"v1"})
     */
    public function getLocationWiseSummary(GetLocationWiseSummaryRequest $request, $tournamentId)
    {
        return $this->pitchObj->getLocationWiseSummary($tournamentId);
    }

    public function getSignedUrlForPitchPlannerPrint(GetSignedUrlForPitchPlannerPrintRequest $request, $tournamentId)
    {
        $signedUrl = UrlSigner::sign(url('api/pitchPlanner/print/' . $tournamentId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function generatePitchPlannerPrint(Request $request, $tournamentId)
    {
        $date = new \DateTime(date('H:i d M Y'));

        $pitchPlannerPrintData = $this->pitchObj->getPitchPlannerPrintData($tournamentId);

        $pitchPlannerPdf =  "Pitch planner - ".$pitchPlannerPrintData['tournamentData']->name;
        $tournamentStartDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerPrintData['tournamentData']->start_date.'00:00:00');
        $tournamentEndDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerPrintData['tournamentData']->end_date.'00:00:00');

        $tournamentDates = [];

        while ($tournamentStartDate <= $tournamentEndDate)
        {
            array_push($tournamentDates, clone($tournamentStartDate));
            $tournamentStartDate->addDay();
        }

        $matches = collect([]);

        $tournamentPitches = [];

        foreach ($pitchPlannerPrintData['matches'] as $match) {

            if($match->match_datetime) {

                $stdate = Carbon::parse($match->match_datetime);

                $matches->push([
                        'match_id' => $match->id,
                        'referee_id' => $match->referee_id,
                        'pitch_id' => $match->pitch_id,
                        'match_datetime' => $stdate,
                        'match_endtime' => Carbon::parse($match->match_endtime),
                        'pitch_name' => $match->pitch_number,
                        'match_day' => $stdate->format('Y-m-d'),
                        'referre_name' => $match->first_name.' '.$match->last_name,
                        'venues_name' => $match->venues_name,
                        'match_name' => str_replace('@AWAY', $match->away_team_name, str_replace('@HOME', $match->home_team_name, $match->display_match_number)),
                        'hometeam_score' => $match->hometeam_score,
                        'awayteam_score' => $match->awayteam_score,
                    ]);

                $tournamentPitches[$match->pitch_id] = $match->pitch_number;
            }
        }

        $data = ['pitchPlannerPrintData' => $pitchPlannerPrintData, 'tournamentDates' => $tournamentDates, 'matches' => $matches, 'tournamentPitches' => $tournamentPitches];

        $pdf = PDF::loadView('pitchplanner.tournament_matches',$data)
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOption('footer-font-size', 9)
            ->setOption('footer-font-name', 'Open Sans')
            ->setOrientation('portrait')
            ->setOption('footer-right', 'Page [page] of [toPage]')
            ->setOption('header-right', $date->format('H:i D d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);

        return $pdf->inline($pitchPlannerPdf);
    }
}
