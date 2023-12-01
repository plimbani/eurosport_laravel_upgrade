<?php

namespace App\Http\Requests\Tournament;

use App\Models\Competition;
use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class CategoryCompetitionColorRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->all()['competitionsColorData'])) {
            $competitionsColorData = $this->all()['competitionsColorData'];
            $competitionIds = array_keys($competitionsColorData);
            $tournamentIds = Competition::whereIn('id', $competitionIds)->pluck('tournament_id')->unique()->toArray();
            $isTournamentAccessible = $this->checkForMultipleTournamentAccess($tournamentIds);
            if (! $isTournamentAccessible) {
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
            'competitionsColorData' => 'required | array',
        ];
    }
}
