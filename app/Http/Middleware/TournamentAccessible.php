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
        
        if(isset($request->headers->all()['ismobileuser']) && $request->headers->all()['ismobileuser'] == true) {
            if(!$token || (isset($request->headers->all()['ismobileuser'])) && $request->headers->all()['ismobileuser'] == true) {
                $tournament_id = $request->all()['tournament_id'];
                $currentLayout = config('config-variables.current_layout');
                if($currentLayout == 'commercialisation'){
                    $user = $this->getCurrentLoggedInUserDetail();
                    $checkForTournamentAccess = $this->isTournamentAccessible($user, $tournament_id);
                    if(!$checkForTournamentAccess) {
                        return ['status_code' => 200, 'tournament_expired' => 'Selected tournament has expired'];
                    } 
                }  
            } 
        }
        return $next($request);
    }
}
