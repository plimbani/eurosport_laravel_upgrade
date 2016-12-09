<?php

namespace App\Api\Contracts;

interface RefereeContract
{
    /*
     * Get Referee Data
     *
     * @param  array $data
     * @return response
     */

    public function getAllReferees();

    public function createReferee($data);
}
