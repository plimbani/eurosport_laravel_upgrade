<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Referee;
use DB;

class RefereeRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('referee');
    }

    public function getAllReferees($tournamentId)
    {
        return Referee::where('tournament_id',$tournamentId)->get();
    }

    public function createReferee($refereeData)
    {
        return Referee::create($refereeData);
    }

    public function edit($data,$refereeId)
    {
        return Referee::where('id', $refereeId)->update($data);
    }

    public function getRefereeFromId($refereeId)
    {
        // dd(Referee::find($refereeId));
        return Referee::find($refereeId);
    }
}
