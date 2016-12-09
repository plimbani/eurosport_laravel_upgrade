<?php

namespace App\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use App\Api\Contracts\TeamContract;

class TeamController extends BaseController
{
    public function __construct(TeamContract $teamObj)
    {
        $this->teamObj = $teamObj;
    }

    public function getTeams()
    {
        return $this->teamObj->getAllTeams();
    }

    public function createTeam(Request $request)
    {
        return $this->teamObj->createTeam($request);
    }
}
