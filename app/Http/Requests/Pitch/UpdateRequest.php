<?php

namespace Laraspace\Http\Requests\Pitch;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Traits\TournamentAccess;

class UpdateRequest extends FormRequest
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
