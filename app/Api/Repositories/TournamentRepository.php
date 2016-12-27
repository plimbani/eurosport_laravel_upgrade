<?php

namespace App\Api\Repositories;

use App\Models\Tournament;

class TournamentRepository
{
    public function getAll()
    {
        return Tournament::get();
    }

    public function create($data)
    {
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
