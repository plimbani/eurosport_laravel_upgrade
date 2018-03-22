<?php

namespace Laraspace\Http\Requests\Website;

use Laraspace\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class GetWebsiteDefaultPagesRequest extends FormRequest
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

        if($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator') || $loggedInUser->hasRole('Internal.administrator')) {
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
