<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Tournament;

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

    public function edit($data)
    {
        return Tournament::where('id', $data['id'])->update($data);
    }

    public function delete($data)
    {
        return Tournament::find($data['id'])->delete();
    }
}
