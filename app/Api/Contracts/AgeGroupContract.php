<?php

namespace App\Api\Contracts;

interface AgeGroupContract
{
    public function getAllData();

    /*
     * Create New Match
     *
     * @param  array $data
     * @return response
     */
    public function create($data);

    /*
     * Edit Match
     *
     * @param  array $ageGroupId,$data
     * @return response
     */
    public function edit($request, $ageGroupId);

    /*
     * Delete Match
     *
     * @param  array $ageGroupId
     * @return response
     */
    public function delete($ageGroupId);
}
