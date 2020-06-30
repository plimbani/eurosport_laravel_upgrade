<?php

namespace Laraspace\Http\Requests\Website;

use Laraspace\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class GetWebsiteConfigurationDetailRequest extends FormRequest
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
        $currentLayout = config('config-variables.current_layout');

        if($loggedInUser->hasRole('mobile.user')) {
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
