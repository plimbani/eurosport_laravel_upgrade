<?php

namespace Laraspace\Contracts;

interface PitchContract
{
    /*
     * Get Pitch Data
     *
     * @param  array $data
     * @return response
     */

    public function getAllPitches();

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
     * @param  array $api_key,$pitch_id,$data
     * @return response
     */
    public function editPitch($request, $pitchId);

    /*
     * Delete Pitch
     *
     * @param  array $api_key,$pitch_id
     * @return response
     */
    public function deletePitch($data);
}
