<?php

namespace Laraspace\Http\Requests\Venue;

use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetVenueRequest extends FormRequest
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
        if(!$isTournamentAccessible) {
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
