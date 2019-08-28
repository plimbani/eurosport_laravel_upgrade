<?php

namespace Laraspace\Http\Requests\Template;

use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\TemplateAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use TemplateAccess;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $loggedInUser = $this->getCurrentLoggedInUserDetail();

        if($loggedInUser->hasRole('customer')) {
            if($this->canUserManageTemplate() && $this->isManageTemplateAccessible()) {
                return true;
            }
        }

        if($loggedInUser->hasRole('Super.administrator')) {
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
