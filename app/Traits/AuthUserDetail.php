<?php

namespace Laraspace\Traits;

use JWTAuth;
use Laraspace\Models\User;

trait AuthUserDetail
{
	/*
	 * Get current logged in user detail.
	 *
	 * @return response
	 */
	protected function getCurrentLoggedInUserDetail()
	{
		$token=JWTAuth::getToken();
		$authUser = JWTAuth::parseToken()->toUser();
		$userObj = User::with('roles')->where('id', $authUser->id)->first();

		return $userObj;
	}

	/*
	 * Get current logged in user id.
	 *
	 * @return response
	 */
	protected function getCurrentLoggedInUserId()
	{
		$token=JWTAuth::getToken();
		$authUser = JWTAuth::parseToken()->toUser();
		return $authUser->id;
	}
}