<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\WebsiteAccess;

class GetProgramPageDataRequest extends FormRequest
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
