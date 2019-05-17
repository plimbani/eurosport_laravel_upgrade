<?php

namespace Laraspace\Http\Requests\AgeGroup;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetCompetationFormatRequest extends FormRequest
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
        if(!$token || ($this->headers->all()['ismobileuser']) && app('request')->header('ismobileuser') == "true") {
            $tournament_id = null;
            if(($this->headers->all()['ismobileuser']) && app('request')->header('ismobileuser') == "true") {
                $tournament_id = $this->all()['tournament_id'];
            } else {
                $data = $this->all()['tournamentData'];
                $tournament_id = $data['tournament_id'];
            }

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
