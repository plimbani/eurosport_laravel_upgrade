<?php

namespace Laraspace\Http\Requests\Visitor;

use Laraspace\Traits\WebsiteAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
{
    use WebsiteAccess;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->all();
        $isWebsiteAccessible = $this->checkForWritePermissionByWebsite($data['websiteId']);
        if(!$isWebsiteAccessible) {
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
