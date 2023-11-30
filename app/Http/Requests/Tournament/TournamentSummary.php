<?php

namespace App\Http\Requests\Tournament;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TournamentAccess;

class TournamentSummary extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['tournamentId'])) {
            $tournamentId = $this->all()['tournamentId'];
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($tournamentId);
            if (! $isTournamentAccessible) {
                return false;
            }
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
            'tournamentId' => 'required',
        ];
    }
}
