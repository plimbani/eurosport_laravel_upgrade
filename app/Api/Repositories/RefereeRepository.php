<?php

namespace App\Api\Repositories;

use App\Models\Referee;
use App\Models\TournamentCompetationTemplates;
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
        $RefereeData = Referee::where('tournament_id', $tournamentData['tournamentId'])
            ->orderBy('last_name', 'ASC');

        if ($age_category != '') {
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
            'age_group_id' => $refereeData['age_category'],
            'is_all_age_categories_selected' => $refereeData['is_all_age_categories_selected'],

        ]);
        // return Referee::create($refereeData);
    }

    public function edit($refereeData, $refereeId)
    {
        return Referee::where('id', $refereeId)->update([
            'tournament_id' => $refereeData['tournament_id'],
            'first_name' => $refereeData['first_name'],
            'last_name' => $refereeData['last_name'],
            'telephone' => $refereeData['telephone'],
            'email' => $refereeData['email'],
            'comments' => $refereeData['comments'],
            'age_group_id' => $refereeData['age_category'],
            'is_all_age_categories_selected' => $refereeData['is_all_age_categories_selected'],

        ]);
    }

    public function getRefereeFromId($refereeId)
    {
        // dd(Referee::find($refereeId));
        return Referee::find($refereeId);
    }

    /*
    * Upload referees
    *
    * @return response
    */
    public function uploadRefereesExcel($data)
    {
        $ageGroups = TournamentCompetationTemplates::where('tournament_id', $data->refereeData['tournamentId'])->pluck('id')->toArray();
        $ageGroupsIds = implode(',', $ageGroups);
        if ($data['firstname'] && $data['lastname']) {
            return Referee::create([
                'tournament_id' => $data->refereeData['tournamentId'],
                'first_name' => $data['firstname'],
                'last_name' => $data['lastname'],
                'telephone' => array_get($data, 'telephone', null),
                'email' => array_get($data, 'email', null),
                'comments' => array_get($data, 'availability', null),
                'age_group_id' => $ageGroupsIds,
                'is_all_age_categories_selected' => true,
            ]);
        }
    }
}
