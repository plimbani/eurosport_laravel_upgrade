<?php

namespace App\Api\Services;

use DB;
use App\Api\Contracts\MatchContract;
use Validator;
use App\Model\Role;

class MatchService implements MatchContract
{
    public function __construct()
    {
        $this->matchRepoObj = new \App\Api\Repositories\MatchRepository();
    }

    public function getAllMatches()
    {
        return $this->matchRepoObj->getAllMatches();
    }

    public function createMatch($data)
    {
        $data = $data->all();
        $data = $this->matchRepoObj->createMatch($data);
        if ($data) {
            return ['code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    public function deleteMatch($deleteId)
    {
        $matchRes = $this->matchRepoObj->getMatchFromId($deleteId)->delete();
        if ($matchRes) {
            return ['code' => '200', 'message' => 'Match Sucessfully Deleted'];
        }
    }
}
