<?php

namespace App\Api\Repositories;

use App\Models\MatchResult;
use DB;

class MatchRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('match_results');
    }

    public function getAllMatches()
    {
        return MatchResult::all();
    }

    public function createMatch($matchData)
    {
        return $this->dbObj->insert($matchData);
    }

    public function getMatchFromId($matchId)
    {
        return MatchResult::find($matchId);
    }
}
