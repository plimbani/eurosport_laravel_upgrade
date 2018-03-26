<?php

namespace Laraspace\Http\Requests\AgeGroup;

use Laraspace\Traits\TournamentAccess;
use Laraspace\Models\TournamentCompetationTemplates;
use Illuminate\Foundation\Http\FormRequest;

class TeamDetailsRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $ageCategoryId = $this->all()['ageCategoryId'];
        $ageCategory = TournamentCompetationTemplates::findOrFail($ageCategoryId);
        $isTournamentAccessible = $this->checkForTournamentReadAccess($ageCategory->tournament_id);
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
