<?php

namespace Laraspace\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Traits\TournamentAccess;

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
        if ($user->hasRole('Super.administrator') || $user->hasRole('tournament.administrator') || $user->hasRole('Internal.administrator') || $user->hasRole('Master.administrator') || $user->hasRole('customer')) {
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
            'ageCategoryId' => 'required',
            'tournamentId' => 'required',
        ];
    }
}
