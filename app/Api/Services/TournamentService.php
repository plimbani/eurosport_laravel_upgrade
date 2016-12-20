<?php

namespace App\Api\Services;

use App\Api\Contracts\TournamentContract;
use App\Api\Repositories\TournamentRepository;

class TournamentService implements TournamentContract
{
    /**
     *  Messages To Display.
     */
    const SUCCESS_MSG = 'Data Sucessfully inserted';
    const ERROR_MSG = 'Error in Data';

    public function __construct(TournamentRepository $tournamentRepoObj)
    {
        $this->tournamentRepoObj = $tournamentRepoObj;
    }

     /*
     * Get All Tournaments
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index()
    {
        // Here we send Status Code and Messages
        $data = $this->tournamentRepoObj->getAll();
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => self::ERROR_MSG];
    }

    /**
     * create New Tournament.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function create($data)
    {
        $data = $data->all();
        $data = $this->tournamentRepoObj->create($data);
        if ($data) {
            return ['status_code' => '200', 'message' => self::SUCCESS_MSG];
        }
    }

    /**
     * Edit Tournament.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data,$id)
    {
        $data = $data->all();
        $data = $this->tournamentRepoObj->edit($data,$id);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Tournament.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function delete($deleteId)
    {
        $tournamentRes = $this->tournamentRepoObj->getTournamentFromId($deleteId)->delete();
        if ($tournamentRes) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
}
