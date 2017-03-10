<?php

namespace Laraspace\Api\Contracts;

interface UserContract
{
    /*
     * Get All Users
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getAllUsers();

    /*
     * Create New User
     *
     * @param  array $api_key,$data
     * @return response
     */

    public function create($request);

    /*
     * Edit User
     *
     * @param  array $api_key,$user_id,$data
     * @return response
     */
    public function edit($request, $userId);

    /*
     * Delete User
     *
     * @param  array $api_key,$user_id
     * @return response
     */

    public function delete($request);
}
