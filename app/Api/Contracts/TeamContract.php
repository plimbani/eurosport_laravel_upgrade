<?php

namespace App\Api\Contracts;

interface TeamContract
{
    /*
     * Get User Data
     *
     * @param  array $data,$affiliateList
     * @return response
     */
    
    public function getAllTeams();
    public function createTeam($data);
}
