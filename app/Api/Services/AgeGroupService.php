<?php

namespace App\Api\Services;

use App\Api\Contracts\AgeGroupContract;
use Validator;
use App\Api\Repositories\AgeGroupRepository;

class AgeGroupService implements AgeGroupContract
{
    public function __construct(AgeGroupRepository $ageRepoObj)
    {
        $this->ageGroupObj = $ageRepoObj;
    }

    public function getAllData()
    {
        // Here we send Status Code and Messages
        $data = $this->ageGroupObj->getAll();
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function create($data)
    {
        $data = $data->all();
        $data = $this->ageGroupObj->create($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    public function edit($data, $ageId)
    {
        $data = $data->all();
        $data = $this->ageGroupObj->edit($data, $ageId);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    public function delete($deleteId)
    {
        $matchRes = $this->ageGroupObj->getAgegroupFromId($deleteId)->delete();
        if ($matchRes) {
            return ['code' => '200', 'message' => 'AgeGroup has been deleted sucessfully'];
        }
    }
}
