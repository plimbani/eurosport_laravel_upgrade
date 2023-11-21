<?php

namespace Laraspace\Http\Requests\Tournament;

use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Models\Competition;
use Laraspace\Traits\TournamentAccess;

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
