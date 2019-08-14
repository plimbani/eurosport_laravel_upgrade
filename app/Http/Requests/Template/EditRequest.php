<?php

namespace Laraspace\Http\Requests\Template;

use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\TemplateAccess;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    use TemplateAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $templateId = $this->route('id');
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        if($loggedInUser->hasRole('Super.administrator')) {
            return true;
        }

        if($loggedInUser->hasRole('customer')) {
            $isTemplateAccessible = $this->checkForTemplateAccess($templateId);
            if($isTemplateAccessible) {
                return true;
            }
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
