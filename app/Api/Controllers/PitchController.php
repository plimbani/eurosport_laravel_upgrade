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
use Laraspace\Http\Requests\Pitch\GetSignedUrlForPitchPlannerExportRequest;
use Laraspace\Models\Tournament;
use Laraspace\Models\Pitch;
use Laraspace\Models\PitchAvailable;
use Laraspace\Models\PitchBreaks;
use Laraspace\Models\PitchUnavailable;
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
        $this->tournamentLogo =  getenv('S3_URL').'/assets/img/tournament_logo/';
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
        $signedUrl = UrlSigner::sign(secure_url('api/pitch/reportCard/' . $pitchId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

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

        $tournamentLogo = null;
        $tournamentDetail = Tournament::find($tournamentId);
        if($tournamentDetail->logo != null) {
            $tournamentLogo = $this->tournamentLogo. $tournamentDetail->logo;
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
        $data = ['pitchPlannerPrintData' => $pitchPlannerPrintData, 'tournamentDates' => $tournamentDates, 'matches' => $matches, 'tournamentPitches' => $tournamentPitches, 'tournamentLogo' => 
        $tournamentLogo];

        $pdf = PDF::loadView('pitchplanner.tournament_matches',$data)
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
        $signedUrl = UrlSigner::sign(env('APP_URL').'/api/pitchPlanner/export/' . $tournamentId, Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function generatePitchPlannerExport(Request $request, $tournamentId)
    {
        $pitchPlannerExportData = $this->pitchObj->getPitchPlannerPrintData($tournamentId);
        $tournamentStartDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerExportData['tournamentData']->start_date.'00:00:00');
        $tournamentEndDate = Carbon::createFromFormat('d/m/Y H:i:s', $pitchPlannerExportData['tournamentData']->end_date.'00:00:00');

        $tournamentDates = [];
        while ($tournamentStartDate <= $tournamentEndDate) {
            array_push($tournamentDates, clone($tournamentStartDate));
            $tournamentStartDate->addDay();
        }

        $i = 1;
        $startTime = Carbon::parse('8:00');
        $endTime = Carbon::parse('23:00');
        $time[0] = '';

        $endColumn = 'HZ';
        $alphabet = $this->createColumnsArray($endColumn, $firstLetters = '');

        while($startTime <= $endTime) {
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
                            ->where('pitches.deleted_at', '=', NULL)
                            ->where('pitch_availibility.deleted_at', '=', NULL)
                            ->select('pitches.*')
                            ->get();

            $tournamentPitches[$startDateTimestamp] = $pitches;
        }


        \Excel::create('Match planner', function($excel) use ($tournamentDates, $tournamentPitches, $time, $matches) {
            $excel->sheet('Match planner', function($sheet) use ($tournamentDates, $tournamentPitches, $time, $matches)
            {
                $rowCount = 1;
                $cell = 2;
                foreach ($tournamentDates as $key => $date) {
                    $key = $key + 1;                    
                    $startRowIndex = array_search('08:00', $time);
                    $endRowIndex = array_search('23:00', $time);

                    if($rowCount == 1) {
                        $sheet->row($rowCount, ['Day '. $key .': '. $date->format('D d M Y')]);
                        $sheet->row($rowCount, function($row){
                            $row->setBackground("#2196F3");
                            $row->setFontColor('#ffffff');
                            $row->setFontWeight('bold');
                            $row->setFontFamily('Arial');
                            $row->setFontSize(10);
                        });

                        $sheet->mergeCells('A'.($cell - 1). ':' .$endRowIndex.($cell - 1));
                    }

                    $rowCount++;
                    $cell++;

                    if($rowCount > 2) {
                        $sheet->row($rowCount, []);
                        $rowCount = $rowCount + 1;
                        $cell = $cell + 1;

                        $sheet->row($rowCount, ['Day '. $key .': '. $date->format('D d M Y')]);
                        $sheet->row($rowCount, function($row){
                            $row->setBackground("#2196F3");
                            $row->setFontColor('#ffffff');
                            $row->setFontWeight('bold');
                            $row->setFontFamily('Arial');
                            $row->setFontSize(10);
                        });

                        $sheet->mergeCells($startRowIndex.($cell - 1). ':' .$endRowIndex.($cell - 1));
                        $rowCount++;
                        $cell++;
                    }

                    // displaying pitch times
                    $sheet->row($rowCount, $time);

                    $beforePitchStartUnAvailableTime = [];
                    $beforePitchEndUnAvailableTime = [];
                    $dateTimestamp = Carbon::parse($date)->timestamp;
                    foreach($tournamentPitches[$dateTimestamp] as $pitch) {
                        $pitchStartDate = Carbon::parse($date)->format('Y-m-d');
                        $pitchAvailability = PitchAvailable::where('pitch_id', $pitch->id)->where('stage_start_date', $pitchStartDate)->get();
                        foreach($pitchAvailability as $availibility) {
                            $pitchStartTime = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date . ' ' . '08:00');
                            $pitchEndTime = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date . ' ' . '23:00');
                            $pitchAvailableStart = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date . ' ' . $availibility->stage_start_time);
                            $pitchAvailableEnd = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date . ' ' . $availibility->stage_end_time);

                            while($pitchStartTime < $pitchAvailableStart) {
                                $beforePitchStartUnAvailableTime[$availibility->stage_start_date][$pitch->id][] = $pitchStartTime->format('H:i');
                                $pitchStartTime->addMinute(5);
                            }
                            while($pitchEndTime > $pitchAvailableEnd) {
                                $beforePitchEndUnAvailableTime[$availibility->stage_start_date][$pitch->id][] = $pitchAvailableEnd->format('H:i');
                                $pitchAvailableEnd->addMinute(5);                     
                            }

                            //pitch break block scenario
                            $pitchBreaks = PitchBreaks::where('pitch_id', $pitch->id)->where('availability_id', $availibility->id)->first();
                            if($pitchBreaks && $pitchBreaks->break_start != null && $pitchBreaks->break_end != null) {
                                $breakStartTime = $pitchBreaks->break_start;
                                $breakEndTime = $pitchBreaks->break_end;

                                $pitchUnavailableBreakEndTime = Carbon::parse($breakEndTime)->subMinute(5)->format('H:i');
                                $startBreakIndex = array_search($breakStartTime, $time);
                                $endBreakIndex = array_search($pitchUnavailableBreakEndTime, $time);

                                $sheet->cell($startBreakIndex.$cell, function($cell) use ($breakStartTime, $breakEndTime){
                                    $cell->setValue($breakStartTime .' - '. $breakEndTime);
                                    $cell->setBackground('#808080');
                                    $cell->setFontColor('#ffffff');
                                    $cell->setFontFamily('Arial');
                                    $cell->setFontSize(10);
                                });
                                $sheet->mergeCells($startBreakIndex.$cell. ':' .$endBreakIndex.$cell);
                            }
                            //pitch unavailable block scenario
                            $pitchUnavailable = PitchUnavailable::where('pitch_id', $pitch->id)
                                                                ->whereDate('match_start_datetime', $pitchStartDate)
                                                                ->get();
                            foreach ($pitchUnavailable as $key => $unavailable) {
                                $unavailableBreakStartTime = Carbon::parse($unavailable->match_start_datetime)->format('H:i');
                                $unavailableBreakEndTime = Carbon::parse($unavailable->match_end_datetime)->subMinute(5)->format('H:i');
                                
                                $startUnavailableBreakIndex = array_search($unavailableBreakStartTime, $time);
                                $endUnavailableBreakIndex = array_search($unavailableBreakEndTime, $time);

                                $sheet->cell($startUnavailableBreakIndex.$cell, function($cell) use ($unavailableBreakStartTime, $unavailableBreakEndTime){
                                    $unavailableBreakEndTime = Carbon::parse($unavailableBreakEndTime)->addMinute(5)->format('H:i');
                                    $cell->setValue($unavailableBreakStartTime .' - '. $unavailableBreakEndTime);
                                    $cell->setBackground('#808080');
                                    $cell->setFontColor('#ffffff');
                                    $cell->setFontFamily('Arial');
                                    $cell->setFontSize(10);
                                });
                                $sheet->mergeCells($startUnavailableBreakIndex.$cell. ':' .$endUnavailableBreakIndex.$cell);                                
                            }
                        }
                        // displaying pitch names with its size
                        $sheet->cell('A'.$cell, function($cell) use ($pitch) {
                            $cell->setValue($pitch->pitch_number.' ('.$pitch->size.')');
                        });

                        //pitch unavailable before starting pitch time block scenario
                        $matchstartDate = $date->format('d/m/Y');
                        if(array_key_exists($matchstartDate, $beforePitchStartUnAvailableTime)){
                            if(array_key_exists($pitch->id, $beforePitchStartUnAvailableTime[$matchstartDate])){
                                $timeArrCount = count($beforePitchStartUnAvailableTime[$matchstartDate][$pitch->id]) - 1;
                                $pitchUnavailableStartTime = $beforePitchStartUnAvailableTime[$matchstartDate][$pitch->id][0];
                                $pitchUnavailableEndTime = $beforePitchStartUnAvailableTime[$matchstartDate][$pitch->id][$timeArrCount];
                                $pitchUnavailableStartTimeIndex = array_search($pitchUnavailableStartTime, $time);
                                $pitchUnavailableEndTimeIndex = array_search($pitchUnavailableEndTime, $time);

                                $sheet->cell($pitchUnavailableStartTimeIndex.$cell, function($cell) use ($pitchUnavailableStartTime, $pitchUnavailableEndTime){
                                    $pitchUnavailableEndTime = Carbon::parse($pitchUnavailableEndTime)->addMinute(5)->format('H:i');
                                    $cell->setValue($pitchUnavailableStartTime .' - '. $pitchUnavailableEndTime);
                                    $cell->setBackground('#808080');
                                    $cell->setFontColor('#ffffff');
                                    $cell->setFontFamily('Arial');
                                    $cell->setFontSize(10);
                                });
                                $sheet->mergeCells($pitchUnavailableStartTimeIndex.$cell. ':' .$pitchUnavailableEndTimeIndex.$cell);
                            }
                        }

                        //pitch unavailable after compelition of pitch time block scenario
                        if(array_key_exists($matchstartDate, $beforePitchEndUnAvailableTime)){
                            if(array_key_exists($pitch->id, $beforePitchEndUnAvailableTime[$matchstartDate])){
                                $beforePitchEndTimeCount = count($beforePitchEndUnAvailableTime[$matchstartDate][$pitch->id]) - 1;
                                $beforePitchUnavailableStartTime = $beforePitchEndUnAvailableTime[$matchstartDate][$pitch->id][0];
                                $beforePitchUnavailableStartTimeIndex = array_search($beforePitchUnavailableStartTime, $time);
                                $beforePitchUnavailableEndTime = $beforePitchEndUnAvailableTime[$matchstartDate][$pitch->id][$beforePitchEndTimeCount];                        
                                $beforePitchUnavailableEndTimeIndex = array_search($beforePitchUnavailableEndTime, $time);

                                $sheet->cell($beforePitchUnavailableStartTimeIndex.$cell, function($cell) use ($beforePitchUnavailableStartTime, $beforePitchUnavailableEndTime){
                                    $beforePitchUnavailableEndTime = Carbon::parse($beforePitchUnavailableEndTime)->addMinute(5)->format('H:i');                                
                                    $cell->setValue($beforePitchUnavailableStartTime .' - '. $beforePitchUnavailableEndTime);
                                    $cell->setBackground('#808080');
                                    $cell->setFontColor('#ffffff');
                                    $cell->setFontFamily('Arial');
                                    $cell->setFontSize(10);
                                });
                                $sheet->mergeCells($beforePitchUnavailableStartTimeIndex.$cell. ':' .$beforePitchUnavailableEndTimeIndex.$cell);
                            }
                        }

                        // displaying match fixtures
                        $matchString = '';
                        foreach($matches->where('pitch_id', $pitch->id)->where('match_day', $date->format('Y-m-d')) as $match) {
                            $ageCategoryColor = $match['age_category_color'] ? $match['age_category_color'] : $match['category_age_color'];
                            $ageCategoryFontColor = $match['category_age_font_color'];
                            $startTime = $match['match_datetime']->format('H:i');
                            $endTime = $match['match_endtime']->subMinutes(5)->format('H:i');
                            $startIndex = array_search($startTime, $time);
                            $endIndex = array_search($endTime, $time);

                            $matchActualName = $match['competation_type'] == 'Round Robin' ? ' ('.$match['actual_name'].')' : '';
                            $matchTime = $startTime . ' - ' . Carbon::parse($endTime)->addMinute(5)->format('H:i'). $matchActualName . "\n";
                            $refreeName = '';
                            if($match['referre_name'] != ' '){
                                $refreeName = $match['referre_name']."\n";
                            }
                            $score = '';
                            
                            if($match['hometeam_score'] !== null && $match['awayteam_score'] !== null) {
                                $score = "\n".$match['hometeam_score'] . ' - ' .$match['awayteam_score'];
                            }
                            $matchString = $refreeName .$matchTime. $match['match_name'] .$score;

                            // displaying fixtures
                            $sheet->cell($startIndex.$cell, function($cell) use ($matchString, $ageCategoryColor, $ageCategoryFontColor) {
                                $cell->setValue($matchString);
                                $cell->setBackground($ageCategoryColor);
                                $cell->setFontColor($ageCategoryFontColor);
                                $cell->setFontFamily('Arial');
                                $cell->setFontSize(10);
                            });
                            $sheet->mergeCells($startIndex.$cell. ':' .$endIndex.$cell);
                            $sheet->getRowDimension($cell)->setRowHeight(50);
                            $sheet->getStyle($startIndex.$cell)->getAlignment()->setWrapText(true);
                        }
                        $rowCount++;
                        $cell++;
                    }
                }
                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');
    }

    public function createColumnsArray($endColumn, $firstLetters = '')
    {
        $columns = array();
        $length = strlen($endColumn);
        $letters = range('A', 'Z');
                
        foreach ($letters as $letter) {
          $column = $firstLetters . $letter;
          $columns[] = $column;
          if ($column == $endColumn)
            return $columns;
        }

        foreach ($columns as $column) {
          if (!in_array($endColumn, $columns) && strlen($column) < $length) {
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
