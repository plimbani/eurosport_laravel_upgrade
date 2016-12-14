<?php

namespace App\Api\Services;

use App\Api\Contracts\TournamentContract;
use App\Api\Repositories\TournamentRepository;

class TournamentService implements TournamentContract
{
    public function __construct(TournamentRepository $tournamentRepoObj)
    {
        $this->tournamentRepoObj = $tournamentRepoObj;
    }

    public function getAllTournaments()
    {
        // Here we send Status Code and Messages
        $data = $this->tournamentRepoObj->getAllTournaments();
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function createTournament($data)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->createTournament($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }
}
