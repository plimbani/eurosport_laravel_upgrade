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

    /**
     * create New Referee.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function createReferee($data)
    {
        $data = $data->all();
        $data = $this->refereeRepoObj->createReferee($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Match.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        $data = $this->refereeRepoObj->edit($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

     /**
      * Delete Referee.
      *
      * @param array $data
      * @param mixed $deleteId
      *
      * @return [type]
      */
     public function deleteReferee($deleteId)
     {
         $refereeRes = $this->refereeRepoObj->getRefereeFromId($deleteId)->delete();
         if ($refereeRes) {
             return ['code' => '200', 'message' => 'Referee Sucessfully Deleted'];
         }
     }
}
