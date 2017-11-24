<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Pitch;
use Laraspace\Models\TournamentCompetationTemplates;
use DB;

class PitchRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('pitches');
    }

    public function getAllPitches($tournamentId)
    {
          // $pitch = DB::table('tournament_competation_template')
          //                   ->where('tournament_id',$tournamentId)
          //                   ->groupBy('pitch_size')
          //                   ->get();
        //echo "<pre>";print_r($pitch);echo "</pre>";exit;                 
        return Pitch::with('pitchAvailability')->where('tournament_id',$tournamentId)->get();
    }

    public function createPitch($pitchData)
    {
        // dd($pitchData);

        return Pitch::create([
            'tournament_id' => $pitchData['tournamentId'],
            'pitch_number' => $pitchData['pitch_number'],
            'type' => $pitchData['pitch_type'],
            'venue_id' => $pitchData['location'],
            'size' => $pitchData['pitch_size'],
            'pitch_capacity' => $pitchData['pitchCapacity'],

        ]);
    }
    public function getPitchData($pitchId)
    {
        return Pitch::find($pitchId);
    }

    public function edit($pitchData,$pitchId)
    {
        $updateData = [
            'tournament_id' => $pitchData['tournamentId'],
            'pitch_number' => $pitchData['pitch_number1'],
            'type' => $pitchData['pitch_type'],
            'venue_id' => $pitchData['location'],
            'size' => $pitchData['pitch_size'],
            'pitch_capacity' => $pitchData['pitchCapacity'],
            ];
        return Pitch::where('id', $pitchId)->update($updateData);
    }

    public function getPitchFromId($pitchId)
    {
        return Pitch::find($pitchId);
    }
}
