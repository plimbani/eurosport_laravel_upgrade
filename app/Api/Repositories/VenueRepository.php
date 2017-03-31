<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Venue;
use DB;

class VenueRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('venues');
    }

    public function getAllVenues($tournamentId)
    {
        return Venue::where('tournament_id',$tournamentId)->get();
    }

    
}
