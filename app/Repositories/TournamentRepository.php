<?php

namespace Laraspace\Repositories;

use Laraspace\Models\Tournament;
use Carbon\Carbon;

class TournamentRepository
{
    public function getAll()
    {
        return Tournament::get();
    }

    public function create($tournamentData)
    {
        // Save Tournament Data arrage it

        $startDate = Carbon::createFromFormat('m/d/Y', $tournamentData['tournaments_start_date']);

 
        $data['name'] = $tournamentData['tournaments_name'];
        $data['start_date'] = $startDate;
        $data['website'] = $tournamentData['tournaments_website'];
        $data['facebook'] = $tournamentData['tournaments_facebook'];
        $data['twitter'] = $tournamentData['tournaments_twitter'];
        $data['end_date'] = Carbon::createFromFormat('d/m/Y', $tournamentData['tournaments_start_date'])->addDays($tournamentData['tournament_days']);
        $data['user_id'] = \Auth::id();

        // Todo: Set Default Temporary Values
        $data['no_of_pitches'] = 0;
        $data['no_of_match_per_day_pitch'] = 0;
        $data['points_per_match_win'] = 0;
        $data['points_per_match_tie'] = 0;
        $data['points_per_bye'] = 0;

        return Tournament::create($data);
    }

    public function edit($data, $tournamentId)
    {
        return Tournament::where('id', $tournamentId)->update($data);
    }

    public function getTournamentFromId($tournamentId)
    {
        return Tournament::find($tournamentId);
    }
}
