<?php

namespace App\Http\Requests\User;

//use Illuminate\Foundation\Http\FormRequest;
use Dingo\Api\Http\FormRequest;

class RemoveFavouriteTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (app('request')->header('ismobileuser')) {
            $isMobileUser = app('request')->header('ismobileuser');
            if ($isMobileUser == 'true') {
                return true;
            }
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
            'tournament_id' => 'required|exists:tournaments,id',
            'team_id' => 'required|exists:teams,id',
        ];
    }
}
