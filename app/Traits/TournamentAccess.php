<?php

namespace App\Traits;

use App\Models\User;

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
        if ($user->hasRole('tournament.administrator')) {
            $tournamentsIds = $user->tournaments()->pluck('id')->toArray();
            if (in_array($id, $tournamentsIds)) {
                return true;
            }

            return false;
        }

        return true;
    }

    /*
     * Check for tournaments access to user
     *
     * @return response
     */
    protected function checkForMultipleTournamentAccess($id)
    {
        $user = $this->getCurrentLoggedInUserDetail();
        if ($user->hasRole('tournament.administrator')) {
            $tournamentsIds = $user->tournaments()->pluck('id')->toArray();
            if (! empty(array_intersect($id, $tournamentsIds))) {
                return true;
            }

            return false;
        }

        return true;
    }

    /*
     * Check for tournament read access to user
     *
     * @return response
     */
    protected function checkForTournamentReadAccess($id)
    {
        $user = $this->getCurrentLoggedInUserDetail();
        if ($user->hasRole('tournament.administrator')) {
            $tournamentsIds = [];
            $userTournamentsIds = $user->tournaments()->pluck('id')->toArray();
            $userFavTournamentsIds = $user->favouriteTournaments()->pluck('tournament_id')->toArray();
            $tournamentsIds = array_merge($userTournamentsIds, $userFavTournamentsIds);
            $tournamentsIds = array_unique($tournamentsIds);
            if (in_array($id, $tournamentsIds)) {
                return true;
            }

            return false;
        }

        return true;
    }

    /*
     * Check for write permission by tournament
     *
     * @return response
     */
    protected function checkForWritePermissionByTournament($id)
    {
        $user = $this->getCurrentLoggedInUserDetail();
        if ($user->hasRole('tournament.administrator')) {
            $tournamentsIds = $user->tournaments()->pluck('id')->toArray();
            if (in_array($id, $tournamentsIds)) {
                return true;
            }

            return false;
        }
        if ($user->hasRole('mobile.user')) {
            return false;
        }

        return true;
    }

    protected function isTournamentPublished($tournament)
    {
        if ($tournament->status != 'Published') {
            return false;
        }

        return true;
    }

    /*
     * Check for write permission of tournament
     *
     * @return response
     */
    protected function checkForWritePermissionOfTournament($id)
    {
        $user = $this->getCurrentLoggedInUserDetail();
        if ($user->hasRole('tournament.administrator')) {
            $tournamentsIds = $user->tournaments()->pluck('id')->toArray();
            if (in_array($id, $tournamentsIds)) {
                return true;
            }

            return false;
        }
        if ($user->hasRole('mobile.user') || $user->hasRole('Results.administrator')) {
            return false;
        }

        return true;
    }
}
