<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;
use App\Models\Tournament;
use App\Traits\TournamentAccess;

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
        if (! $token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == 'true')) {

            //$tournament_id = $this->all()['tournamentId'];

            if (isset($this->all()['tournamentId'])) {
                $tournament_id = $this->all()['tournamentId'];
            } else {
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
