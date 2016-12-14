<?php

namespace App\Api\Repositories;

use DB;

class TournamentRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('tournaments');
    }

    public function getAllTournaments()
    {
        return $this->dbObj->get();
    }

    public function createTournament($data)
    {
        return $this->dbObj->insert($data);
    }
}
