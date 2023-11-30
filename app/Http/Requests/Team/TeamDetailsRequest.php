<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Team;
use App\Traits\TournamentAccess;

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
