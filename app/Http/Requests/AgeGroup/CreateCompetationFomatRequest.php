<?php

namespace App\Http\Requests\AgeGroup;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TournamentAccess;

class CreateCompetationFomatRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['compeationFormatData'])) {
            $data = $this->all()['compeationFormatData'];
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($data['tournament_id']);
            if (! $isTournamentAccessible) {
                return false;
            }
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
            'compeationFormatData' => 'required | array',
        ];
    }
}
