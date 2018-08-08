<?php

namespace Laraspace\Api\Services;

use DB;
use Laraspace\Api\Contracts\RefereeContract;
use Validator;
use Laraspace\Model\Role;
use Laraspace\Traits\TournamentAccess;

class RefereeService implements RefereeContract
{
    use TournamentAccess;
    
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

    /*
     * Upload referees
     *
     * @return response
     */  
    public function uploadRefereesExcel($data)
    {
        $refereesData = $data->all();
        $file = $data->file('fileUpload');
        $this->data['tournamentId'] = $refereesData['tournamentId'];
        $excelDataCheck = false;
        \Excel::load($file->getRealPath(), function($reader) use (&$excelDataCheck) {
            $this->data['totalSize']  = $reader->getTotalRowsOfFile() - 1;
            $reader->each(function($sheet) use (&$excelDataCheck) {
                if (array_has($sheet, 'firstname') && array_has($sheet, 'lastname')) {
                    $sheet->refereeData = $this->data;
                    return $this->refereeRepoObj->uploadRefereesExcel($sheet);
                } else {
                    $excelDataCheck = true;
                    return false;
                }
            });
        }, 'ISO-8859-1');
        if ($excelDataCheck) {
            return ['status_code' => '500', 'message' => 'Please upload proper data'];                
        }
    }
}
