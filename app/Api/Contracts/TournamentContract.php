<?php

namespace App\Api\Contracts;

interface TournamentContract
{
    /*
     * Get All Tournaments
     *
     * @param  array $state,$type
     * @return response
     */
    public function index();

    /*
     * Create New Tournament
     *
     * @param  array $data
     * @return response
     */

    public function create($request);

    /*
     * Edit Tournament
     *
     * @param  array $id,$data
     * @return response
     */
    public function edit($request,$id);

    /*
     * Delete Tournament
     *
     * @param  array $id
     * @return response
     */

    public function delete($id);
}
