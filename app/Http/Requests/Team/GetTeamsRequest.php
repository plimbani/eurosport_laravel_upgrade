<?php

namespace App\Http\Requests\Team;

use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetTeamsRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['teamData']['tournamentId'])) {
            $data = $this->all();
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($data['teamData']['tournamentId']);
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
            'teamData' => 'required|array',
        ];
    }
}
