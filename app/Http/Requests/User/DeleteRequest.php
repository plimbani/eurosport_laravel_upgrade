<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Traits\AuthUserDetail;

class DeleteRequest extends FormRequest
{
    use AuthUserDetail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('id');
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        if ($loggedInUser->id == $id) {
            return true;
        }

        if (! ($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator'))) {
            return false;
        }

        $user = User::findOrFail($id)->roles()->first();
        if ($user['slug'] == 'Super.administrator' && $loggedInUser->hasRole('Master.administrator')) {
            return false;
        }
        if ($user['slug'] == 'mobile.user' && $loggedInUser->hasRole('Master.administrator')) {
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
