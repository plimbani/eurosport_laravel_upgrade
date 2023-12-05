<?php

namespace App\Http\Requests\Tournament;

use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class PublishRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->all()['tournamentData'];
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($data['tournamentId']);
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
