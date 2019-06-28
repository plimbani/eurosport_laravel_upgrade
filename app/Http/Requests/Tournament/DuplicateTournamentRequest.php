<?php

namespace Laraspace\Http\Requests\Tournament;

use JWTAuth;
use Laraspace\Models\TournamentUser;
use Illuminate\Foundation\Http\FormRequest;

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
        $tournamentUser = TournamentUser::where('tournament_id', $this->copy_tournament_id)->where('user_id', $authUser->id)->get();

        if($authUser->roles()->first()->slug == 'tournament.administrator') {
            if($tournamentUser->count() == 0) {
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
