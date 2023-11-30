<?php

namespace App\Api\Repositories;

use DB;
use App\Models\Venue;

class VenueRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('venues');
    }

    public function getAllVenues($tournamentId)
    {
        return Venue::orderBy('name', 'ASC')->where('tournament_id', $tournamentId)->get();
    }
}
