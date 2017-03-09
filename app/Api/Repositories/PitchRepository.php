<?php

namespace Laraspace\Api\Repositories;

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
        // dd($pitchData);

        return Pitch::create([
            'name' => $pitchData['number'],
            'pitch_number' => $pitchData['number'],
            'type' => $pitchData['type'],
            'venue_id' => '1',
            'size' => $pitchData['Size'],
        ]);
    }

    public function edit($data)
    {
        return Pitch::where('id', $data['id'])->update($data);
    }

    public function getPitchFromId($pitchId)
    {
        return Pitch::find($pitchId);
    }
}
