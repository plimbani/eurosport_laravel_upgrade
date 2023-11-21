<?php

namespace Laraspace\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Traits\WebsiteAccess;

class GetContactDataRequest extends FormRequest
{
    use WebsiteAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $websiteId = $this->route('websiteId');
        $isWebsiteAccessible = $this->checkForWritePermissionByWebsite($websiteId);
        if (! $isWebsiteAccessible) {
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
