<?php

namespace App\Http\Requests\User;

use App\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class GetUserDetailsRequest extends FormRequest
{
    use AuthUserDetail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->all()['userData'];
        $userEmail = $data['email'];
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        if ($userEmail == $loggedInUser['email']) {
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
