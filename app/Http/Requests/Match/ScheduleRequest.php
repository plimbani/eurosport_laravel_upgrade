<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TournamentAccess;

class ScheduleRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['data']['matchData'])) {
            $data = $this->all()['data']['matchData'];
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($data['tournamentId']);
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
            'data' => 'required|array',
        ];
    }
}
