<?php

namespace Laraspace\Http\Requests\Commercialisation\BuyLicense;

use Laraspace\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class CustomerTransactionsRequest extends FormRequest
{
    use AuthUserDetail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->getCurrentLoggedInUserDetail();
        if($user->hasRole('Master.administrator') || $user->hasRole('Super.administrator')) {
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
            'tournament_id' => 'required',
        ];
    }
}
