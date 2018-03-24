<?php

namespace Laraspace\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RemoveFavouriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->headers->all()['ismobileuser'])) {
            $isMobileUser = $this->headers->all()['ismobileuser'];
            if ($isMobileUser == true) {
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
