<?php

namespace Laraspace\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\Team;
use Laraspace\Traits\TournamentAccess;

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
        $teamId = $this->route('id');
        $team = Team::findOrFail($teamId);
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($team->tournament_id);
        if (! $isTournamentAccessible) {
            return false;
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
