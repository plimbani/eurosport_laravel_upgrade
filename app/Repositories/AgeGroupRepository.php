<?php

namespace App\Repositories;

use App\Models\AgeGroup;

use DB;

class AgeGroupRepository
{
    public function getAll()
    {
        return AgeGroup::get();
    }

    public function create($data)
    {
        return AgeGroup::create($data);
    }

    public function delete($data)
    {
        return AgeGroup::find($data['id'])->delete();
    }

    public function edit($data, $ageId)
    {
        return AgeGroup::where('id', $ageId)->update($data);
    }

    public function getAgegroupFromId($ageId)
    {
        return AgeGroup::find($ageId);
    }
}
