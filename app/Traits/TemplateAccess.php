<?php

namespace Laraspace\Traits;

use JWTAuth;
use Carbon\Carbon;
use Laraspace\Models\User;
use Laraspace\Api\Repositories\TournamentRepository;

trait TemplateAccess
{
	use AuthUserDetail;

	/**
     * @param object $tournamentObj
     */
    public function __construct(TournamentRepository $tournamentObj)
    {
        $this->tournamentRepoObj = $tournamentObj;
    }
	
	/*
	 * Check for template access to user
	 *
	 * @return response
	*/
	protected function checkForTemplateAccess($id)
	{
		$user = $this->getCurrentLoggedInUserDetail();
		$templatesIds = $user->templates()->pluck('id')->toArray();
		if (in_array($id, $templatesIds)) {
			return true;
		}
		return false;
	}

	/*
	 * Can user manage template section
	 *
	 * @return response
	*/
	protected function canUserManageTemplate()
	{
		$user = $this->getCurrentLoggedInUserDetail();
		$tournaments = $this->tournamentRepoObj->getAll('', $user)->toArray();
		$customTournaments = array_filter($tournaments, function($o) {
            if($o['tournament_type'] === 'cup' && $o['custom_tournament_format'] == 1) {
                return true;
            }
            return false;
        });
        return count($customTournaments) > 0 ? true : false;
	}

	/*
	 * Is manage template section accessible to user
	 *
	 * @return response
	*/
	protected function isManageTemplateAccessible()
	{
		$user = $this->getCurrentLoggedInUserDetail();
		$tournaments = $this->tournamentRepoObj->getAll('', $user)->toArray();
		$currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
		$customActiveTournaments = array_filter($tournaments, function($o) use($currentDateTime) {
			$tournamentExpireTime = Carbon::parse($o['tournamentExpireTime'])->format('Y-m-d H:i:s');
            if($o['tournament_type'] === 'cup' && $o['custom_tournament_format'] == 1 && $tournamentExpireTime > $currentDateTime) {
                return true;
            }
            return false;
        });
        return count($customActiveTournaments) > 0 ? true : false;
	}
}