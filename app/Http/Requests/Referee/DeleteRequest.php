<?php

namespace Laraspace\Http\Requests\Referee;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\Referee;
use Laraspace\Traits\TournamentAccess;

class DeleteRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $refereeId = $this->route('deleteid');
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
