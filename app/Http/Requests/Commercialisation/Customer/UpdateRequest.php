<?php

namespace Laraspace\Http\Requests\Commercialisation\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'organisation' => 'required',
            'job_title' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'country' => 'required',
        ];
    }
}
