<?php

namespace App\Http\Requests\Referee;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TournamentAccess;

class GetRefereesRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['tournamentData'])) {
            $data = $this->all()['tournamentData'];
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
            'tournamentData' => 'required|array',
        ];
    }
}
