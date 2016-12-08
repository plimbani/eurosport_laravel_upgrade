<?php

namespace App\Api\Services;

use DB;
use App\Api\Contracts\TeamContract;
use Validator;
use App\Model\Role;

class TeamService implements TeamContract
{
    public function __construct()
    {
        $this->teamRepoObj = new \App\Api\Repositories\TeamRepository();
    }

    public function getAllTeams()
    {
        return $this->teamRepoObj->getAllTeams();
    }

    public function createTeam($data)
    {
        return $this->teamRepoObj->createTeam($data);
    }
}
