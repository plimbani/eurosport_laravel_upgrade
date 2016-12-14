<?php

namespace App\Api\Contracts;

interface MatchContract
{
    /*
     * Get Match Data
     *
     * @param  array $data
     * @return response
     */

    public function getAllMatches();

    public function createMatch($data);

    public function deleteMatch($deleteId);
}
