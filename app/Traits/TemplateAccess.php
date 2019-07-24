<?php

namespace Laraspace\Traits;

use JWTAuth;
use Laraspace\Models\User;

trait TemplateAccess
{
	use AuthUserDetail;
	
	/*
	 * Check for tournament access to user
	 *
	 * @return response
	*/
	protected function checkForTemplateAccess($id)
	{
		$user = $this->getCurrentLoggedInUserDetail();
		if($user->hasRole('customer')) {
			$templatesIds = $user->templates()->pluck('id')->toArray();
			if (in_array($id, $templatesIds)) {
				return true;
			}
			return false;
		}
		return true;
	}
}