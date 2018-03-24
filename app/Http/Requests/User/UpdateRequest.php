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
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        if(!($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator'))) {
          if($id != $loggedInUser->id) {
            return false;
          }
        }
        $user = User::find($id)->roles()->first();
        if (isset($this->all()['userType'])) {
            $userType = $this->all()['userType'];
            $role = Role::find($userType);
            if (($user['slug'] == 'Super.administrator' || $role['slug'] == 'Super.administrator') && $loggedInUser->hasRole('Master.administrator')) {
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
            'userType' => 'required'
        ];
    }
}
