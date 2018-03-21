<?php

namespace Laraspace\Http\Requests\Match;

use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class AssignRefereeRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['data'])) {
            $data = $this->all()['data'];
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($data['tournamentId']);        
            if(!$isTournamentAccessible) {
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
            'data' => 'required | array'
        ];
    }
}
