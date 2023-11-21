<?php

namespace Laraspace\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Traits\TournamentAccess;

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
        $tournamentId = $this->ageCategoryData['tournament_id'];
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($tournamentId);
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
            //
        ];
    }
}
