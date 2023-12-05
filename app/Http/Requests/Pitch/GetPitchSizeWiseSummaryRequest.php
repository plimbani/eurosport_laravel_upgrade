<?php

namespace App\Http\Requests\Pitch;

use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetPitchSizeWiseSummaryRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournamentId = $this->route('tournamentId');
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($tournamentId);
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
