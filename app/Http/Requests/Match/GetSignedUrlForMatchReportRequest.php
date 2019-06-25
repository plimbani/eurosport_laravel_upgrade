<?php

namespace Laraspace\Http\Requests\Match;

use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\TournamentCompetationTemplates;

class GetSignedUrlForMatchReportRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $ageCategoryId = $this->ageCategoryData['ageCategory'];
        $ageCategory = TournamentCompetationTemplates::findOrFail($ageCategoryId);
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($ageCategory->tournament_id);
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
