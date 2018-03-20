<?php

namespace Laraspace\Http\Requests\Referee;

use Laraspace\Models\Referee;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class RefereeDetailRequest extends FormRequest
{
    use TournamentAccess;
 
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['refereeId'])) {
            $refereeId = $this->all()['refereeId'];
            $referee = Referee::find($refereeId)->first();
            $isTournamentAccessible = $this->checkForWritePermissionByTournament($referee->tournament_id);
            if(!$isTournamentAccessible) {
                return false;
            }
            return true;
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
            'refereeId' => 'required',
        ];
    }
}
