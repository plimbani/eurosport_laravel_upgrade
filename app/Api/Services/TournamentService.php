<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\TournamentContract;
use Laraspace\Api\Repositories\TournamentRepository;
use DB;
use Carbon\Carbon;

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
        $data = $this->tournamentRepoObj->getAll();

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
    public function templates()
    {
        // Here we send Status Code and Messages        
        $data = $this->tournamentRepoObj->getAllTemplates();

        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => self::ERROR_MSG];
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
        $data = $data->all();
        // dd($data);
        // here first we save the tournament related Data
        // here we have to precprocess the image
        // Save the image
       // $this->saveTournamentLogo($data);
        
        //\File::put($path , $imgData);
        //print_r($imgData);        

        $data = $this->tournamentRepoObj->create($data['tournamentData']);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
             'data'=>$data];
        }
    }
    private function saveTournamentLogo($data){
        $image_string = $data['tournamentData']['image_logo']; 

        $img = explode(',', $image_string);        
        $imgData = base64_decode($img[1]);        

        $name = $data['tournamentData']['name'];

        $path = public_path().'/assets/img/tournament_logo/'.$name.'.jpg';        
        file_put_contents($path, $imgData);
        return $name.'.jpg';
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
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted','data'=>$tournamentData];
        }
    }

    public function generateReport($data)
    {
        $data=$data['data'];
         // dd($data);
        $reportQuery = DB::table('fixtures')
            // ->Join('tournament', 'fixture.tournament_id', '=', 'tournament.id')
            ->leftjoin('venues', 'fixtures.venue_id', '=', 'venues.id')
            ->leftjoin('teams as home_team', function ($join) {
                $join->on('home_team.id', '=', 'fixtures.home_team');
            })
            ->leftjoin('teams as away_team', function ($join) {
                $join->on('away_team.id', '=', 'fixtures.away_team');
            })
            ->leftjoin('pitches', 'fixtures.pitch_id', '=', 'pitches.id')
            ->leftjoin('competitions', 'competitions.id', '=', 'fixtures.competition_id')
            ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
           
            ->leftjoin('match_results', 'fixtures.match_result_id', '=', 'match_results.id')
            ->leftjoin('referee', 'referee.id', '=', 'match_results.referee_id')
            ->groupBy('fixtures.id')
            ->select('fixtures.id as fid','fixtures.match_datetime','tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name',DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game'));
            if(isset($data['age_category'])  && $data['age_category']!= ''){
                $reportQuery->where('tournament_competation_template.id',$data['age_category']);
            }
            if(isset($data['start_date'])  && $data['start_date']!= '' ){
                $start_date = Carbon::createFromFormat('m/d/Y', $data['start_date']);
                // dd($start_date);
               $reportQuery = $reportQuery->where('fixtures.match_datetime','>=',$start_date);
            }
            if(isset($data['end_date'])  && $data['end_date']!= '' ){
                // dd(Carbon::createFromFormat('m/d/Y', $data['end_date']));
                $reportQuery = $reportQuery->where('fixtures.match_datetime','<=',Carbon::createFromFormat('m/d/Y', $data['end_date']));
            }
            if(isset($data['location'])  && $data['location']!= '' ){
                $reportQuery = $reportQuery->where('fixtures.venue_id',$data['location']);
            }
            if(isset($data['team'])  && $data['team']!= '' ){
                $reportQuery = $reportQuery->where('fixtures.home_team',$data['team'])
                            ->orWhere('fixtures.away_team',$data['team']);
            }
            if(isset($data['pitch'])  && $data['pitch']!= '' ){
                $reportQuery = $reportQuery->where('fixtures.pitch_id',$data['pitch']);
            }
            if(isset($data['referee'])  && $data['referee']!= '' ){
                $reportQuery = $reportQuery->where('match_results.referee_id',$data['referee']);
            }
            
            // $reportQuery = $reportQuery->select('fixtures.id as fid','fixtures.match_datetime','tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name',DB::raw('CONCAT(fixtures.home_team, " vs ", fixtures.away_team) AS full_game'));
        $reportData = $reportQuery->get();
         // $tournamentData = $this->tournamentRepoObj->tournamentReport($data);
        // $billings = $billings->get();

        $dataArray = array();
        
         if($data['report_download'] == 'yes') {
            foreach ($reportData as $reportRec) {
                $ddata = [
                    $reportRec->match_datetime,
                    $reportRec->group_name,
                    $reportRec->venue_name,
                    $reportRec->pitch_number,
                    $reportRec->referee_name,
                    $reportRec->full_game,
                ];
                array_push($dataArray, $ddata);
            }   
             $otherParams = [
                    'sheetTitle' => "Report3",
                    'sheetName' => "Report2",
                    'boldLastRow' => false
                ];

            $lableArray = [
                'Date(time)','Age category' ,'Location', 'Pitch','Referee', 'Game'
            ];

            //Total Stakes, Total Revenue, Amount & Balance fields are set as Number statically.


            \Laraspace\Custom\Helper\Common::toExcel($lableArray,$dataArray,$otherParams,'xlsx','yes');
         }

        if ($reportData) {
            return ['status_code' => '200', 'message' => '','data'=>$reportData];
        }
    }
       
}
