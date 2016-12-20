<?php

namespace App\Api\Repositories;

use App\Models\Referee;
use DB;

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

    public function createReferee($refereeData)
    {
        return Referee::create($refereeData);
    }

    public function edit($data,$id)
    {
        return Referee::where('id', $id)->update($data);
    }

    public function getRefereeFromId($refereeId)
    {
        return Referee::findOrFail($refereeId);
    }
}
