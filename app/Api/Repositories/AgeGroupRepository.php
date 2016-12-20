<?php

namespace App\Api\Repositories;

use App\Models\AgeGroup;
use DB;

class AgeGroupRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('age_groups');
    }

    public function getAll()
    {
        return AgeGroup::all();
    }

    public function create($data)
    {
        return AgeGroup::create($data);
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
