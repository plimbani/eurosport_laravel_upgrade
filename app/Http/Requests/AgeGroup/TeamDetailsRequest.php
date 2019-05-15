<?php

namespace Laraspace\Http\Requests\AgeGroup;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Traits\TournamentAccess;
use Laraspace\Models\TournamentCompetationTemplates;
use Illuminate\Foundation\Http\FormRequest;

class TeamDetailsRequest extends FormRequest
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
            $ageCategoryId = $this->all()['ageCategoryId'];
            $ageCategory = TournamentCompetationTemplates::findOrFail($ageCategoryId);
            $tournament_id = $ageCategory->tournament_id;
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
