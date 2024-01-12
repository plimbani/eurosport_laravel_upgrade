<?php

namespace App\Api\Repositories;

use App\Models\Pitch;
use App\Models\TempFixture;
use App\Models\Tournament;
use App\Models\TournamentCompetationTemplates;
use App\Models\Venue;
use DB;

class PitchRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('pitches');
    }

    public function getAllPitches($tournamentId)
    {
        $pitches = Pitch::with(['pitchAvailability', 'pitchAvailability.pitchBreaks', 'venue'])->where('tournament_id', $tournamentId)->orderBy('order')->get();

        return ['pitches' => $pitches];

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

        return ['totalAvailableTimePitchSizeWise' => $totalAvailableTimePitchSizeWiseData, 'totalTimeRequiredPitchSizeWise' => $totalTimeRequiredPitchSizeWiseData, 'allPitchSizes' => $allPitchSizes];
    }

    public function createPitch($pitchData)
    {
        // Get max order from pitches
        $maxOrder = Pitch::where('tournament_id', $pitchData['tournamentId'])->max('order');

        return Pitch::create([
            'tournament_id' => $pitchData['tournamentId'],
            'pitch_number' => $pitchData['pitch_number'],
            'type' => $pitchData['pitch_type'],
            'venue_id' => $pitchData['location'],
            'size' => $pitchData['pitch_size'],
            'pitch_capacity' => $pitchData['pitchCapacity'],
            'order' => $maxOrder + 1,
            'duplicated_from' => isset($pitchData['duplicated_from']) ? $pitchData['duplicated_from'] : null,
        ]);
    }

    public function getPitchData($pitchId)
    {
        return Pitch::find($pitchId);
    }

    public function edit($pitchData, $pitchId)
    {
        $updateData = [
            'tournament_id' => $pitchData['tournamentId'],
            'pitch_number' => $pitchData['pitch_number'],
            'type' => $pitchData['pitch_type'],
            'venue_id' => $pitchData['location'],
            'size' => $pitchData['pitch_size'],
            'pitch_capacity' => $pitchData['pitchCapacity'],
        ];

        $data = Pitch::where('id', $pitchId)->update($updateData);

        if ($data) {
            $tempFixtures = TempFixture::where('pitch_id', $pitchId)
                ->where('tournament_id', $pitchData['tournamentId'])
                ->update(['venue_id' => $pitchData['location']]);
        }

        return $data;
    }

    public function getPitchFromId($pitchId)
    {
        return Pitch::find($pitchId);
    }

    /**
     * Get location wise summary.
     *
     * @param  int  $tournamentId
     * @return [type]
     */
    public function getLocationWiseSummary($tournamentId)
    {
        $allPitchSizes = [];

        $totalAvailableTimeLocationWise = [];
        $allPitches = Pitch::where('tournament_id', $tournamentId)->get()->each(function ($item) use (&$totalAvailableTimeLocationWise, &$allPitchSizes) {
            if (! isset($totalAvailableTimeLocationWise[$item->venue_id])) {
                $totalAvailableTimeLocationWise[$item->venue_id] = [];
            }
            if (! isset($totalAvailableTimeLocationWise[$item->venue_id][$item->size])) {
                $totalAvailableTimeLocationWise[$item->venue_id][$item->size] = 0;
            }
            $totalAvailableTimeLocationWise[$item->venue_id][$item->size] += $item->pitch_capacity;
            $allPitchSizes[] = $item->size;
        });

        $totalTimeUsedLocationWise = [];
        $locationWisePitches = TempFixture::with('pitch')->where('temp_fixtures.tournament_id', $tournamentId)->where('temp_fixtures.is_scheduled', 1)->get()->each(function ($item) use (&$totalTimeUsedLocationWise, &$allPitchSizes) {
            if (! isset($totalTimeUsedLocationWise[$item->pitch->venue_id])) {
                $totalTimeUsedLocationWise[$item->pitch->venue_id] = [];
            }
            if (! isset($totalTimeUsedLocationWise[$item->pitch->venue_id][$item->pitch->size])) {
                $totalTimeUsedLocationWise[$item->pitch->venue_id][$item->pitch->size] = 0;
            }
            $totalTimeUsedLocationWise[$item->pitch->venue_id][$item->pitch->size] += $item->match_endtime->diffInMinutes($item->match_datetime);
            $allPitchSizes[] = $item->pitch->size;
        });

        $totalAvailableTimeLocationWiseKeys = array_keys($totalAvailableTimeLocationWise);
        $totalTimeUsedLocationWiseKeys = array_keys($totalTimeUsedLocationWise);
        $locations = array_values(array_unique(array_merge($totalAvailableTimeLocationWiseKeys, $totalTimeUsedLocationWiseKeys)));
        $allPitchSizes = array_values(array_unique($allPitchSizes));
        $allLocations = Venue::whereIn('id', $locations)->get();
        $allPitches = Pitch::where('tournament_id', $tournamentId)->get();

        return ['totalAvailableTimeLocationWise' => $totalAvailableTimeLocationWise, 'totalTimeUsedLocationWise' => $totalTimeUsedLocationWise, 'allLocations' => $allLocations, 'allPitchSizes' => $allPitchSizes, 'allPitches' => $allPitches];
    }

    /**
     * Get pitch planner print data.
     *
     * @param  int  $tournamentId
     * @return [type]
     */
    public function getPitchPlannerPrintData($tournamentId)
    {
        $tournament = Tournament::find($tournamentId);

        $matches = DB::table('temp_fixtures')
            ->select('temp_fixtures.*', 'referee.first_name', 'referee.last_name', 'pitches.pitch_number', 'pitches.size', 'venues.name as venues_name', 'tournament_competation_template.category_age_color', 'tournament_competation_template.category_age_font_color', 'competitions.actual_name', 'competitions.competation_type')
            ->leftjoin('referee', 'temp_fixtures.referee_id', '=', 'referee.id')
            ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
            ->leftjoin('venues', 'pitches.venue_id', '=', 'venues.id')
            ->leftjoin('competitions', 'temp_fixtures.competition_id', '=', 'competitions.id')
            ->leftjoin('tournament_competation_template', 'temp_fixtures.age_group_id', '=', 'tournament_competation_template.id')
            ->where('temp_fixtures.tournament_id', $tournamentId)
            ->where('temp_fixtures.is_scheduled', 1)
            ->orderBy('temp_fixtures.match_datetime')
            ->orderBy('temp_fixtures.pitch_id')
            ->get();

        return ['tournamentData' => $tournament, 'matches' => $matches];
    }

    public function getPitchSearchRecord($tournamentData)
    {
        $selectVenue = false;

        $pitchSearchData = Pitch::with(['pitchAvailability', 'pitchAvailability.pitchBreaks', 'venue'])
            ->where('pitches.tournament_id', $tournamentData['tournament_id']);

        if (isset($tournamentData['selectedVenue']) && $tournamentData['selectedVenue'] != '') {
            $selectVenue = true;
            $pitchSearchData->where('venue_id', '=', $tournamentData['selectedVenue']);
        }

        if (isset($tournamentData['pitchDataSearch']) && $tournamentData['pitchDataSearch'] != '') {
            $pitchSearchData->where('pitches.tournament_id', $tournamentData['tournament_id'])
                ->where(function ($query) use ($tournamentData) {
                    $query->where('pitches.pitch_number', 'like', '%'.$tournamentData['pitchDataSearch'].'%')
                        ->orWhereHas('venue', function ($query) use ($tournamentData) {
                            $query->where('name', 'like', '%'.$tournamentData['pitchDataSearch'].'%');
                        });
                });
        }

        return ['pitches' => $pitchSearchData->get()];
    }

    public function getVenuesDropDownData($tournamentData)
    {
        $pitchVenues = Venue::where('tournament_id', $tournamentData['tournament_id'])
            ->select('id', 'name')
            ->get();

        return ['venues' => $pitchVenues];
    }

    public function updatePitchOrder($pitchId, $order)
    {
        $updateData = ['order' => $order];

        return Pitch::where('id', $pitchId)->update($updateData);
    }
}
