<?php

namespace Laraspace\Http\Requests\Commercialisation\Pricing;

use Laraspace\Models\Role;
use Laraspace\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
{
    use AuthUserDetail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $loggedInUser = $this->getCurrentLoggedInUserDetail();

        if($loggedInUser->hasRole('Super.administrator') || $loggedInUser->hasRole('Master.administrator') || $loggedInUser->hasRole('Internal.administrator')) {
            return true;
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
            'tournamentPricingData.data.*.min_teams' => 'required',
            'tournamentPricingData.data.*.max_teams' => 'required',
            'tournamentPricingData.data.*.price' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'tournamentPricingData.data.*.min_teams' => 'The Min teams field is required.',
            'tournamentPricingData.data.*.max_teams' => 'The Max teams field is required.',
            'tournamentPricingData.data.*.price' => 'The Basic price field is required.'
        ];
    }    
}
