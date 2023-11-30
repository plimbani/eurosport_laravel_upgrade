<?php

namespace App\Http\Requests\AgeGroup;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TournamentCompetationTemplates;
use App\Traits\TournamentAccess;

class DeleteCompetitionFormatRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->all();
        $ageCategoryId = $data['tournamentCompetationTemplateId'];
        $ageCategory = TournamentCompetationTemplates::findOrFail($ageCategoryId);
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($ageCategory->tournament_id);
        if (! $isTournamentAccessible) {
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
            'tournamentCompetationTemplateId' => 'required',
        ];
    }
}
