<?php

namespace App\Http\Requests\Team;

use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class AllTeamColorsRequest extends FormRequest
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
        if ($user->hasRole('mobile.user')) {
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
