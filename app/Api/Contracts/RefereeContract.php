<?php

namespace Laraspace\Api\Contracts;

interface RefereeContract
{
    /*
     * Get Referee Data
     *
     * @param  array $data
     * @return response
     */

    public function getAllReferees($tournamentData);

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
     * @param  array $api_key,$referee_id,$data
     * @return response
     */
    public function edit($request,$refereeId);

    /*
     * Delete Referee
     *
     * @param  array $api_key,$referee_id
     * @return response
     */
    public function deleteReferee($deleteId);
}
