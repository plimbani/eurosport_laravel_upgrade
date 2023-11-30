<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Traits\AuthUserDetail;

class EditRequest extends FormRequest
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
        $user = User::findOrFail($id)->roles()->first();
        $loggedInUser = $this->getCurrentLoggedInUserDetail();

        if (! ($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator') || $loggedInUser->hasRole('tournament.administrator'))) {
            if ($id != $loggedInUser->id) {
                return false;
            }
        }
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
