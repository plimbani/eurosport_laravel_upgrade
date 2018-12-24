<?php

namespace Laraspace\Http\Requests\Commercialisation\BuyLicense;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
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
            'tournament_name' => 'required',
            'tournament_max_teams' => 'required',
            'tournament_start_date' => 'required',
            'tournament_end_date' => 'required',
        ];
    }
}
