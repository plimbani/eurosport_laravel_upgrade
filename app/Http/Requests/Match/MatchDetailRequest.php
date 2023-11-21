<?php

namespace Laraspace\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\TempFixture;
use Laraspace\Traits\TournamentAccess;

class MatchDetailRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['matchId'])) {
            $matchId = $this->all()['matchId'];
            $tempFixture = TempFixture::findOrFail($matchId);
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($tempFixture->tournament_id);
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
            'matchId' => 'required',
        ];
    }
}
