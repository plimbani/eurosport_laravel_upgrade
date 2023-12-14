<?php

namespace App\Exports;

use App\Models\Pitch;
use App\Models\PitchAvailable;
use App\Models\PitchBreaks;
use App\Models\PitchUnavailable;
use App\Models\Tournament;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class PitchExport implements FromCollection,WithStyles,WithProperties,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    private $fileName = 'Pitchmatchschedule.xlsx';


      /**
    * Optional Writer Type
    */
  //  private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

     public function properties(): array
    {
        return [            
            'title' => 'Match Planner',
            'setPageOrientation' => 'ORIENTATION_LANDSCAPE' ,
        ];
    }

    public function __construct($tournamentDates, $tournamentPitches, $time, $matches)
    {
        $this->tournamentDates = $tournamentDates;
        $this->tournamentPitches = $tournamentPitches;
        $this->time = $time;
        $this->matches = $matches;
    }

    public function collection()
    {
        return Pitch::all();
    }

    public function styles(Worksheet $sheet)
    {
        
           $rowCount = 1;
                $cell = 2;
                foreach ($this->tournamentDates as $key => $date) {
                    $key = $key + 1;
                    $startRowIndex = array_search('08:00', $this->time);
                    $endRowIndex = array_search('23:00', $this->time);

                    if ($rowCount == 1) {
                        $sheet->setCellValue("A{$rowCount}", 'Day ' . $key . ': ' . $date->format('D d M Y'));

                        $sheet->getStyle("A{$rowCount}")->applyFromArray([
                            'fill' => [
                                'color' => [
                                    'rgb' => '2196F3',
                                ],
                            ],
                            'font' => [
                                'color' => [
                                    'rgb' => 'ffffff',
                                ],
                                'bold' => true,
                                'name' => 'Arial',
                                'size' => 10,
                            ],
                        ]);
                    $sheet->mergeCells("A" . ($cell - 1) . ":{$endRowIndex}" . ($cell - 1));
                    }

                    $rowCount++;
                    $cell++;

                    if ($rowCount > 2) {
                        $sheet->fromArray([], null, "A{$rowCount}");
                        $rowCount = $rowCount + 1;
                        $cell = $cell + 1;


                        $sheet->setCellValue("A{$rowCount}", 'Day ' . $key . ': ' . $date->format('D d M Y'));

                            $sheet->getStyle("A{$rowCount}")->applyFromArray([
                                'fill' => [
                                    'color' => [
                                        'rgb' => '2196F3',
                                    ],
                                ],
                                'font' => [
                                    'color' => [
                                        'rgb' => 'ffffff',
                                    ],
                                    'bold' => true,
                                    'name' => 'Arial',
                                    'size' => 10,
                                ],
                            ]);
                        $sheet->mergeCells("{$startRowIndex}" . ($cell - 1) . ":{$endRowIndex}" . ($cell - 1));

                      
                        $rowCount++;
                        $cell++;
                    }

                    // displaying pitch times
                    $sheet->setCellValue("A{$rowCount}", $this->time);


                    $beforePitchStartUnAvailableTime = [];
                    $beforePitchEndUnAvailableTime = [];
                    $dateTimestamp = Carbon::parse($date)->timestamp;
                    foreach ($this->tournamentPitches[$dateTimestamp] as $pitch) {
                        $pitchStartDate = Carbon::parse($date)->format('Y-m-d');
                        $pitchAvailability = PitchAvailable::where('pitch_id', $pitch->id)->where('stage_start_date', $pitchStartDate)->get();
                        foreach ($pitchAvailability as $availibility) {
                            $pitchStartTime = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date.' '.'08:00');
                            $pitchEndTime = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date.' '.'23:00');
                            $pitchAvailableStart = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date.' '.$availibility->stage_start_time);
                            $pitchAvailableEnd = Carbon::createFromFormat('d/m/Y H:i', $availibility->stage_start_date.' '.$availibility->stage_end_time);

                            while ($pitchStartTime < $pitchAvailableStart) {
                                $beforePitchStartUnAvailableTime[$availibility->stage_start_date][$pitch->id][] = $pitchStartTime->format('H:i');
                                $pitchStartTime->addMinute(5);
                            }
                            while ($pitchEndTime > $pitchAvailableEnd) {
                                $beforePitchEndUnAvailableTime[$availibility->stage_start_date][$pitch->id][] = $pitchAvailableEnd->format('H:i');
                                $pitchAvailableEnd->addMinute(5);
                            }

                            //pitch break block scenario
                            $pitchBreaks = PitchBreaks::where('pitch_id', $pitch->id)->where('availability_id', $availibility->id)->first();
                            if ($pitchBreaks && $pitchBreaks->break_start != null && $pitchBreaks->break_end != null) {
                                $breakStartTime = $pitchBreaks->break_start;
                                $breakEndTime = $pitchBreaks->break_end;

                                $pitchUnavailableBreakEndTime = Carbon::parse($breakEndTime)->subMinute(5)->format('H:i');
                                $startBreakIndex = array_search($breakStartTime, $time);
                                $endBreakIndex = array_search($pitchUnavailableBreakEndTime, $time);

                                $sheet->cell($startBreakIndex.$cell, function ($cell) use ($breakStartTime, $breakEndTime) {
                                    $cell->setValue($breakStartTime.' - '.$breakEndTime);
                                    $cell->setBackground('#808080');
                                    $cell->setFontColor('#ffffff');
                                    $cell->setFontFamily('Arial');
                                    $cell->setFontSize(10);
                                });
                                $sheet->mergeCells($startBreakIndex.$cell.':'.$endBreakIndex.$cell);
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

                                $sheet->cell($startUnavailableBreakIndex.$cell, function ($cell) use ($unavailableBreakStartTime, $unavailableBreakEndTime) {
                                    $unavailableBreakEndTime = Carbon::parse($unavailableBreakEndTime)->addMinute(5)->format('H:i');
                                    $cell->setValue($unavailableBreakStartTime.' - '.$unavailableBreakEndTime);
                                    $cell->setBackground('#808080');
                                    $cell->setFontColor('#ffffff');
                                    $cell->setFontFamily('Arial');
                                    $cell->setFontSize(10);
                                });
                                $sheet->mergeCells($startUnavailableBreakIndex.$cell.':'.$endUnavailableBreakIndex.$cell);
                            }
                        }
                        // displaying pitch names with its size
                       /* $sheet->cell('A'.$cell, function ($cell) use ($pitch) {
                            $cell->setCellValue($pitch->pitch_number.' ('.$pitch->size.')');
                        });*/




                        //pitch unavailable before starting pitch time block scenario
                        $matchstartDate = $date->format('d/m/Y');
                        if (array_key_exists($matchstartDate, $beforePitchStartUnAvailableTime)) {
                            if (array_key_exists($pitch->id, $beforePitchStartUnAvailableTime[$matchstartDate])) {
                                $timeArrCount = count($beforePitchStartUnAvailableTime[$matchstartDate][$pitch->id]) - 1;
                                $pitchUnavailableStartTime = $beforePitchStartUnAvailableTime[$matchstartDate][$pitch->id][0];
                                $pitchUnavailableEndTime = $beforePitchStartUnAvailableTime[$matchstartDate][$pitch->id][$timeArrCount];
                                $pitchUnavailableStartTimeIndex = array_search($pitchUnavailableStartTime, $this->time);
                                $pitchUnavailableEndTimeIndex = array_search($pitchUnavailableEndTime, $this->time);

                                 $pitchUnavailableEndTime = Carbon::parse($pitchUnavailableEndTime)->addMinute(5)->format('H:i');

                                $sheet->setCellValue($pitchUnavailableStartTimeIndex . $cell, $pitchUnavailableStartTime . ' - ' . $pitchUnavailableEndTime);


                                    $sheet->getStyle($pitchUnavailableStartTimeIndex . $cell)->applyFromArray([
                                        'fill' => [
                                            'color' => [
                                                'rgb' => '808080',
                                            ],
                                        ],
                                        'font' => [
                                            'color' => [
                                                'rgb' => 'ffffff',
                                            ],
                                            'family' => 'Arial',
                                            'size' => 10,
                                        ],
                                    ]);


                                $sheet->mergeCells($pitchUnavailableStartTimeIndex.$cell.':'.$pitchUnavailableEndTimeIndex.$cell);
                            }
                        }

                        //pitch unavailable after compelition of pitch time block scenario
                        if (array_key_exists($matchstartDate, $beforePitchEndUnAvailableTime)) {
                            if (array_key_exists($pitch->id, $beforePitchEndUnAvailableTime[$matchstartDate])) {
                                $beforePitchEndTimeCount = count($beforePitchEndUnAvailableTime[$matchstartDate][$pitch->id]) - 1;
                                $beforePitchUnavailableStartTime = $beforePitchEndUnAvailableTime[$matchstartDate][$pitch->id][0];
                                $beforePitchUnavailableStartTimeIndex = array_search($beforePitchUnavailableStartTime, $this->time);
                                $beforePitchUnavailableEndTime = $beforePitchEndUnAvailableTime[$matchstartDate][$pitch->id][$beforePitchEndTimeCount];
                                $beforePitchUnavailableEndTimeIndex = array_search($beforePitchUnavailableEndTime, $this->time);


                                 $beforePitchUnavailableEndTime = Carbon::parse($beforePitchUnavailableEndTime)->addMinute(5)->format('H:i');

                                $sheet->setCellValue($beforePitchUnavailableStartTimeIndex . $cell, $beforePitchUnavailableStartTime . ' - ' . $beforePitchUnavailableEndTime);

                                    $sheet->getStyle($beforePitchUnavailableStartTimeIndex . $cell)->applyFromArray([
                                        'fill' => [
                                            'color' => [
                                                'rgb' => '808080',
                                            ],
                                        ],
                                        'font' => [
                                            'color' => [
                                                'rgb' => 'ffffff',
                                            ],
                                            'family' => 'Arial',
                                            'size' => 10,
                                        ],
                                    ]);

                                $sheet->mergeCells($beforePitchUnavailableStartTimeIndex.$cell.':'.$beforePitchUnavailableEndTimeIndex.$cell);
                            }
                        }

                        // displaying match fixtures
                        $matchString = '';
                        foreach ($this->matches->where('pitch_id', $pitch->id)->where('match_day', $date->format('Y-m-d')) as $match) {
                            $ageCategoryColor = $match['age_category_color'] ? $match['age_category_color'] : $match['category_age_color'];
                            $ageCategoryFontColor = $match['category_age_font_color'];
                            $startTime = $match['match_datetime']->format('H:i');
                            $endTime = $match['match_endtime']->subMinutes(5)->format('H:i');
                            $startIndex = array_search($startTime, $this->time);
                            $endIndex = array_search($endTime, $this->time);

                            $matchActualName = $match['competation_type'] == 'Round Robin' ? ' ('.$match['actual_name'].')' : '';
                            $matchTime = $startTime.' - '.Carbon::parse($endTime)->addMinute(5)->format('H:i').$matchActualName."\n";
                            $refreeName = '';
                            if ($match['referre_name'] != ' ') {
                                $refreeName = $match['referre_name']."\n";
                            }
                            $score = '';

                            if ($match['hometeam_score'] !== null && $match['awayteam_score'] !== null) {
                                $score = "\n".$match['hometeam_score'].' - '.$match['awayteam_score'];
                            }
                            $matchString = $refreeName.$matchTime.$match['match_name'].$score;

                            // displaying fixtures

                            $sheet->setCellValue($startIndex . $cell, $matchString);

                            $sheet->getStyle($startIndex . $cell)->applyFromArray([
                                'fill' => [
                                    'color' => [
                                        'rgb' => $ageCategoryColor,
                                    ],
                                ],
                                'font' => [
                                    'color' => [
                                        'rgb' => $ageCategoryFontColor,
                                    ],
                                    'family' => 'Arial',
                                    'size' => 10,
                                ],
                            ]);
                            $sheet->mergeCells($startIndex.$cell.':'.$endIndex.$cell);
                            $sheet->getRowDimension($cell)->setRowHeight(50);
                            $sheet->getStyle($startIndex.$cell)->getAlignment()->setWrapText(true);
                        }
                        $rowCount++;
                        $cell++;
                    }

              }
              
           //$sheet->setPageOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
 


       /* return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]],
        ];*/
    }

    public function map($sheet): array
    {


            
        // This example will return 3 rows.
        // First row will have 2 column, the next 2 will have 1 column
        return [
         
        ];
    }

}
