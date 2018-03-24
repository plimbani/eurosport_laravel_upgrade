<?php

namespace Laraspace\Http\Requests\User;

use Laraspace\Models\Role;
use Laraspace\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use AuthUserDetail;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->headers->all()['ismobileuser'])) {
            $isMobileUser = $this->headers->all()['ismobileuser'];
            if ($isMobileUser == true) {
                return true;            
            }
        }
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        if($loggedInUser->hasRole('Super.administrator')) {
            return true;
        }
        if($loggedInUser->hasRole('Master.administrator')) {
            if (isset($this->all()['userType'])) {            
                $userType = $this->all()['userType'];
                $role = Role::find($userType);
                if ( !($role['slug'] == 'Super.administrator') ) {
                    return true;
                }
            }
            return false;
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
