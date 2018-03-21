<?php

namespace Laraspace\Http\Requests\AgeGroup;

use Laraspace\Models\Pitch;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

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
        $pitchId = $data['pitch_id'];
        $pitch = Pitch::find($pitchId);
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($pitch->tournament_id);        
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
