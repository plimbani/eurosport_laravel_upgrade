<?php

namespace Laraspace\Http\Requests\Pitch;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\Pitch;
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
        $pitchId = $this->route('deleteid');
        $pitch = Pitch::findOrFail($pitchId);
        $isTournamentAccessible = $this->checkForWritePermissionByTournament($pitch->tournament_id);
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
