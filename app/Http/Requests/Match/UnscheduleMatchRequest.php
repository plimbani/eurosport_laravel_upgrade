<?php

namespace Laraspace\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\TempFixture;
use Laraspace\Traits\TournamentAccess;

class UnscheduleMatchRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->all()['matchData']) {
            $matchId = $this->all()['matchData'];
            $tempFixture = TempFixture::findOrFail($matchId);
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($tempFixture->tournament_id);
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
            'matchData' => 'required',
        ];
    }
}
