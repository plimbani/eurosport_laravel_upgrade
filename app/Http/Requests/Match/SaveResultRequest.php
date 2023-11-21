<?php

namespace Laraspace\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\TempFixture;
use Laraspace\Traits\TournamentAccess;

class SaveResultRequest extends FormRequest
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
        if (isset($data['matchData']['matchId'])) {
            $matchId = $data['matchData']['matchId'];
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
            'matchData' => 'required|array',
        ];
    }
}
