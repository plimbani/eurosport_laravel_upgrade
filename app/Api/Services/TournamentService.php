<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\TournamentContract;
use Laraspace\Api\Repositories\TournamentRepository;

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
        
         
         
         //exit;
        $data = $data->all();
        // dd($data);
        // here first we save the tournament related Data
        // here we have to precprocess the image
        // Save the image
        $data['tournamentData']['image_logo']=$this->saveTournamentLogo($data);
        
        //\File::put($path , $imgData);
        //print_r($imgData);        

        $data = $this->tournamentRepoObj->create($data['tournamentData']);

        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG,
             'data'=>$data];
        }
    }
    private function saveTournamentLogo($data)
    {
       if($data['tournamentData']['image_logo'] != '')
       {
            $image_string = $data['tournamentData']['image_logo']; 

            $img = explode(',', $image_string);        
            $imgData = base64_decode($img[1]);        

            $name = $data['tournamentData']['name'];
            $now = new \DateTime();
            
            $timeStamp = $now->getTimestamp();
            $path = public_path().'/assets/img/tournament_logo/'.$timeStamp.'.png';        
            file_put_contents($path, $imgData);      
            return $timeStamp.'.png';
        } else {
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
}
