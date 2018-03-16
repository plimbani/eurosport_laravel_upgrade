<?php

namespace Laraspace\Traits;

use JWTAuth;
use Laraspace\Models\User;

trait TournamentAccess
{
	use AuthUserDetail;
	
	/*
	 * Check for tournament access to user
	 *
	 * @return response
	 */
	protected function checkForTournamentAccess($id)
	{
		$user = $this->getCurrentLoggedInUserDetail();
		if($user->hasRole('tournament.administrator')) {
			$tournamentsIds = $user->tournaments()->pluck('id')->toArray();
			if (in_array($id, $tournamentsIds)) {
				return true;
			}
			return false;
		}
		return true;
	}

	/*
	 * Check for tournament access to user
	 *
	 * @return response
	 */
	protected function checkForWritePermissionByTournament($id)
	{
		$user = $this->getCurrentLoggedInUserDetail();
		if($user->hasRole('tournament.administrator')) {
			$tournamentsIds = $user->tournaments()->pluck('id')->toArray();
			if (in_array($id, $tournamentsIds)) {
				return true;
			}
			return false;
		}
		if($user->hasRole('mobile.user')) {
			return false;
		}
		return true;
	}
}