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
        $user = $this->getCurrentLoggedInUserDetail();
        if($user->hasRole('Super.administrator') || $user->hasRole('tournament.administrator') || $user->hasRole('Internal.administrator') || $user->hasRole('Master.administrator')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ageCategoryName' => 'required',
            'tournamentId' => 'required',
        ];
    }
}
