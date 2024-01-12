<?php

namespace App\Api\Services;

use App\Api\Contracts\RefereeContract;
use App\Imports\RefereeImport;
use App\Traits\TournamentAccess;

class RefereeService implements RefereeContract
{
    use TournamentAccess;

    public function __construct()
    {
        $this->refereeRepoObj = new \App\Api\Repositories\RefereeRepository();
    }

    public function getAllReferees($tournamentData)
    {
        $referees=$this->refereeRepoObj->getAllReferees($tournamentData);
        return array('referees' => $referees);
    }

    /**
     * create New Referee.
     *
     * @param  [type]
     * @param  mixed  $data
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
     * @param  array  $data
     * @return [type]
     */
    public function edit($data, $refereeId)
    {
        // $data = $data->all();
        // dd($data,$refereeId);
        $data = $this->refereeRepoObj->edit($data, $refereeId);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Referee.
     *
     * @param  array  $data
     * @param  mixed  $deleteId
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
        $excelDataCheck = false;

        $reader = \Excel::toArray(new RefereeImport, $file);
        // Get the total rows of the file
        $sheets = $reader[0];
        $totalRows = count($sheets); 
        if (count($sheets) > 0) {
            
            $validationErrors=[];
            foreach ($sheets as $sheet) {
                // dd($sheet['firstname']);
                if(empty(trim($sheet['firstname']))) 
                {
                     $validationErrors['firstname'] = 'Please upload a sheet with valid firstname data.';
                     break; // Exit
                }
                if(empty(trim($sheet['lastname']))) 
                {
                     $validationErrors['lastname'] = 'Please upload a sheet with valid lastname data.';
                     break; // Exit
                }
               
                $sheet['tournamentId'] = $refereesData['tournamentId'];
                $allInSheet[] = $sheet;
                
            }

              // Check if there were any validation errors.
                if (! empty($validationErrors)) {
                    return ['status_code' => '422', 'message' => $validationErrors];
                }else{
                   foreach ($allInSheet as $sheet) {
                         $this->refereeRepoObj->uploadRefereesExcel($sheet);
                    }

                }
        }
    }        
}
