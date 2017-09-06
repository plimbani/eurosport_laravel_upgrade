<?php

namespace Laraspace\Api\Services;

use DB;
use Laraspace\Api\Contracts\RefereeContract;
use Validator;
use Laraspace\Model\Role;

class RefereeService implements RefereeContract
{
    public function __construct()
    {
        $this->refereeRepoObj = new \Laraspace\Api\Repositories\RefereeRepository();
    }

    public function getAllReferees($tournamentData)
    {
        return $this->refereeRepoObj->getAllReferees($tournamentData);
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
        $data = $data->all()['data'];

        $dataResult = $this->refereeRepoObj->createReferee($data);
        if ($dataResult) {
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
    public function edit($data,$refereeId)
    {
        // $data = $data->all();
         // dd($data,$refereeId);
        $data = $this->refereeRepoObj->edit($data,$refereeId);
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
        $referee = $this->refereeRepoObj->getRefereeFromId($deleteId);
        $refereeRes = $referee->delete();
        if ($refereeRes) {
            return ['code' => '200', 'message' => 'Referee Sucessfully Deleted'];
        }
     }
}
