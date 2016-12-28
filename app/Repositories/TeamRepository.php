<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository
{
    public function getAll()
    {
        return Team::get();
    }

    public function create($data)
    {
        return Team::create($data);
    }

    public function edit($data)
    {
        return Team::where('id', $data['id'])->update($data);
    }

    public function delete($data)
    {
        return Team::find($data['id'])->delete();
    }
}
