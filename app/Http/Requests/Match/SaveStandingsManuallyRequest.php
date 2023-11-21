<?php

namespace Laraspace\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\Competition;
use Laraspace\Traits\TournamentAccess;

class SaveStandingsManuallyRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['data'])) {
            $data = $this->all()['data'];
            $competitionId = $data['competitionId'];
            $competition = Competition::findOrFail($competitionId);
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($competition->tournament_id);
            if (! $isTournamentAccessible) {
                return false;
            }

            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
        ];
    }
}
