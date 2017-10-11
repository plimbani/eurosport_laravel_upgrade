<?php
namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\TournamentContract;
use Laraspace\Api\Repositories\TournamentRepository;
use DB;
use Carbon\Carbon;
use PDF;
use Laraspace\Models\Venue;
use Laraspace\Models\Team;
use Laraspace\Models\Tournament;
use Validate;
use JWTAuth;
use Laraspace\Models\User;
use View;

class TournamentService implements TournamentContract
{
    /**
     *  Messages To Display.
     */
    const SUCCESS_MSG = 'Data Sucessfully inserted';
    const ERROR_MSG = 'Error in Data';

    public function __construct(TournamentRepository $tournamentRepoObj)
    {
        $this->tournamentRepoObj = $tournamentRepoObj;
        $this->getAWSUrl = getenv('S3_URL');
        $this->tournamentLogo =  getenv('S3_URL').'/assets/img/tournament_logo/';
    }

     /*
     * Get All Tournaments
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index()
    {
        // Here we send Status Code and Messages
        $isMobileUsers = \Request::header('IsMobileUser');
        if( $isMobileUsers != '') {
          $data = $this->tournamentRepoObj->getAll('published', null);
        }
        else {
          $token=JWTAuth::getToken();
          $user = null;
          if($token)
          {
            $authUser = JWTAuth::parseToken()->toUser();
            $userObj = User::find($authUser->id);
            if($authUser && $userObj->hasRole('tournament.administrator')) {
              $user = $userObj;
            }
          }
          $data = $this->tournamentRepoObj->getAll('', $user);
        }

        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => self::ERROR_MSG];
    }

    /*
     * Get Filter Data
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function tournamentFilter($data)
    {
        // Here we send Status Code and Messages
        $data = $this->tournamentRepoObj->tournamentFilter($data);

        if ($data) {
          return ['status_code' => '200', 'data' => $data];
        } else {
          return ['status_code' => '505', 'message' => 'No Data Found'];;
        }
    }
    /*
     * Get All Tournaments By Status
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTournamentByStatus($data)
    {
        // Here we send Status Code and Messages
        $tournamentData = $data->all();
        $data = $this->tournamentRepoObj->getTournamentsByStatus($tournamentData['tournamentData']);

        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => self::ERROR_MSG];
    }

    /*
     * Get All Templates
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function templates($data=array())
    {
        // Here we send Status Code and Messages
        $data1 = $this->tournamentRepoObj->getAllTemplatesFromMatches($data);

        if ($data1) {
          //TODO: here we Add Some Extra Fields For merge with TemplateData
          $newData=array();


          foreach($data1 as $key=>$value){

            // Now here we call a function
            $newData[$key]=$value;
            $jsonVal = $value->json_data;

            list($totalTime,$totalmatch,$dispFormatname,$template_font_color,$remark,
              $avg_game_team) = $this->calculateTime($data['tournamentData'],$value);
            $newData[$key]['total_time'] = $totalTime;
            $newData[$key]['total_match'] = $totalmatch;
            $newData[$key]['disp_format'] = $dispFormatname;
            $newData[$key]['template_font_color'] = $template_font_color;
            $newData[$key]['remark'] = $remark;
            $newData[$key]['avg_game_team'] = $avg_game_team;
          }


          //exit;
          return ['status_code' => '200', 'data' => $newData];
        }

        return ['status_code' => '505', 'message' => self::ERROR_MSG];
    }
    private function calculateTime($data,$jsonData) {
        // We calculate the Following over here
        // Total Time
        // Total Match
        // display Format Name
        if($data['game_duration_RR'] == 'other') {
          $data['game_duration_RR'] = 2 * $data['game_duration_RR_other'];
        }
        if($data['game_duration_FM'] == 'other') {
          $data['game_duration_FM'] = 2 * $data['game_duration_FM_other'];
        }
        if($data['match_interval_RR'] == 'other') {
          $data['match_interval_RR'] = $data['match_interval_RR_other'];
        }
        if($data['match_interval_FM'] == 'other') {
          $data['match_interval_FM'] = $data['match_interval_FM_other'];
        }

        $jsonData = $jsonData->json_data;

        $json_data = json_decode($jsonData);
        if(!$json_data){
          echo 'Problem in Template';
          print_r($jsonData);
         exit;
        }


        $disp_format_name = $json_data->tournament_teams .' teams: '.
        $json_data->competition_group_round.' - '.$json_data->competition_round;

        $total_matches = $json_data->total_matches;

        // Now here we calculate total time for a Compeation format For RR
        // Move For loop and take count -1 for round robin
        $totalRound = count($json_data->tournament_competation_format->format_name);
        $total_rr_time = 0; $total_final_time=0;$total_time=0;
        // we use -1 loop for only consider round robin matches
        // TODO: We change logic to Only Consider final Matches

        if($json_data->competition_round == 'F') {
          // Its Final Round
          $roundFinal = 1;
        } else {
          $roundFinal = 0;
        }
        for($i=0;$i<$totalRound-$roundFinal;$i++){
            // Now here we calculate followng fields
            $rounds = $json_data->tournament_competation_format->format_name[$i]->match_type;
            // Now here we have to for loop for match_type

            foreach($rounds as $round) {
               $total_round_match = $round->total_match;
               // Calculate Game Duration for RR
               $total_rr_time+= $data['game_duration_RR'] * $total_round_match;
               // Calculate  half Time Break for RR
               $total_rr_time+= $data['halftime_break_RR'] * $total_round_match;
              // Calculate Match Interval
               $total_rr_time+= $data['match_interval_RR'] * $total_round_match;
           }

        }

        // Now we calculate final match time
        if($json_data->competition_round == 'F')
        {
          $final_round = array_pop($json_data->tournament_competation_format->format_name);

          // we know that we have only one Final Round Over here
          $total_final_match = $final_round->match_type[0]->total_match;

          $total_final_time  = $data['game_duration_FM']  * $total_final_match;
          $total_final_time += $data['halftime_break_FM'] * $total_final_match;
          $total_final_time += $data['match_interval_FM'] * $total_final_match;

        } else {
          $total_final_time  = 0;
        }
        // Now we sum up round robin and final match
        $total_time = $total_rr_time + $total_final_time;

        // Todo : Add font Color For this template
        $template_font_color = $json_data->template_font_color;

        // Todo: add remark option

        $remark =  (isset($json_data->remark)) ? $json_data->remark : '';
        // TODO: add avg_game_team
        // Added below json decode to not affect above code and it is due to round issue of average team
        $string_json_data = json_decode(preg_replace('/:\s*(\-?\d+(\.\d+)?([e|E][\-|\+]\d+)?)/', ': "$1"', $jsonData));
        $avg_game_team = (isset($string_json_data->avg_game_team)) ? $string_json_data->avg_game_team : '';
        return array($total_time,$total_matches,$disp_format_name, $template_font_color,$remark,$avg_game_team);
    }

    /*
     * Get Json Data For Template
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTemplate($data)
    {
       // Here we send Status Code and Messages
        $data = $data['tournamentTemplateId'];
        $data = $this->tournamentRepoObj->getTemplate($data);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => self::ERROR_MSG];
    }

    /**
     * create New Tournament.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function create($data)
    {

         //exit;
        $data = $data->all();

        // here first we save the tournament related Data
        // here we have to precprocess the image
        // Save the image
        $id = ($data['tournamentData']['tournamentId'] !=0 || $data['tournamentData']['tournamentId'] !=0) ? $data['tournamentData']['tournamentId']:'';

        $data['tournamentData']['image_logo']=$this->saveTournamentLogo($data,$id);

        //\File::put($path , $imgData);
        //print_r($imgData);

        $resultData = $this->tournamentRepoObj->create($data['tournamentData']);

        $this->getCoordinates($resultData);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
             'data'=>$resultData];
        }
    }
    private function saveTournamentLogo($data, $id='')
    {
       if($data['tournamentData']['image_logo'] != '')
       {
            // here we check using preg_replace that if its change image or not
            if(strpos($data['tournamentData']['image_logo'],$this->getAWSUrl) !==  false) {
              $path = $this->getAWSUrl.'/assets/img/tournament_logo/';
              $imageLogo = str_replace($path,"",$data['tournamentData']['image_logo']);
              return $imageLogo;
            }
            $s3 = \Storage::disk('s3');
            $imagePath = '/assets/img/tournament_logo/';
            // here we check it for edit purpose and if image is
            // already there we will return it
            //if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/assets/img/tournament_logo/'.$data['tournamentData']['image_logo']))


              // $imagename = $data['user_image'];
              //exit;
            $image_string = $data['tournamentData']['image_logo'];

            $img = explode(',', $image_string);
            if(count($img)>1) {

                $imgData = base64_decode($img[1]);
            }else{
                return '';
            }

            $name = $data['tournamentData']['name'];

            if($id == '') {
              $now = new \DateTime();
              $timeStamp = $now->getTimestamp();
            } else {
              $timeStamp = $id;
            }

            //$now = new \DateTime();

            //$timeStamp = $now->getTimestamp();

            $path = $imagePath.$timeStamp.'.png';
            $s3->put($path, $imgData);
            //file_put_contents($path, $imgData);

            // Resize image to 100*100

            // TODO: Need to add code for Resize
            //$img = \Image::make($imgData)->resize(250, 250);
            // Save it
            //$img->save($path);
            //$s3->put($path, $img->save());

            return $timeStamp.'.png';


        } else {
            // If its Edit
            return '';
        }
    }
    /**
     * Edit Tournament.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        // dd($data);
        // here first we save the tournament related Data
        // here we have to precprocess the image
        // Save the image
       // $this->saveTournamentLogo($data);

        //\File::put($path , $imgData);
        //print_r($imgData);

        $data = $this->tournamentRepoObj->edit($data['tournamentData']);

        $this->getCoordinates($data['tournamentData']['id']);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
             'data'=>$data];
        }
    }

    /**
     * Delete Tournament.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function delete($tournamentId)
    {
        $data = $this->tournamentRepoObj->delete($tournamentId);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
    public function tournamentSummary($data)
    {
        $data = $data->all();
        $tournamentData = $this->tournamentRepoObj->tournamentSummary($data['tournamentId']);
        if ($tournamentData) {
            return ['status_code' => '200', 'data'=>$tournamentData];
        }
    }

    public function generateReport($data)
    {
        // dd($data);
        // $data=$data['data'];
         // dd($data);

        $reportQuery = DB::table('temp_fixtures')
            // ->Join('tournament', 'fixture.tournament_id', '=', 'tournament.id')
            ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
            ->leftjoin('teams as home_team', function ($join) {
                $join->on('home_team.id', '=', 'temp_fixtures.home_team');
            })
            ->leftjoin('teams as away_team', function ($join) {
                $join->on('away_team.id', '=', 'temp_fixtures.away_team');
            })
            ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
            ->leftjoin('countries as HomeFlag', 'home_team.country_id', '=',
                'HomeFlag.id')
            ->leftjoin('countries as AwayFlag', 'away_team.country_id', '=',
                'AwayFlag.id')
            ->leftjoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
            ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
            ->leftjoin('referee', 'referee.id', '=', 'temp_fixtures.referee_id')
            ->groupBy('temp_fixtures.id')
            ->select('temp_fixtures.id as fid','temp_fixtures.match_datetime','tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.last_name as referee_last_name',
              'home_team.name as HomeTeam','away_team.name as AwayTeam',
              'HomeFlag.country_flag as HomeCountryFlag',
              'AwayFlag.country_flag as AwayCountryFlag',
              'home_team.name as HomeTeam','away_team.name as AwayTeam',
               DB::raw('CONCAT("'.$this->getAWSUrl.'", HomeFlag.logo) AS HomeFlagLogo'),
                DB::raw('CONCAT("'.$this->getAWSUrl.'", AwayFlag.logo) AS AwayFlagLogo'),
              'referee.first_name as referee_first_name',
              DB::raw('CONCAT(referee.last_name,",",referee.first_name) as refereeFullName'),
              DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game'))
            ->where('temp_fixtures.tournament_id',$data['tournament_id'])
            ->where('temp_fixtures.is_scheduled',1);


            if(isset($data['sel_ageCategory'])  && $data['sel_ageCategory']!= ''){
                $reportQuery->where('tournament_competation_template.id',$data['sel_ageCategory']);
            }
            // if(isset($data['sel_clubs'])  && $data['sel_clubs']!= ''){
            //   $reportQuery->where('home_team.club_id',$data['sel_clubs'])->orWhere('away_team.club_id',$data['sel_clubs']);
            // }
            // if(isset($data['start_date'])  && $data['start_date']!= '' ){

            //   $start_date = Carbon::createFromFormat('d/m/Y', $data['start_date']);
            //   $reportQuery = $reportQuery->where('temp_fixtures.match_datetime','>=',$start_date);
            // }
            // if(isset($data['end_date'])  && $data['end_date']!= '' ){
            //   $reportQuery = $reportQuery->where('temp_fixtures.match_datetime','<=',Carbon::createFromFormat('d/m/Y', $data['end_date']));
            // }
            if(isset($data['start_date'])  && $data['start_date']!= '' ){

              $start_date = Carbon::createFromFormat('d/m/Y', $data['start_date'])->toDateString();
             // echo  $start_date;
              $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime','>=',$start_date);
            }
            if(isset($data['end_date'])  && $data['end_date']!= '' ){
              //echo  '<br>'.Carbon::createFromFormat('d/m/Y', $data['end_date']);

              $end_date = Carbon::createFromFormat('d/m/Y', $data['end_date'])->toDateString();
            //  echo $end_date;
              $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime','<=',$end_date);
            }

            // print_r($reportQuery->toSql());exit();
            // print_r($reportQuery->toSql());exit();
            if(isset($data['start_time'])  && $data['start_time']!= '' ){
              // $start_time = Carbon::createFromFormat('hh:mm', $data['start_time']);
              $start_time = $data['start_time'];
              // print_r($start_time); exit();
              // $start_time = '09:00';
              $reportQuery = $reportQuery->whereRaw("TIME(temp_fixtures.match_datetime) >= '$start_time'");

            }
            if(isset($data['end_time'])  && $data['end_time']!= '' ){
              $end_time = $data['end_time'];
              $reportQuery = $reportQuery->whereRaw("TIME(temp_fixtures.match_datetime) <= '$end_time'");
            }

            if(isset($data['sel_venues'])  && $data['sel_venues']!= '' ){
                $reportQuery = $reportQuery->where('temp_fixtures.venue_id',$data['sel_venues']);
            }
             if(isset($data['sel_clubs']) && $data['sel_clubs'] !== '' )
          {
             $club_id =$data['sel_clubs'];
             $tournamentId = $data['tournament_id'];
             $getTeamId = Team::where('club_id','=',$club_id)->where('tournament_id','=',$tournamentId)->pluck('teams.id')->toArray();

            $reportQuery =  $reportQuery->whereIn('temp_fixtures.home_team',$getTeamId)
                ->orWhereIn('temp_fixtures.away_team',$getTeamId);
            //$reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$tournamentData['pitchId']);
          }
            if(isset($data['sel_teams'])  && $data['sel_teams']!= '' ){
             // echo $data['sel_teams'];
                //$reportQuery = $reportQuery->where('temp_fixtures.home_team',$data['sel_teams'])
                  //          ->orWhere('temp_fixtures.away_team',$data['sel_teams']);
             // $reportQuery = $reportQuery->where(DB::raw('temp_fixtures.home_team = '.$data['sel_teams'].'or temp_fixtures.away_team = '.$data['sel_teams']));
            // echo $data['sel_teams'];
             $team = $data['sel_teams'];
              $reportQuery = $reportQuery->where(function ($query) use($team)
                              {
                                $query->where('temp_fixtures.home_team',$team)
                                ->orWhere('temp_fixtures.away_team',$team);
                              }
                            );
            }
            if(isset($data['sel_pitches'])  && $data['sel_pitches']!= '' ){
                $reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$data['sel_pitches']);
            }
            if(isset($data['sel_referees'])  && $data['sel_referees']!= '' ){

                $reportQuery = $reportQuery->where('temp_fixtures.referee_id', '=',$data['sel_referees']);
            }
            if(isset($data['sort_by']) && $data['sort_by'] != '') {
              switch($data['sort_by']) {
                  case 'match_datetime':
                        $fieldName = 'temp_fixtures.match_datetime';
                        break;
                  case 'group_name':
                        $fieldName = 'group_name';
                        break;
                  case 'venue_name':
                        $fieldName = 'venues.venue_name';
                        break;
                  case 'pitch_number':
                        $fieldName = 'pitches.pitch_number';
                        break;
                  case 'referee':
                        $fieldName = 'refereeFullName';
                        break;
                  case 'HomeTeam':
                        $fieldName = 'HomeTeam';
                        break;
                  case 'AwayTeam':
                        $fieldName = 'AwayTeam';
                        break;
              }

             // $sortOrder = (isset($data['sort_order']) && $data['sort_order'] != '') ? 'asc' : 'desc';
              $reportQuery = $reportQuery->orderBy($fieldName, $data['sort_order']);
             // echo 'SQL IS';
           //   print_r($reportQuery->toSql());
            }
            // $reportQuery = $reportQuery->select('fixtures.id as fid','fixtures.match_datetime','tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name',DB::raw('CONCAT(fixtures.home_team, " vs ", fixtures.away_team) AS full_game'));
        // echo $reportQuery->toSql();exit;
        $reportData = $reportQuery->get();
        //echo $reportQuery->toSql();exit;
         // $tournamentData = $this->tournamentRepoObj->tournamentReport($data);
        // $billings = $billings->get();

        $dataArray = array();

         if(isset($data['report_download']) &&  $data['report_download'] == 'yes') {
            foreach ($reportData as $reportRec) {
               $refName  = '';
              if($reportRec->referee_last_name != '' && $reportRec->referee_first_name != '') {
                $refName =   $reportRec->referee_last_name . ', ' . $reportRec->referee_first_name;
              }
                $ddata = [
                    $reportRec->match_datetime,
                    $reportRec->group_name,
                    $reportRec->venue_name,
                    $reportRec->pitch_number,
                    $refName,
                    $reportRec->HomeTeam,
                    $reportRec->AwayTeam,
                ];
                array_push($dataArray, $ddata);
            }
             $otherParams = [
                    'sheetTitle' => "Report3",
                    'sheetName' => "Report2",
                    'boldLastRow' => false
                ];

            $lableArray = [
                'Date and time','Age category' ,'Location', 'Pitch','Referee', 'Team','Team'
            ];
            //Total Stakes, Total Revenue, Amount & Balance fields are set as Number statically.
            \Laraspace\Custom\Helper\Common::toExcel($lableArray,$dataArray,$otherParams,'xlsx','yes');
         }
        if ($reportData) {
            return ['status_code' => '200', 'message' => '','data'=>$reportData];
        }
    }


    public function generatePrint($data)
    {
      $tournamentData = Tournament::where('id', '=', $data['tournament_id'])->select(DB::raw('CONCAT("'.$this->tournamentLogo.'", logo) AS tournamentLogo'))->first();


        $reportQuery = DB::table('temp_fixtures')
            ->leftJoin('tournaments', 'temp_fixtures.tournament_id', '=', 'tournaments.id')
            ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
            ->leftjoin('teams as home_team', function ($join) {
                $join->on('home_team.id', '=', 'temp_fixtures.home_team');
            })
            ->leftjoin('teams as away_team', function ($join) {
                $join->on('away_team.id', '=', 'temp_fixtures.away_team');
            })
            ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
            ->leftjoin('countries as HomeFlag', 'home_team.country_id', '=',
                'HomeFlag.id')
            ->leftjoin('countries as AwayFlag', 'away_team.country_id', '=',
                'AwayFlag.id')
            ->leftjoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
            ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
            ->leftjoin('referee', 'referee.id', '=', 'temp_fixtures.referee_id')
            ->groupBy('temp_fixtures.id')
            ->select('temp_fixtures.id as fid','temp_fixtures.match_datetime','tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.last_name as referee_last_name','referee.first_name as referee_first_name',
               'home_team.name as HomeTeam','away_team.name as AwayTeam', 'tournaments.logo',

              'HomeFlag.country_flag as HomeCountryFlag',
              'AwayFlag.country_flag as AwayCountryFlag',
               DB::raw('CONCAT("'.$this->getAWSUrl.'", HomeFlag.logo) AS HomeFlagLogo'),
               DB::raw('CONCAT("'.$this->getAWSUrl.'", AwayFlag.logo) AS AwayFlagLogo'),
               DB::raw('CONCAT("'.$this->getAWSUrl.'", tournaments.logo) AS tournamentLogo'),
              'home_team.name as HomeTeam','away_team.name as AwayTeam',
              DB::raw('CONCAT(referee.last_name,",",referee.first_name) as refereeFullName'),
              DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game'))
            ->where('temp_fixtures.tournament_id',$data['tournament_id'])
            ->where('temp_fixtures.is_scheduled',1);


            if(isset($data['sel_ageCategory'])  && $data['sel_ageCategory']!= ''){
                $reportQuery->where('tournament_competation_template.id',$data['sel_ageCategory']);
            }

            if(isset($data['start_date'])  && $data['start_date']!= '' ){

              $start_date = Carbon::createFromFormat('d/m/Y', $data['start_date'])->toDateString();
             // echo  $start_date;
              $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime','>=',$start_date);
            }
            if(isset($data['end_date'])  && $data['end_date']!= '' ){
              //echo  '<br>'.Carbon::createFromFormat('d/m/Y', $data['end_date']);

              $end_date = Carbon::createFromFormat('d/m/Y', $data['end_date'])->toDateString();
            //  echo $end_date;
              $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime','<=',$end_date);
            }

            // print_r($reportQuery->toSql());exit();
            // print_r($reportQuery->toSql());exit();
            if(isset($data['start_time'])  && $data['start_time']!= '' ){
              // $start_time = Carbon::createFromFormat('hh:mm', $data['start_time']);
              $start_time = $data['start_time'];
              // print_r($start_time); exit();
              // $start_time = '09:00';
              $reportQuery = $reportQuery->whereRaw("TIME(temp_fixtures.match_datetime) >= '$start_time'");

            }
            if(isset($data['end_time'])  && $data['end_time']!= '' ){
              $end_time = $data['end_time'];
              $reportQuery = $reportQuery->whereRaw("TIME(temp_fixtures.match_datetime) <= '$end_time'");
            }
               if(isset($data['sel_clubs']) && $data['sel_clubs'] !== '' )
          {
             $club_id =$data['sel_clubs'];
             $tournamentId = $data['tournament_id'];
             $getTeamId = Team::where('club_id','=',$club_id)->where('tournament_id','=',$tournamentId)->pluck('teams.id')->toArray();

            $reportQuery =  $reportQuery->whereIn('temp_fixtures.home_team',$getTeamId)
                ->orWhereIn('temp_fixtures.away_team',$getTeamId);
            //$reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$tournamentData['pitchId']);
          }
            if(isset($data['sel_venues'])  && $data['sel_venues']!= '' ){
                $reportQuery = $reportQuery->where('temp_fixtures.venue_id',$data['sel_venues']);
            }
            if(isset($data['sel_teams'])  && $data['sel_teams']!= '' ){

             $team = $data['sel_teams'];
              $reportQuery = $reportQuery->where(function ($query) use($team)
                              {
                                $query->where('temp_fixtures.home_team',$team)
                                ->orWhere('temp_fixtures.away_team',$team);
                              }
                            );
            }
            if(isset($data['sel_pitches'])  && $data['sel_pitches']!= '' ){
                $reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$data['sel_pitches']);
            }
            if(isset($data['sel_referees'])  && $data['sel_referees']!= '' ){

                $reportQuery = $reportQuery->where('temp_fixtures.referee_id', '=',$data['sel_referees']);
            }
            if(isset($data['sort_by']) && $data['sort_by'] != '') {
              switch($data['sort_by']) {
                  case 'match_datetime':
                        $fieldName = 'temp_fixtures.match_datetime';
                        break;
                  case 'group_name':
                        $fieldName = 'group_name';
                        break;
                  case 'venue_name':
                        $fieldName = 'venues.venue_name';
                        break;
                  case 'pitch_number':
                        $fieldName = 'pitches.pitch_number';
                        break;
                  case 'referee':
                        $fieldName = 'refereeFullName';
                        break;
                  case 'full_game':
                        $fieldName = 'full_game';
                        break;
                  case 'HomeTeam':
                        $fieldName = 'HomeTeam';
                        break;
                  case 'AwayTeam':
                        $fieldName = 'AwayTeam';
                        break;
              }

             // $sortOrder = (isset($data['sort_order']) && $data['sort_order'] != '') ? 'asc' : 'desc';
              $reportQuery = $reportQuery->orderBy($fieldName, $data['sort_order']);
             // echo 'SQL IS';
           //   print_r($reportQuery->toSql());
            }
            // $reportQuery = $reportQuery->select('fixtures.id as fid','fixtures.match_datetime','tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name',DB::raw('CONCAT(fixtures.home_team, " vs ", fixtures.away_team) AS full_game'));
        // echo $reportQuery->toSql();exit;
        $reportData = $reportQuery->get();
        // dd($reportData->all()); 
        $date = new \DateTime(date('H:i d M Y'));
        // $footer = View::make('summary.footer');  
        // $date->setTimezone();.
        $pdf = PDF::loadView('summary.report',['data' => $reportData->all(), 'tournamentData' => $tournamentData])
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')  
            ->setOrientation('portrait')
            ->setOption('footer-html', route('pdf.footer'))
            ->setOption('header-right', $date->format('H:i d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);
        return $pdf->inline('Summary.pdf');
    }

    public function updateStatus($data)
    {
        $data = $this->tournamentRepoObj->updateStatus($data['tournamentData']);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG];
        }
    }
    public function getAllCategory($data)
    {
        $data = $this->tournamentRepoObj->getAllCategory($data['tournamentData']);
        if ($data) {
            return ['status_code' => '200', 'data' => $data, 'message' => 'All category fetch Successfully'];
        }
    }
    public function getUserLoginDefaultTournament($data)
    {
      $data = $this->tournamentRepoObj->getUserDefaultLoginTournament($data);
      if($data) {
        return ['status_code' => '200', 'data' => $data, 'message' => 'getDefaultLoginTournamentData'];
      }
    }
     public function getUserLoginFavouriteTournament($data)
    {
      $data = $this->tournamentRepoObj->getUserLoginFavouriteTournament($data);
      if($data) {
        return ['status_code' => '200', 'data' => $data,
        'message' => 'getUserLoginFavouriteTournament'];
      } else {
        return ['status_code' => '200',
        'message' => 'Data not exist'];
      }
    }
    public function getTournamentClub($data)
    {
      $data = $this->tournamentRepoObj->getTournamentClub($data);
      if($data) {
        return ['status_code' => '200', 'data' => $data,'message' => 'getTournamentClubs'];
      }
    }

    public function getCoordinates($resultData)
    {
      $venue = Venue::where('tournament_id',$resultData['id'])->get();
      $tournament_id = $resultData['id'];
      $coordinates = [];
      foreach ($venue as $location) {
        $address = $location->address1.', '.$location->city.', '.$location->postcode.', '.$location->state.', '.$location->country;
        try {
          /*$geocode = Geocoder::geocode("$name, Tanzania")->toArray();
          return Response::json($geocode);*/
          $locationDetails = app('geocoder')->geocode($address)->get();
          $coordinates['id'] = $location->id;
          foreach($locationDetails as $loc)
          {
            $lat = $loc->getLatitude();
            $long = $loc->getLongitude();
          }
          $coodinates['venue_coordinates'] = $lat.','.$long;
          $updateData = [
            'venue_coordinates' => $coodinates['venue_coordinates']
          ];
          Venue::where('id',$coordinates['id'])->update($updateData);
          return;
        } catch (\Exception $e) {
          // echo $e->getMessage();
          return;
        }
      }
    }
}
