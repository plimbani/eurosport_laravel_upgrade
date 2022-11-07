<?php

namespace Laraspace\Http\Requests\User;

use Laraspace\Models\User;
use Laraspace\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

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
        $currentLayout = config('config-variables.current_layout');
        if ($loggedInUser->id == $id) {
            return true;
        }

        if(!($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator'))) {
          return false;
        }
        
        $user = User::findOrFail($id)->roles()->first();
        if ($user['slug'] == 'Super.administrator' && $loggedInUser->hasRole('Master.administrator')) {
            return false;
        }
        if ($user['slug'] == 'mobile.user' && $loggedInUser->hasRole('Master.administrator')) {
            return false;
        }
        if ($currentLayout === 'commercialisation' && ($user['slug'] == 'tournament.administrator' || $user['slug'] == 'Master.administrator' || $user['slug'] == 'Results.administrator')) {
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
