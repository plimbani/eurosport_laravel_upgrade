<?php

namespace Laraspace\Http\Middleware;

use Closure;
use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Traits\TournamentAccess;


class TournamentAccessible
{
    use TournamentAccess;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = JWTAuth::getToken();
        
        if((isset($request->headers->all()['ismobileuser'])) && $request->headers->all()['ismobileuser'] == true) {
            if(isset($request->all()['tournamentData']) && isset($request->all()['tournamentData']['tournamentId'])) {
                $tournament_id = $request->all()['tournamentData']['tournamentId'];
            } else if(isset($request->all()['tournamentId'])) {
                $tournament_id = $request->all()['tournamentId'];
            }
            
            $currentLayout = config('config-variables.current_layout');
            if($currentLayout == 'commercialisation'){
                $user = $this->getCurrentLoggedInUserDetail();
                $checkForTournamentAccess = $this->isTournamentFavourite($user, $tournament_id);
                if(!$checkForTournamentAccess) {
                    return response()->json(['status_code' => 500, 'tournament_expired' => 'Selected tournament has expired'], 500);
                } 
            }  
        } 
        return $next($request);
    }
}
