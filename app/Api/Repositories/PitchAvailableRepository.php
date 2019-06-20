<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\PitchAvailable;
use Laraspace\Models\PitchBreaks;
use DB;

class PitchAvailableRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('pitch_availability');
    }

    public function getAllPitches()
    {
        return PitchAvailable::all();
    }
    public function getPitchData($pitchId)
    {
        return PitchAvailable::with('pitchbreaks')->where('pitch_id', $pitchId)->get();
    }
    

    public function createPitch($pitchData,$pitchId)
    {
        // dd($pitchData);
        for($i=1;$i<=$pitchData['stage'];$i++) {
            // dd(isset($pitchData['stage_start_time'.$i]));
            if(isset($pitchData['stage_start_date'.$i]) && isset($pitchData['stage_start_time'.$i])  ) {
                if(isset(  $pitchData['stage_break_chk'.$i]) && $pitchData['stage_break_chk'.$i]== 'on' ){
                    $break_enable = true;
                    // $break_start_time = $pitchData['stage_break_start'.$i];
                    // $break_end_time = $pitchData['stage_continue_time'.$i];
                }else{
                    $break_enable = false;
                    // $break_start_time = $pitchData['stage_start_time'.$i];
                    // $break_end_time = $pitchData['stage_start_time'.$i];
                }
                $pitchAvailableData = PitchAvailable::create([
                    'tournament_id' => $pitchData['tournamentId'],
                    'pitch_id' => $pitchId,
                    'stage_no' => $i,
                    'stage_start_date' => $pitchData['stage_start_date'.$i],
                    'stage_start_time' => $pitchData['stage_start_time'.$i],
                    // 'break_start_time' => '10:00',
                    'stage_continue_date' => $pitchData['stage_start_date'.$i],
                    // 'break_end_time' => '10:00',
                    'stage_end_date' => $pitchData['stage_start_date'.$i],
                    'stage_end_time' => $pitchData['stage_end_time'.$i],
                    'break_enable' => $break_enable,
                    
                    'stage_capacity' => $pitchData['stage_capacity_min'.$i]
                ]);
                if($break_enable) {
                    for($j=1;$j<=$pitchData['totalBreaksForStage'.$i];$j++){
                        PitchBreaks::create([
                            'pitch_id' => $pitchId,
                            'availability_id' => $pitchAvailableData->id,
                            'break_start' => $pitchData['stage_break_start'.$i.'-'.$j],
                            'break_end' =>  $pitchData['stage_continue_time'.$i.'-'.$j],
                            // 'break_no' => 1,
                        ]);
                    }
        
                }
            }
        }
        
    }

    public function edit($data)
    {
       
        return PitchAvailable::where('id', $data['id'])->update($data);
    }

    public function getPitchFromId($pitchId)
    {
        return PitchAvailable::find($pitchId);
    }
    public function removePitchAvailability($pitchId)
    {
        PitchBreaks::where('pitch_id',$pitchId)->delete();
        return PitchAvailable::where('pitch_id',$pitchId)->delete();
    }
    
}
