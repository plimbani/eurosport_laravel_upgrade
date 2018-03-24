<?php

namespace Laraspace\Http\Requests\Website;

use Laraspace\Traits\WebsiteAccess;
use Illuminate\Foundation\Http\FormRequest;

class SummaryRequest extends FormRequest
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
        $isTournamentAccessible = $this->checkForWritePermissionByWebsite($data['websiteId']);
        if(!$isTournamentAccessible) {
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
