<?php

namespace Laraspace\Repositories;

use Laraspace\Models\Pitch;
use DB;

class PitchRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('pitches');
    }

    public function getAllPitches()
    {
        return Pitch::all();
    }

    public function createPitch($pitchData)
    {
        return Pitch::create($pitchData);
    }

    public function edit($data, $pitchId)
    {
        return Pitch::where('id', $pitchId)->update($data);
    }

    public function getPitchFromId($pitchId)
    {
        return Pitch::find($pitchId);
    }
}
