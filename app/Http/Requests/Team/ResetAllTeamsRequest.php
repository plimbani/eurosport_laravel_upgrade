<?php

namespace Laraspace\Http\Requests\Team;

use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class ResetAllTeamsRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['ageCategoryId'])) {
            $data = $this->all()['ageCategoryId'];
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
            'ageCategoryId' => 'required | array'
        ];
    }
}
