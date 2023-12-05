<?php

namespace App\Http\Requests\User;

use App\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class ResendEmailRequest extends FormRequest
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

        if ($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('tournament.administrator') || $loggedInUser->hasRole('Master.administrator')) {
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
