<?php

namespace Laraspace\Contracts;

interface AgeGroupContract
{
    /*
     * Get All Age Group Categories
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index();

    /*
     * Create New Age Group
     *
     * @param  array $api_key,$data
     * @return response
     */

    public function create($request);

    /*
     * Edit Age Group
     *
     * @param  array $api_key,$data
     * @return response
     */
    public function edit($request);

    /*
     * Delete Age Group
     *
     * @param  array $api_key,$data
     * @return response
     */

    public function delete($request);
}
