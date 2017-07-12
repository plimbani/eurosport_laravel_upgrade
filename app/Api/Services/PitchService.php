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
        $this->pitchAvailableRepoObj = new \Laraspace\Api\Repositories\PitchAvailableRepository();
    }

    public function getAllPitches($tournamentId)
    {
        return $this->pitchRepoObj->getAllPitches($tournamentId);
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
        $dataArr = $data->all();

        $pitchdata = $this->pitchRepoObj->createPitch($dataArr);
        // dd($pitchdata->id);
        // $data['pitchId'] = $pitchdata->id;

        if($pitchdata){
            $data1 = $this->pitchAvailableRepoObj->createPitch($dataArr, $pitchdata->id);

        }
        if ($data1) {
            return ['code' => '200','pitchId'=>$pitchdata->id, 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Pitch.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data,$pitchId)
    {

        $dataArr = $data->all();
        // Call function to check if particular pitch has some schedule matches in that time
        // if its have it then unschedule it

        // dd($dataArr);
        $pitchdata = $this->pitchRepoObj->edit($dataArr,$pitchId);
        $this->unScheduleAllocatedMatch($dataArr,$pitchId);
        if($pitchdata){
            $this->pitchAvailableRepoObj->removePitchAvailability($pitchId);
            $data1 = $this->pitchAvailableRepoObj->createPitch($dataArr, $pitchId);
        }
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    public function getPitchData($pitchId)
    {
        $pitchdata = $this->pitchRepoObj->getPitchData($pitchId);
        if($pitchdata){
            $pitchAvailable = $this->pitchAvailableRepoObj->getPitchData($pitchId);

        }
        $data = ['pitchdetail' => $pitchdata,'pitchAvailable' => $pitchAvailable];
        if ($pitchdata) {
           return ['status_code' => '200', 'data' => $data, 'message' => 'Data Successfully Updated'];
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
    private function unScheduleAllocatedMatch($dataArr,$pitchId)
    {
        // find matches where not in between that time details in temp_fixtures
        $pitches = DB::table('pitch_availibility')
                ->where('pitch_availibility.pitch_id','=',$pitchId)
                ->where('pitch_availibility.tournament_id','=',$dataArr['tournamentId'])->get();
        $matches = DB::table('temp_fixtures')
                ->where('temp_fixtures.pitch_id','=',$pitchId)
                ->where('temp_fixtures.is_scheduled','=',1)->get();
        foreach ($pitches as $stage) {
            foreach ($matches as $match) {
                $stage_start_date_time = $stage->stage_start_date.' '.$stage->stage_start_time;
                $stage_end_date_time = $stage->stage_end_date.' '.$stage->stage_end_time;

                $matchStartDateTime = $match->match_datetime;
                $matchEndDateTime = $match->match_endtime;
                // if its schedule earlier then change pitch allocation
                if ($matchStartDateTime < $stage_start_date_time ) {
                    $update_match = DB::table('temp_fixtures')
                        ->where('temp_fixtures.id','=',$match->id)
                        ->update(['is_scheduled' => 0,'match_datetime'=>null,'match_endtime'=>null]);
                }
                if($matchEndDateTime > $stage_end_date_time)
                {
                    $update_match = DB::table('temp_fixtures')
                        ->where('temp_fixtures.id','=',$match->id)
                        ->update(['is_scheduled' => 0,'match_datetime'=>null,'match_endtime'=>null]);
                }
            }
        }
    }
}
