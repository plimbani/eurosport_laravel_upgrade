<?php

namespace App\Api\Controllers;

use App\Api\Contracts\PitchContract;
use App\Http\Requests\Pitch\DeleteRequest;
use App\Http\Requests\Pitch\GetLocationWiseSummaryRequest;
use App\Http\Requests\Pitch\GetPitchesRequest;
use App\Http\Requests\Pitch\GetPitchSizeWiseSummaryRequest;
use App\Http\Requests\Pitch\GetSignedUrlForPitchMatchReportRequest;
use App\Http\Requests\Pitch\GetSignedUrlForPitchPlannerExportRequest;
use App\Http\Requests\Pitch\GetSignedUrlForPitchPlannerPrintRequest;
use App\Http\Requests\Pitch\ShowRequest;
use App\Http\Requests\Pitch\StoreRequest;
use App\Http\Requests\Pitch\UpdateRequest;
// Need to Define Only Contracts
use App\Models\Pitch;
use App\Models\PitchAvailable;
use App\Models\PitchBreaks;
use App\Models\PitchUnavailable;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use UrlSigner;
use App\Exports\PitchExport;

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
        $this->tournamentLogo = getenv('S3_URL').'/assets/img/tournament_logo/';
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Pitches.
     *
     * @Get("/pitches")
     *
     * @Versions({"v1"})
     *
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
     *
     * @Versions({"v1"})
     *
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
     *
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
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(UpdateRequest $request, $pitchId)
    {
        return $this->pitchObj->edit($request, $pitchId);
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
        $signedUrl = UrlSigner::sign(secure_url('api/pitch/reportCard/'.$pitchId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    /**
     * Get lcation wise summary
     *
     * Get a JSON representation of all the Pitches.
     *
     * @Get("/getLocationWiseSummary")
     *
     * @Versions({"v1"})
     */
    public function getLocationWiseSummary(GetLocationWiseSummaryRequest $request, $tournamentId)
    {
        return $this->pitchObj->getLocationWiseSummary($tournamentId);
    }

    public function getSignedUrlForPitchPlannerPrint(GetSignedUrlForPitchPlannerPrintRequest $request, $tournamentId)
    {
        $signedUrl = UrlSigner::sign(url('api/pitchPlanner/print/'.$tournamentId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function generatePitchPlannerPrint(Request $request, $tournamentId)
    {
        $date = new \DateTime(date('H:i d M Y'));

        $pitchPlannerPrintData = $this->pitchObj->getPitchPlannerPrintData($tournamentId);
        $pitchPlannerPdf = 'Pitch planner - '.$pitchPlannerPrintData['tournamentData']->name;
        $tournamentStartDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerPrintData['tournamentData']->start_date.'00:00:00');
        $tournamentEndDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerPrintData['tournamentData']->end_date.'00:00:00');

        $tournamentDates = [];

        while ($tournamentStartDate <= $tournamentEndDate) {
            array_push($tournamentDates, clone $tournamentStartDate);
            $tournamentStartDate->addDay();
        }

        $tournamentLogo = null;
        $tournamentDetail = Tournament::find($tournamentId);
        if ($tournamentDetail->logo != null) {
            $tournamentLogo = $this->tournamentLogo.$tournamentDetail->logo;
        }
        $matches = collect([]);

        $tournamentPitches = [];

        foreach ($pitchPlannerPrintData['matches'] as $match) {
            $stdate = Carbon::parse($match->match_datetime);
            $compelitionType = $match->competation_type;
            $competitionActualName = explode('-', ($match->actual_name));

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
                'actual_name' => $competitionActualName[2].' '.$competitionActualName[3],
                'competation_type' => $match->competation_type,
            ]);
            $tournamentPitches[$match->pitch_id] = $match->pitch_number;
        }
        $data = ['pitchPlannerPrintData' => $pitchPlannerPrintData, 'tournamentDates' => $tournamentDates, 'matches' => $matches, 'tournamentPitches' => $tournamentPitches, 'tournamentLogo' => $tournamentLogo];

        $pdf = PDF::loadView('pitchplanner.tournament_matches', $data)
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOption('footer-spacing', '5')
            ->setOption('footer-font-size', 7)
            ->setOption('footer-font-name', 'Open Sans')
            ->setOrientation('portrait')
            ->setOption('footer-right', 'Page [page] of [toPage]')
            ->setOption('header-right', $date->format('H:i D d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);

        return $pdf->inline($pitchPlannerPdf);
    }

    public function getSignedUrlForPitchPlannerExport(GetSignedUrlForPitchPlannerExportRequest $request, $tournamentId)
    {
        $signedUrl = UrlSigner::sign(secure_url('api/pitchPlanner/export/'.$tournamentId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function generatePitchPlannerExport(Request $request, $tournamentId)
    {
        $pitchPlannerExportData = $this->pitchObj->getPitchPlannerPrintData($tournamentId);
        $tournamentStartDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerExportData['tournamentData']->start_date.'00:00:00');
        $tournamentEndDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerExportData['tournamentData']->end_date.'00:00:00');

        $tournamentDates = [];
        while ($tournamentStartDate <= $tournamentEndDate) {
            array_push($tournamentDates, clone $tournamentStartDate);
            $tournamentStartDate->addDay();
        }

        $i = 1;
        $startTime = Carbon::parse('8:00');
        $endTime = Carbon::parse('23:00');
        $time[0] = '';

        $endColumn = 'HZ';
        $alphabet = $this->createColumnsArray($endColumn, $firstLetters = '');

        while ($startTime <= $endTime) {
            $time[$alphabet[$i]] = $startTime->format('H:i');
            $startTime->addMinute(5);
            $i++;
        }

        $matches = collect();
        $tournamentPitches = [];
        foreach ($pitchPlannerExportData['matches'] as $match) {
            $compelitionType = $match->competation_type;
            $competitionActualName = explode('-', ($match->actual_name));
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
                'age_category_color' => $match->age_category_color,
                'category_age_color' => $match->category_age_color,
                'category_age_font_color' => $match->category_age_font_color,
                'actual_name' => $competitionActualName[2].' '.$competitionActualName[3],
                'competation_type' => $match->competation_type,
            ]);
        }

        foreach ($tournamentDates as $date) {
            $startDate = Carbon::parse($date)->format('Y-m-d');
            $startDateTimestamp = Carbon::parse($date)->timestamp;
            $pitches = \DB::table('pitches')
                ->leftjoin('pitch_availibility', 'pitch_availibility.pitch_id', '=', 'pitches.id')
                ->where('pitch_availibility.stage_start_date', $startDate)
                ->where('pitches.tournament_id', $tournamentId)
                ->where('pitches.deleted_at', '=', null)
                ->where('pitch_availibility.deleted_at', '=', null)
                ->select('pitches.*')
                ->get();

            $tournamentPitches[$startDateTimestamp] = $pitches;
        }

        return \Excel::store(new PitchExport($tournamentDates, $tournamentPitches, $time, $matches), 'matchplanner.xlsx');
    }

    public function createColumnsArray($endColumn, $firstLetters = '')
    {
        $columns = [];
        $length = strlen($endColumn);
        $letters = range('A', 'Z');

        foreach ($letters as $letter) {
            $column = $firstLetters.$letter;
            $columns[] = $column;
            if ($column == $endColumn) {
                return $columns;
            }
        }

        foreach ($columns as $column) {
            if (! in_array($endColumn, $columns) && strlen($column) < $length) {
                $newColumns = $this->createColumnsArray($endColumn, $column);
                $columns = array_merge($columns, $newColumns);
            }
        }

        return $columns;
    }

    public function getPitchSearchRecord(Request $tournamentData)
    {
        return $this->pitchObj->getPitchSearchRecord($tournamentData);
    }

    public function getVenuesDropDownData(Request $tournamentData)
    {
        return $this->pitchObj->getVenuesDropDownData($tournamentData);
    }

    public function updatePitchOrder(Request $request)
    {
        return $this->pitchObj->updatePitchOrder($request);
    }
}
