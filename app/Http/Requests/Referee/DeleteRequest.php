<?php

namespace Laraspace\Http\Requests\Referee;

use Laraspace\Models\Referee;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

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
        $referee = Referee::find($refereeId);        
        // echo "<pre>";print_r($referee);echo "</pre>";exit;
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($referee->tournament_id);
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
