<?php

namespace App\Api\Services;

use DB;
use App\Api\Contracts\RefereeContract;
use Validator;
use App\Model\Role;

class RefereeService implements RefereeContract
{
    public function __construct()
    {
        $this->refereeRepoObj = new \App\Api\Repositories\RefereeRepository();
    }

    public function getAllReferees()
    {
        return $this->refereeRepoObj->getAllReferees();
    }

    public function createReferee($data)
    {
        $data = $data->all();
        $data = $this->refereeRepoObj->createReferee($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }
}
