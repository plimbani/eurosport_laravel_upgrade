<?php

namespace Laraspace\Http\Requests\Team;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Models\Competition;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetAllCompetitionTeamsFromFixtureRequest extends FormRequest
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
        if(!$token || (isset(app('request')->header('ismobileuser'))) && app('request')->header('ismobileuser') == "true") {
            $data = $this->all()['tournamentData'];
            $competitionId = $data['competitionId'];
            $competition = Competition::findOrFail($competitionId);
            $tournament_id = $competition->tournament_id;
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
