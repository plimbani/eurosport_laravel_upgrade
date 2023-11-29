<?php

namespace App\Http\Requests\Pitch;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pitch;
use App\Traits\TournamentAccess;

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
