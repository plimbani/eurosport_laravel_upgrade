<?php

namespace App\Repositories;

use DB;
use App\Models\Referee;

class RefereeRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('referee');
    }

    public function getAllReferees()
    {
        return Referee::all();
    }

    public function createReferee1($refereeData)
    {
        dd('hi');

        return Referee::create($refereeData);
    }

    public function edit($data, $refereeId)
    {

        return Referee::where('id', $refereeId)->update($data);
    }

    public function getRefereeFromId($refereeId)
    {
        return Referee::findOrFail($refereeId);
    }
}
