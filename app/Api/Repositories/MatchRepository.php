<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\MatchResult;
use Laraspace\Models\Competition;
use Laraspace\Models\Fixture;

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
        return MatchResult::create($matchData);
    }

    public function edit($data)
    {
        return MatchResult::where('id', $data['id'])->update($data);
    }

    public function getMatchFromId($matchId)
    {
        return MatchResult::find($matchId);
    }

    public function getDraws($tournamentId) {
        return Competition::find($tournamentId)->get();
    }
    public function getFixtures($tournamentId) {
        return Fixture::find($tournamentId)->get();
    }
       
}
