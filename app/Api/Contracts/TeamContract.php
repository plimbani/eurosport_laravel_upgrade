<?php

namespace Laraspace\Api\Contracts;

interface TeamContract
{
    /*
     * Get All Teams
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTeams();

    /*
     * Create New Team
     *
     * @param  array $api_key,$data
     * @return response
     */

    public function create($request);

    /*
     * Edit team
     *
     * @param  array $api_key,$tournament_id,$data
     * @return response
     */
    public function edit($request);

    /*
     * Delete Team
     *
     * @param  array $api_key,$tournament_id
     * @return response
     */

    public function delete($request);
}
