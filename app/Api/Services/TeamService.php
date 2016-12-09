<?php

namespace App\Api\Services;

use App\Api\Contracts\TeamContract;
use App\Api\Repositories\TeamRepository;

class TeamService implements TeamContract
{
    public function __construct(TeamRepository $teamRepoObj)
    {
        $this->teamRepoObj = $teamRepoObj;
    }

    public function getAllTeams()
    {
        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getAllTeams();
        if($data){
            return array('status_code'=>'200','data'=>$data);
        } else {
            return array('status_code'=>'505','message'=>'Error in Data');
        }
        
    }

    public function createTeam($data)
    {
        $data = $data->all();
        //dd($data);
        //$data = array('name'=>'tean1');
         $data = $this->teamRepoObj->createTeam($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }
}
