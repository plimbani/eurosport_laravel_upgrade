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

    /*
     * Create New Referee
     *
     * @param  array $api_key,$data
     * @return response
     */
    public function createReferee($data);

    /*
     * Edit Referee
     *
     * @param  array $api_key,$refereeId,$data
     * @return response
     */
    public function edit($request, $refereeId);

    /*
     * Delete Referee
     *
     * @param  array $api_key,$refereeId
     * @return response
     */
    public function deleteReferee($refereeId);
}
