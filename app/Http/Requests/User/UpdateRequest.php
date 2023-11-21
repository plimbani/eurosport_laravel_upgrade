<?php

namespace Laraspace\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\Role;
use Laraspace\Models\User;
use Laraspace\Traits\AuthUserDetail;

class UpdateRequest extends FormRequest
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
        $request = $this->all();
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        $usersRole = User::findOrFail($id)->roles()->first();
        if ($loggedInUser->hasRole('tournament.administrator') && $usersRole->slug == 'Results.administrator') {
            return true;
        }
        if (! ($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator'))) {
            if ($id != $loggedInUser->id) {
                return false;
            }
            if (isset($request['userType']) && $usersRole->id != $request['userType']) {
                return false;
            }

            return true;
        }
        if (isset($request['userType'])) {
            $userType = $request['userType'];
            $role = Role::findOrFail($userType);
            if (($usersRole->slug == 'Super.administrator' || $role->slug == 'Super.administrator') && $loggedInUser->hasRole('Master.administrator')) {
                return false;
            }
            if (($usersRole->slug == 'mobile.user' || $role->slug == 'mobile.user') && $loggedInUser->hasRole('Master.administrator')) {
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
        ];
    }
}
