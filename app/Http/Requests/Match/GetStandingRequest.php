<?php

namespace App\Http\Requests\Match;

use App\Models\Tournament;
use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;

class GetStandingRequest extends FormRequest
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
        if (! $token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == 'true')) {
            $data = isset($this->all()['tournamentData']) ? $this->all()['tournamentData'] : [];
            $tournament_id = isset($data['tournamentId']) ? $data['tournamentId'] : '';
            if (! $tournament_id) {
                return false;
            }

            $tournament = Tournament::where('id', $tournament_id)->first();
            $isTournamentPublished = $this->isTournamentPublished($tournament);
            if (! $isTournamentPublished) {
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
