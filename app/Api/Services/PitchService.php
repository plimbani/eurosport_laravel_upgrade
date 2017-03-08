<?php

namespace Laraspace\Api\Services;

use DB;
use Laraspace\Api\Contracts\PitchContract;
use Validator;
use Laraspace\Model\Role;

class PitchService implements PitchContract
{
    public function __construct()
    {
        $this->pitchRepoObj = new \Laraspace\Api\Repositories\PitchRepository();
    }

    public function getAllPitches()
    {
        return $this->pitchRepoObj->getAllPitches();
    }

    /**
     * create New Pitch.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function createPitch($data)
    {
        $data = $data->all();
        $data = $this->pitchRepoObj->createPitch($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Pitch.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        $data = $this->pitchRepoObj->edit($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Pitch.
     *
     * @param array $data
     * @param mixed $deleteId
     *
     * @return [type]
     */
    public function deletePitch($deleteId)
    {
        $pitchRes = $this->pitchRepoObj->getPitchFromId($deleteId)->delete();
        if ($pitchRes) {
            return ['code' => '200', 'message' => 'Pitch Sucessfully Deleted'];
        }
    }
}
