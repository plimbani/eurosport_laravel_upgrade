<?php

namespace Laraspace\Http\Requests\Website;

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
        if(isset($data['websiteData']['websiteId']) && $data['websiteData']['websiteId'] != null){
            $isWebsiteAccessible = $this->checkForWritePermissionByWebsite($data['websiteData']['websiteId']);        
            if(!$isWebsiteAccessible) {
                return false;
            }
            return true;     
        } else {
            $loggedInUser = $this->getCurrentLoggedInUserDetail();

            if($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator') || $loggedInUser->hasRole('Internal.administrator')) {
                return true;
            }
            return false;
        }
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
