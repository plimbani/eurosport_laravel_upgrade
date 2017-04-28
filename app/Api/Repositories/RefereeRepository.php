<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Referee;
use DB;

class RefereeRepository
{
    public function __construct()
    {
        $this->dbObj = DB::table('referee');
    }

    public function getAllReferees($tournamentId)
    {
        return Referee::where('tournament_id',$tournamentId)->get();
    }

    public function createReferee($refereeData)
    {
        // dd($refereeData);
        return Referee::create([
            'tournament_id' => $refereeData['tournament_id'],
            'first_name' => $refereeData['first_name'],
            'last_name' => $refereeData['last_name'],
            'telephone' => $refereeData['telephone'],
            'email' => $refereeData['email'],
            'comments' => $refereeData['comments'],
            'age_group_id' =>  $refereeData['age_category']
            
        ]);
        // return Referee::create($refereeData);
    }

    public function edit($refereeData,$refereeId)
    {
        return Referee::where('id', $refereeId)->update([
            'tournament_id' => $refereeData['tournament_id'],
            'first_name' => $refereeData['first_name'],
            'last_name' => $refereeData['last_name'],
            'telephone' => $refereeData['telephone'],
            'email' => $refereeData['email'],
            'comments' => $refereeData['comments'],
            'age_group_id' =>  $refereeData['age_category']
            
        ]);
    }

    public function getRefereeFromId($refereeId)
    {
        // dd(Referee::find($refereeId));
        return Referee::find($refereeId);
    }
}
