<?php

namespace App\Api\Repositories;

use DB;

class AgeGroupRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('age_groups');
    }

    public function getAll()
    {
        return $this->dbObj->get();
    }

    public function create($data)
    {
        return $this->dbObj->insert($data);
    }
}
