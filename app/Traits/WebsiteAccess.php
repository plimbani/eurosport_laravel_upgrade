<?php

namespace Laraspace\Traits;

trait WebsiteAccess
{
    use AuthUserDetail;

    /*
     * Check for write permission by website
     *
     * @return response
     */
    protected function checkForWritePermissionByWebsite($id)
    {
        $user = $this->getCurrentLoggedInUserDetail();
        if ($user->hasRole('tournament.administrator')) {
            $websitesIds = $user->websites()->pluck('id')->toArray();
            if (in_array($id, $websitesIds)) {
                return true;
            }

            return false;
        }
        if ($user->hasRole('mobile.user')) {
            return false;
        }

        return true;
    }
}
