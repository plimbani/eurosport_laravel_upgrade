<?php

namespace Laraspace\Api\Contracts;

interface PitchContract
{
    /*
     * Get Pitch Data
     *
     * @param  array $data
     * @return response
     */

    public function getAllPitches($tournamentId);

    /*
     * Create New Pitch
     *
     * @param  array $api_key,$data
     * @return response
     */
    public function createPitch($data);

    /*
     * Edit Pitch
     *
     * @param  array $request
     * @return response
     */
    public function edit($request,$pitchId);
    
    public function getPitchData($pitchId);

    /*
     * Delete Pitch
     *
     * @param  array $api_key,$deleteId
     * @return response
     */
    public function deletePitch($deleteId);
}
