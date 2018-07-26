<?php

namespace Laraspace\Contracts;

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
     * @param  array $api_key,$referee_id,$data
     * @return response
     */
    public function edit($request);

    /*
     * Delete Referee
     *
     * @param  array $api_key,$referee_id
     * @return response
     */
    public function deleteReferee($data);
}
