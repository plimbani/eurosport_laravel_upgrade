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
}