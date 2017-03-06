<?php

namespace Laraspace\Http\Controllers;

use Illuminate\Http\Request;
//use Dingo\Api\Routing\Helpers;
use Laraspace\Contracts\ApiContract;

class TeamController extends Controller
{
    // use Helpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiContract $apiObj)
    {
        $this->apiObj = $apiObj;
        // $this->middleware('auth');
    }

    /**
     * Show the Teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('team.index')->with('teams', $this->apiObj->api->get('teams'));
    }

    public function create()
    {

        $dispatcher = $this->apiObj->getDispacther();

        $teamData = [
            'club_id' => '1', 'user_id' => '1', 'age_group_id' => '1',
            'name' => 'TestTeams', 'website' => 'http://www.team1.com', 'facebook' => 'fbook',
            'twitter' => 'Test',
            'shirt_colour' => '#271d7e', 'esr_reference' => '232323',
        ];

        $result = $dispatcher->with($teamData)->post('team/create');
    }
}
