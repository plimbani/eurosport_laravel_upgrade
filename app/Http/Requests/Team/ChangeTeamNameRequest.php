<?php

namespace App\Http\Requests\Team;

use App\Models\Team;
use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class ChangeTeamNameRequest extends FormRequest
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
        if (isset($data['teamData']['team_id'])) {
            $teamId = $data['teamData']['team_id'];
            $team = Team::findOrFail($teamId);
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($team->tournament_id);
            if (! $isTournamentAccessible) {
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
            'teamData' => 'required|array',
        ];
    }
}
