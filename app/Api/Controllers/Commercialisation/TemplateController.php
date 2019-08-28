<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use JWTAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laraspace\Models\Tournament;
use Laraspace\Models\TempFixture;
use Laraspace\Traits\TemplateAccess;

/**
 * Template Resource Description.
 *
 *
 */
class TemplateController extends BaseController
{
	use TemplateAccess;

	/**
     * Check can manage template section
     * @return array
     */
	public function canManageTemplateSection()
    {
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        if($loggedInUser->hasRole('Super.administrator')) {
            return response()->json(['can_access' => true]);
        }

        if($loggedInUser->hasRole('customer')) {
            if($this->canUserManageTemplate() && $this->isManageTemplateAccessible()) {
                return response()->json(['can_access' => true]);
            }
        }

        return response()->json(['can_access' => false]);
    }
}