<?php

namespace App\Traits;

use App\Models\User;
use JWTAuth;

trait AuthUserDetail
{
    /*
     * Get current logged in user detail.
     *
     * @return response
     */
    protected function getCurrentLoggedInUserDetail()
    {
        $token = JWTAuth::getToken();
        if (! $token) {
            return null;
        }
        $authUser = JWTAuth::parseToken()->toUser();
        $userObj = User::with('roles', 'tournaments')->where('id', $authUser->id)->first();

        // $authUser->assignRole('Super.administrator');
        // dd($userObj->roles);
        return $userObj;
    }

    /*
     * Get current logged in user id.
     *
     * @return response
     */
    protected function getCurrentLoggedInUserId()
    {
        $token = JWTAuth::getToken();
        $authUser = JWTAuth::parseToken()->toUser();

        return $authUser->id;
    }
}
