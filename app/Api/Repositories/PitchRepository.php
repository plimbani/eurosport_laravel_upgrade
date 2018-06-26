<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Venue;
use Laraspace\Models\Pitch;
use Laraspace\Models\TempFixture;
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

    /**
     * Get location wise summary.
     *
     * @param Int $tournamentId
     *
     * @return [type]
     */
    public function getLocationWiseSummary($tournamentId)
    {
        $allPitchSizes = [];

        $totalAvailableTimeLocationWise = [];
        $allPitches = Pitch::where('tournament_id', $tournamentId)->get()->each(function ($item) use(&$totalAvailableTimeLocationWise, &$allPitchSizes) {
            if (!isset($totalAvailableTimeLocationWise[$item->venue_id])) {
                $totalAvailableTimeLocationWise[$item->venue_id] = [];
            }
            if (!isset($totalAvailableTimeLocationWise[$item->venue_id][$item->size])) {
                $totalAvailableTimeLocationWise[$item->venue_id][$item->size] = 0;
            }
            $totalAvailableTimeLocationWise[$item->venue_id][$item->size] += $item->pitch_capacity;
            $allPitchSizes[] = $item->size;
        });

        $totalTimeUsedLocationWise = [];
        $locationWisePitches = TempFixture::with('pitch')->where('temp_fixtures.tournament_id', $tournamentId)->where('temp_fixtures.is_scheduled', 1)->get()->each(function ($item) use(&$totalTimeUsedLocationWise, &$allPitchSizes) {
            if (!isset($totalTimeUsedLocationWise[$item->pitch->venue_id])) {
                $totalTimeUsedLocationWise[$item->pitch->venue_id] = [];
            }
            if (!isset($totalTimeUsedLocationWise[$item->pitch->venue_id][$item->pitch->size])) {
                $totalTimeUsedLocationWise[$item->pitch->venue_id][$item->pitch->size] = 0;
            }
            $totalTimeUsedLocationWise[$item->pitch->venue_id][$item->pitch->size] += $item->match_endtime->diffInMinutes($item->match_datetime);
            $allPitchSizes[] = $item->pitch->size;
        });

        /*$totalAvailableTimeLocationWiseData = $totalAvailableTimeLocationWise->pluck('totalAvailableTime', 'venue_id');
        $totalTimeUsedLocationWiseData = $totalTimeUsedLocationWise->pluck('totalTimeRequired', 'venue_id');*/
        $totalAvailableTimeLocationWiseKeys = array_keys($totalAvailableTimeLocationWise);
        $totalTimeUsedLocationWiseKeys = array_keys($totalTimeUsedLocationWise);
        $locations = array_values(array_unique(array_merge($totalAvailableTimeLocationWiseKeys, $totalTimeUsedLocationWiseKeys)));
        $allPitchSizes = array_unique($allPitchSizes);
        $allLocations = Venue::whereIn('id', $locations)->get();
        $allPitches = Pitch::where('tournament_id', $tournamentId)->get();

        return array('totalAvailableTimeLocationWise' => $totalAvailableTimeLocationWise, 'totalTimeUsedLocationWise' => $totalTimeUsedLocationWise, 'allLocations' => $allLocations, 'allPitchSizes' => $allPitchSizes, 'allPitches' => $allPitches);
    }
}
