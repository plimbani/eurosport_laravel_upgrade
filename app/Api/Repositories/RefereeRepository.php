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

    public function getAllReferees($tournamentData)
    {
        // dd($tournamentData['age_category']);
        $age_category = $tournamentData['age_category'];
         $RefereeData = Referee::where('tournament_id',$tournamentData['tournamentId'])
              ->orderBy('last_name','ASC');

            if($age_category !=''){
                $RefereeData->whereRaw('FIND_IN_SET('.$age_category.',age_group_id)');
            }
            return $RefereeData->get();
    }

    public function createReferee($refereeData)
    {
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
