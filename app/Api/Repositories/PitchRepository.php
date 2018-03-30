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
        return Pitch::with(['pitchAvailability','pitchAvailability.pitchBreaks'])->where('tournament_id',$tournamentId)->get();

    }

    public function getPitchSizeWiseSummary($tournamentId)
    {
        $totalAvailableTimePitchSizeWise = Pitch::where('tournament_id', $tournamentId)->select('size', DB::raw('SUM(pitch_capacity) AS totalAvailableTime'))->groupBy('size')->get();
        $totalTimeRequiredPitchSizeWise = TournamentCompetationTemplates::where('tournament_id', $tournamentId)->select('pitch_size', DB::raw('SUM(total_time) AS totalTimeRequired'))->groupBy('pitch_size')->get();

        $totalAvailableTimePitchSizeWiseData = $totalAvailableTimePitchSizeWise->pluck('totalAvailableTime', 'size');
        $totalTimeRequiredPitchSizeWiseData = $totalTimeRequiredPitchSizeWise->pluck('totalTimeRequired', 'pitch_size');
        $totalAvailableTimePitchSizeWiseKeys = $totalAvailableTimePitchSizeWiseData->keys()->all();
        $totalTimeRequiredPitchSizeWiseKeys = $totalTimeRequiredPitchSizeWiseData->keys()->all();
        $allPitchSizes = array_values(array_unique(array_merge($totalAvailableTimePitchSizeWiseKeys, $totalTimeRequiredPitchSizeWiseKeys)));

        return array('totalAvailableTimePitchSizeWise' => $totalAvailableTimePitchSizeWiseData, 'totalTimeRequiredPitchSizeWise' => $totalTimeRequiredPitchSizeWiseData, 'allPitchSizes' => $allPitchSizes);
    }

    public function createPitch($pitchData)
    {
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
