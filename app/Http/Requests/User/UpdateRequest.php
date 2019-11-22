<?php

namespace Laraspace\Http\Requests\User;

use Laraspace\Models\User;
use Laraspace\Models\Role;
use Laraspace\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

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
        $currentLayout = config('config-variables.current_layout');
        if($loggedInUser->hasRole('tournament.administrator') && $usersRole->slug == 'Results.administrator') {
          return true;
        }
        if(!($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator'))) {
          if($id != $loggedInUser->id) {
            return false;
          }
          if(isset($request['userType']) && $usersRole->id != $request['userType']) {
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
        if (isset($request['userType'])) {
            $userType = $request['userType'];
            if ($currentLayout === 'commercialisation' && ($userType == 'tournament.administrator' || $userType == 'Master.administrator' || $userType == 'Results.administrator')) {
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
