<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ApiContract;

class RefereeController extends Controller
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
     * Show the Referees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $referees = $this->apiObj->api->get('referees');

        return view('referee.index')->with('referees', $referees);
    }

    public function create()
    {
        $dispatcher = $this->apiObj->getDispacther();

        $refereeData = [
             'user_id' => '1', 'availability' => '100',
            'comments' => 'TestComments', 'age_group_id' => '1',
        ];
        return $dispatcher->with($refereeData)->post('referee/create');
    }

    public function edit($refereeId)
    {
        $dispatcher = $this->apiObj->getDispacther();

        $refereeData = [
            'availability' => '100',
        ];
        
       return $dispatcher->with($refereeData)->post('referee/edit/'.$refereeId);
    }

    public function deleteReferee($deleteId)
    {
        $dispatcher = $this->apiObj->getDispacther();

        return $dispatcher->post('referee/delete/'.$deleteId);
    }
}
