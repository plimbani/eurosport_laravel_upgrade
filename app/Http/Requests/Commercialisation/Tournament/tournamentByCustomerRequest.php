<?php

namespace Laraspace\Http\Requests\Commercialisation\Tournament;

use Illuminate\Foundation\Http\FormRequest;

class TournamentByCustomerRequest extends FormRequest
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
            'customer_id' => 'required',
        ];
    }
}
