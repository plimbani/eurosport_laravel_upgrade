<?php

namespace Laraspace\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Traits\AuthUserDetail;

class ChangePermissionRequest extends FormRequest
{
    use AuthUserDetail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $loggedInUser = $this->getCurrentLoggedInUserDetail();

        if ($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator') || $loggedInUser->hasRole('tournament.administrator')) {
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
            //
        ];
    }
}
