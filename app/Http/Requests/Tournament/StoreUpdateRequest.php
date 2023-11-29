<?php

namespace App\Http\Requests\Tournament;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TournamentAccess;

class StoreUpdateRequest extends FormRequest
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
        if (isset($data['tournamentData']['tournamentId']) && $data['tournamentData']['tournamentId'] != 0) {
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($data['tournamentData']['tournamentId']);
            if (! $isTournamentAccessible) {
                return false;
            }

            return true;
        } else {
            $loggedInUser = $this->getCurrentLoggedInUserDetail();

            if ($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator')) {
                return true;
            }

            return false;
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
            //
        ];
    }
}
