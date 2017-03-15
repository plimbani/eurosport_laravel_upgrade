<?php

namespace Laraspace\Services;

use DB;
use Laraspace\Contracts\PitchContract;
use Validator;
use Laraspace\Model\Role;

class PitchService implements PitchContract
{
    public function __construct()
    {
        $this->pitchRepoObj = new \Laraspace\Repositories\PitchRepository();
    }

    public function getAllPitches()
    {
        return $this->pitchRepoObj->getAllPitches($tournamentId);
    }

    /**
     * create New Referee.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function createPitch($data)
    {
        $availability = '80';
        $pitchNumber = '12';
        $dataArr = [
            'name' => $data->input('pitch_name'),
            'pitch_number' => $pitchNumber,
            'type' => $data->input('pitch_type'),
            'venue_id' => $data->input('location'),
            'time_slot' => '30',
            'availability' => $availability,
        ];
        $dataRes = $this->pitchRepoObj->createPitch($dataArr);
        if ($dataRes) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted', 'pitch_id' => $dataRes->id];
        }
    }

    /**
     * Edit Match.
     *
     * @param array $data
     * @param mixed $id
     * @param mixed $pitchId
     *
     * @return [type]
     */
    public function editPitch($data, $pitchId)
    {
        $pitchNumber = '1234';
        $availability = '80';
        $data = [
            'name' => $data->input('pitch_name'),
            'pitch_number' => $pitchNumber,
            'type' => $data->input('pitch_type'),
            'venue_id' => '1',
            'time_slot' => $data->input('time_slot'),
            'availability' => $availability,
        ];
        $data = $this->pitchRepoObj->edit($data, $pitchId);

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated', 'pitch_id' => $pitchId];
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
     public function deletePitch($deleteId)
     {
         $pitchRec = $this->pitchRepoObj->getPitchFromId($deleteId);
         if ($pitchRec) {
             $pitchRes = $pitchRec->delete();
             if ($pitchRes) {
                 return ['code' => '200', 'message' => 'Pitch has been deleted sucessfully'];
             }
         }

         return ['code' => '400', 'message' => 'Something goes wrong'];
     }

    public function getPitchById($pitchId)
    {
        return $this->pitchRepoObj->getPitchFromId($pitchId);
    }
}
