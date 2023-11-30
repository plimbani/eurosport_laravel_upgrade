<?php

namespace App\Http\Requests\Pitch;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TournamentAccess;

class GetLocationWiseSummaryRequest extends FormRequest
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
        $isTournamentAccessible = $this->checkForTournamentReadAccess($tournamentId);
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
