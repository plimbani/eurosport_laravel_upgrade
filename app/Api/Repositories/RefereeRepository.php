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
        return $this->dbObj->insert($refereeData);
    }

    public function getRefereeFromId($refereeId)
    {
        return Referee::find($refereeId);
    }
}
