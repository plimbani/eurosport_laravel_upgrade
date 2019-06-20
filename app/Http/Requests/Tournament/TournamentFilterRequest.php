<?php

namespace Laraspace\Http\Requests\Tournament;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class TournamentFilterRequest extends FormRequest
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
            $data = $this->all()['tournamentData'];
            $tournament_id = $data['tournamentId'];
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
