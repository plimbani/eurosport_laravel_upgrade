<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Role;
use App\Traits\AuthUserDetail;

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
        if (app('request')->header('ismobileuser')) {
            $isMobileUser = app('request')->header('ismobileuser');
            if ($isMobileUser == 'true') {
                return true;
            }
        }
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        if ($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('tournament.administrator')) {
            return true;
        }
        if ($loggedInUser->hasRole('Master.administrator')) {
            if (isset($this->all()['userType'])) {
                $userType = $this->all()['userType'];
                $role = Role::findOrFail($userType);
                if (! ($role['slug'] == 'Super.administrator' || $role['slug'] == 'mobile.user')) {
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
