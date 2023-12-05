<?php

namespace App\Http\Requests\Tournament;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;

class DuplicateTournamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authUser = JWTAuth::parseToken()->toUser();
        if ($authUser->roles()->first()->slug == 'tournament.administrator') {
            $userTournaments = $authUser->tournaments()->where('tournament_id', $this->copy_tournament_id)->get();
            if ($userTournaments->count() == 0) {
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
            //
        ];
    }
}
