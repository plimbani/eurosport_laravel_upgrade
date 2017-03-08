<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\PitchResult;
use DB;

class PitchRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('pitch_results');
    }

    public function getAllPitches()
    {
        return PitchResult::all();
    }

    public function createPitch($pitchData)
    {
        return PitchResult::create($pitchData);
    }

    public function edit($data)
    {
        return PitchResult::where('id', $data['id'])->update($data);
    }

    public function getPitchFromId($pitchId)
    {
        return PitchResult::find($pitchId);
    }
}
