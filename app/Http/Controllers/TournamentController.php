<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Dingo\Api\Routing\Helpers;
use App\Contracts\TournamentContract;

class TournamentController extends Controller
{
    // use Helpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TournamentContract $tournamentObj)
    {
        $this->tournamentObj = $tournamentObj;
      //  $this->middleware('auth');
    }

    /**
     * Show the Teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('team.index')->with('teams', $this->apiObj->api->get('teams'));
    }

    public function create()
    {
        return view('tournament.add_tournament');
        //return view('tournament.create');
    }

    public function dashboard(Request $request, $tournamentId)
    {
        $tournamentData = $this->tournamentObj->getAllData($tournamentId);

        return view('home.index', compact('tournamentData'));
    }

    public function store(Request $request)
    {
        // Call Service For Save Tournament Details
        dd($request->all());

        return response()->json($this->tournamentObj->create($request->all()));
        //return $this->tournamentObj->create($request->all());
    }

    /*public function create()
    {
        $dispatcher = $this->apiObj->getDispacther();

        $teamData = [
            'club_id' => '1', 'user_id' => '1', 'age_group_id' => '1',
            'name' => 'TestTeams', 'website' => 'http://www.team1.com', 'facebook' => 'fbook',
            'twitter' => 'Test',
            'shirt_colour' => '#271d7e', 'esr_reference' => '232323',
        ];

        $result = $dispatcher->with($teamData)->post('team/create');
    }*/
}
