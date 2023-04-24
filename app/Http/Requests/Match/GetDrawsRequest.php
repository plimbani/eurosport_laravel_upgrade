<?php

namespace Laraspace\Http\Requests\Match;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetDrawsRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $token = JWTAuth::getToken();
        if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {            
            
<<<<<<< HEAD
            if(isset($this->all()['tournamentId'])) {
                $tournament_id = $this->all()['tournamentId'];
            } else {
                $tournament_id = $this->all()['tournament_id'];
            }
            
            
            // if(isset($this->all()['tournamentId'])) {
            //     $tournament_id = $this->all()['tournamentId'];
            // } else if (isset($this->all()['tournament_id'])) {
            //     $tournament_id = $this->all()['tournament_id'];
            // } else {
            //     return false;
            // }
=======
            $tournament_id = $this->all()['tournamentId'];
            
            // if (isset($this->all()['tournament_id'])) {
            //     $tournament_id = $this->all()['tournamentId'];
            // } else {
            //     return false;
            // }  
>>>>>>> tmp-1.13.1

            $tournament = Tournament::where('id',$tournament_id)->first();
            $isTournamentPublished = $this->isTournamentPublished($tournament);
            if(!$isTournamentPublished) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
