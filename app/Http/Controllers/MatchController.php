<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ApiContract;

class MatchController extends Controller
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
        $this->middleware('jwt.auth');
    }

    /**
     * Show the Matches.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matches = $this->apiObj->api->get('matches');

        return view('match.index')->with('matches', $matches);
    }

    public function create()
    {
        $dispatcher = $this->apiObj->getDispacther();

        $matchData = [
            'goal_score1' => '1', 'goal_score2' => '3',
            'match_status' => 'full-time', 'winner' => 'test1',
            'location_id' => '1', 'referee_id' => '1',
            'notes' => 'test Notes',
        ];
        return $dispatcher->with($matchData)->post('match/create');
    }

    public function edit($matchId)
    {
        $dispatcher = $this->apiObj->getDispacther();

        $matchData = [
            'goal_score2' => '5',
        ];
        
        return $dispatcher->with($matchData)->post('match/edit/'.$matchId);
    }

    public function deleteMatch($deleteId)
    {
        $dispatcher = $this->apiObj->getDispacther();

        return $dispatcher->post('match/delete/'.$deleteId);
    }
}
