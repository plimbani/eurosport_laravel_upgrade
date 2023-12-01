<?php

namespace App\Http\Requests\Match;

use App\Models\TempFixture;
use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetSignedUrlForMatchPrintRequest extends FormRequest
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
        $matchId = $data['matchId'];
        $tempFixture = TempFixture::findOrFail($matchId);
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($tempFixture->tournament_id);
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
