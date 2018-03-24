<?php

namespace Laraspace\Http\Requests\Tournament;

use Laraspace\Models\Competition;
use Laraspace\Traits\TournamentAccess;
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
            $competitions = Competition::whereIn('id', $competitionIds)->get();
            foreach ($competitions as $competition) {
                $tournamentIds[] = $competition['tournament_id'];
            }
            $tournamentIdsArray = array_unique($tournamentIds);
            $isTournamentAccessible = $this->checkForMultipleTournamentAccess($tournamentIdsArray);
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
            'competitionsColorData' => 'required | array',
        ];
    }
}
