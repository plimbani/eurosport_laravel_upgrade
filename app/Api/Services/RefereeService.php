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
     * @param mixed $id
     * @param mixed $refereeId
     *
     * @return [type]
     */
    public function edit($data, $refereeId)
    {
        $data = $data->all();
        $data = $this->refereeRepoObj->edit($data, $refereeId);
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
         $refreeRec = $this->refereeRepoObj->getRefereeFromId($deleteId);
         if ($refreeRec) {
             $refereeRes = $refreeRec->delete();
             if ($refereeRes) {
                 return ['code' => '200', 'message' => 'Referee has beeb deleted sucessfully'];
             }
         } else {
             return ['code' => '400', 'message' => 'Something goes wrong'];
         }
     }
}
