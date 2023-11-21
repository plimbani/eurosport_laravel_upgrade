<?php

namespace Laraspace\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Traits\TournamentAccess;

class AllCountriesRequest extends FormRequest
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
