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

    public function getPitchSizeWiseSummary($tournamentId)
    {
        return $this->pitchRepoObj->getPitchSizeWiseSummary($tournamentId);
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

        // dd($dataArr,$pitchId);
        $pitchdata = $this->pitchRepoObj->edit($dataArr,$pitchId);

        if($pitchdata){
            $this->pitchAvailableRepoObj->removePitchAvailability($pitchId);
            $data1 = $this->pitchAvailableRepoObj->createPitch($dataArr, $pitchId);

            $this->unScheduleAllocatedMatch($dataArr,$pitchId);
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
        $this->unScheduleAllocatedMatch('',$deleteId);
        $pitchRes = $this->pitchRepoObj->getPitchFromId($deleteId)->delete();
        if ($pitchRes) {
            return ['code' => '200', 'message' => 'Pitch Sucessfully Deleted'];
        }
    }

    // Function to check if any match in between that Stage Schedule
    // if it is then unschedule it
    private function unScheduleAllocatedMatch($dataArr='',$pitchId)
    {

        // find matches where not in between that time details in temp_fixtures
        if(empty($dataArr)) {
            $pitch = DB::table('pitches')
                    ->where('pitches.id','=',$pitchId)->get();
           // $dataArr['tournamentId'] = $pitch[0]->tournament_id;
            $matches = DB::table('temp_fixtures')
                ->where('temp_fixtures.pitch_id','=',$pitchId)
                ->where('temp_fixtures.is_scheduled','=',1)->get();
            $matchIdArray = [];
            foreach ($matches as $match) {
                $matchIdArray[] = $match->id;
            }
            $unScheduleArray = [
              'is_scheduled' => 0
            ];
            $update_match = DB::table('temp_fixtures')
                        ->whereIn('temp_fixtures.id',$matchIdArray)
                        ->update($unScheduleArray);
            return true;
        }

        $pitches = DB::table('pitch_availibility')
                ->where('pitch_availibility.pitch_id','=',$pitchId)
                ->where('pitch_availibility.tournament_id','=',$dataArr['tournamentId'])->get();
        $matches = DB::table('temp_fixtures')
                ->where('temp_fixtures.pitch_id','=',$pitchId)
                ->where('temp_fixtures.is_scheduled','=',1)->get();

     foreach ($pitches as $Stagekey=>$stage) {

       $stage_start_date_time = $stage->stage_start_date.' '.$stage->stage_start_time;
       $stage_end_date_time = $stage->stage_end_date.' '.$stage->stage_end_time;
       if($stage->break_enable == true) {

                  $stage_break_start_date_time = $stage->stage_continue_date.' '.$stage->break_start_time;
                  $stage_break_end_date_time = $stage->stage_continue_date.' '.$stage->break_end_time;

             $stage_break_start_date_time =date("Y-m-d H:i:s",strtotime($stage_break_start_date_time));
             $stage_break_end_date_time =date("Y-m-d H:i:s",strtotime($stage_break_end_date_time));
        }

          $stage_start_date_time =  date("Y-m-d H:i:s",strtotime($stage_start_date_time));
          $stage_end_date_time =  date("Y-m-d H:i:s",strtotime($stage_end_date_time));
      foreach ($matches as $matchKey=>$match) {
          //echo '2';exit;
                $matchStartDateTime  = $match->match_datetime;
                $matchEndDateTime  = $match->match_endtime;
                /*echo '<br>StageKey:'.$Stagekey;
                echo '<br>MatchKey:'.$matchKey;
                echo '<br>StageStartDateTime:'.$stage_start_date_time;
                echo '<br>StageStartEndTime:'.$stage_end_date_time;
                echo '<br>MatchStartDateTime:'.$matchStartDateTime;*/

                echo 'stagedate:'.$stageDate = date("Y-m-d",strtotime($stage->stage_start_date));
                echo '<br>mtchdate:'.$matchDate = date("Y-m-d",strtotime($match->match_datetime));

                echo '<br>matchDateTime'.$matchStartDateTime;
                // Must have Same Date
                if($stageDate== $matchDate) {
                  echo '<br>Same';
                  if(!$this->check_date_is_within_range( $stage_start_date_time,$stage_end_date_time,$matchStartDateTime)){
                      echo '<br>Outer RANGE:'.$match->id;
                      $update_match = DB::table('temp_fixtures')
                        ->where('temp_fixtures.id','=',$match->id)
                        ->update(['is_scheduled' => 0]);
                    }
                    // Also check for match fall into break
                    if($stage->break_enable == 1) {
                      echo '<br>breakStartTime'.$stage_break_start_date_time;
                      echo '<br>breakEndtime'.$stage_break_end_date_time;
                      echo '<br>matchtime'.$matchStartDateTime;

                      // if it is in that range then unschedule it
                      if($this->check_date_is_within_range( $stage_break_start_date_time,$stage_break_end_date_time,$matchStartDateTime)){
                      echo '<br>Outer RANGE break:'.$match->id;
                      $update_match = DB::table('temp_fixtures')
                        ->where('temp_fixtures.id','=',$match->id)
                        ->update(['is_scheduled' => 0]);
                     } else {
                      echo '<br>not brk';
                     }
                    }
                }

                // if its schedule earlier then change pitch allocation
               /* if ($matchStartDateTime > $stage_start_date_time && $matchStartDateTime < $stage_end_date_time ) {
                    $update_match = DB::table('temp_fixtures')
                        ->where('temp_fixtures.id','=',$match->id)
                        ->update(['is_scheduled' => 0]);
                }
                if ($matchEndDateTime > $stage_start_date_time && $matchEndDateTime < $stage_end_date_time )
                {
                    $update_match = DB::table('temp_fixtures')
                        ->where('temp_fixtures.id','=',$match->id)
                        ->update(['is_scheduled' => 0]);
                } */
            }
        }
       // exit;
    }
    private function check_date_is_within_range($start_date, $end_date, $todays_date)
    {
      $start_timestamp = strtotime($start_date);
      $end_timestamp = strtotime($end_date);
      $today_timestamp = strtotime($todays_date);
      return (($today_timestamp >= $start_timestamp) && ($today_timestamp <= $end_timestamp));
    }
}
