<?php

namespace Laraspace\Http\Requests\Match;

use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class CheckTeamIntervalRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->all();
        if (isset($data['tournamentId'])) {
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($data['tournamentId']);
            if(!$isTournamentAccessible) {
                return false;
            }
            return true;
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
            'tournamentId' => 'required',
            'ageGroupId' => 'required',
            'teams' => 'required|array',
        ];
    }
}
