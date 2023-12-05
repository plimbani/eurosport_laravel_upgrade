<?php

namespace App\Http\Requests\Match;

use App\Models\Referee;
use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetSignedUrlForRefereeReportRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $refereeId = $this->route('refereeId');
        $referee = Referee::findOrFail($refereeId);
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($referee->tournament_id);
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
