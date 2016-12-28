<?php

namespace App\Services;

use App\Contracts\TournamentContract;
use App\Repositories\TournamentRepository;

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
     * @param mixed $id
     * @param mixed $tournamentId
     *
     * @return [type]
     */
    public function edit($data, $tournamentId)
    {
        $data = $data->all();
        $data = $this->tournamentRepoObj->edit($data, $tournamentId);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    /**
     * Delete Tournament.
     *
     * @param array $data
     * @param mixed $deleteId
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
