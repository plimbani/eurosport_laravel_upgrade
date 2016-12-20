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

    public function edit($data,$id)
    {
        return Tournament::where('id', $id)->update($data);
    }

    public function getTournamentFromId($id)
    {
        return Tournament::find($id);
    }
}
