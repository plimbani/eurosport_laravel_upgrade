<?php

namespace App\Http\Requests\Tournament;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TournamentAccess;

class GenerateReportRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['tournament_id'])) {
            $tournamentId = $this->all()['tournament_id'];
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($tournamentId);
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
            'tournament_id' => 'required',
        ];
    }
}
