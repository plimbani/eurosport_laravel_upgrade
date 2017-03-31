<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\PitchAvailable;
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
        return PitchAvailable::where('pitch_id', $pitchId)->get();
    }
    

    public function createPitch($pitchData,$pitchId)
    {
        // dd($pitchData);
        for($i=1;$i<=$pitchData['stage'];$i++) {
            // dd(isset($pitchData['stage_start_time'.$i]));
            if(isset($pitchData['stage_start_date'.$i]) && isset($pitchData['stage_start_time'.$i])  ) {
                PitchAvailable::create([
                    'tournament_id' => $pitchData['tournamentId'],
                    'pitch_id' => $pitchId,
                    'stage_no' => $i,
                    'stage_start_date' => $pitchData['stage_start_date'.$i],
                    'stage_start_time' => $pitchData['stage_start_time'.$i],
                    'break_start_time' => $pitchData['stage_break_start'.$i],
                    'stage_continue_date' => $pitchData['stage_start_date'.$i],
                    'break_end_time' => $pitchData['stage_end_time'.$i],
                    'stage_end_date' => $pitchData['stage_start_date'.$i],
                    'stage_end_time' => $pitchData['stage_end_time'.$i],
                    'stage_capacity' => $pitchData['stage_capacity_min'.$i]
                ]);
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
        return PitchAvailable::where('pitch_id',$pitchId)->delete();
    }
    
}
