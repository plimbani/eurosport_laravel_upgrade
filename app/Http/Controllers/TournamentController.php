<?php

namespace Laraspace\Http\Controllers;

use Illuminate\Http\Request;
//use Dingo\Api\Routing\Helpers;
use Laraspace\Contracts\TournamentContract;
use Laraspace\Contracts\ApiContract;

class TournamentController extends Controller
{
    // use Helpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiContract $apiObj, TournamentContract $tournamentObj)
    {
        $this->apiObj = $apiObj;
        // $this->tournamentObj = $tournamentObj;
      //  $this->middleware('auth');
    }

    /**
     * Show the Teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tournamet.index')->with('tournaments', $this->apiObj->api->get('tournaments'));
        //return view('team.index')->with('teams', $this->apiObj->api->get('teams'));
    }

    public function create()
    {
      $dispatcher = $this->apiObj->getDispacther();


        // return view('tournament.add_tournament');
        //return view('tournament.create');
    }

    public function dashboard(Request $request, $tournamentId)
    {
        $tournamentData = $this->tournamentObj->getAllData($tournamentId);
         // dd($tournamentData);

        return view('app', compact('tournamentData'));
        // return view('home.index', compact('tournamentData'));
    }

    public function store(Request $request)
    {
       // dd('ad');
        // Call Service For Save Tournament Details
        $dispatcher = $this->apiObj->getDispacther();
          $tournamentData = [
              'name' => 'test1',
              'website' => 'http://www.team1.com', 
              'facebook' => 'fbook',
              'twitter' => 'Test',
              'start_date' => '03/06/2017',
              'end_date' => '03/10/2017',
              'user_id' => '1', 
              'age_group_id' => ''
              // 'name' => 'TestTeams', 
              
          ];

        $result = $dispatcher->with($tournamentData)->post('tournament/create');
       
        // $match = $dispatcher->with($request->all())->post('tournament/create');
        // return response()->json($this->tournamentObj->create());
        //return $this->tournamentObj->create($request->all());
    }

    // public function create()
    // {
    //     $dispatcher = $this->apiObj->getDispacther();

    //     $teamData = [
    //         'club_id' => '1', 'user_id' => '1', 'age_group_id' => '1',
    //         'name' => 'TestTeams', 'website' => 'http://www.team1.com', 'facebook' => 'fbook',
    //         'twitter' => 'Test',
    //         'shirt_colour' => '#271d7e', 'esr_reference' => '232323',
    //     ];

    //     $result = $dispatcher->with($teamData)->post('team/create');
    // }
}
