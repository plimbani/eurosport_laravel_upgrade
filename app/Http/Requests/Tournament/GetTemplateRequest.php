<?php

namespace App\Http\Requests\Tournament;

use App\Models\TournamentCompetationTemplates;
use App\Traits\AuthUserDetail;
use Illuminate\Foundation\Http\FormRequest;

class GetTemplateRequest extends FormRequest
{
    use AuthUserDetail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournamentTemplateId = $this->all()['tournamentTemplateId'];
        $user = $this->getCurrentLoggedInUserDetail();
        if ($user->hasRole('tournament.administrator')) {
            $accessibleTournamentsIds = $user->tournaments()->pluck('id')->toArray();
            $accessibleTemplates = TournamentCompetationTemplates::whereIn('tournament_id', $accessibleTournamentsIds)->pluck('tournament_template_id')->unique()->toArray();
            if (! in_array($tournamentTemplateId, $accessibleTemplates)) {
                return false;
            }
        }
        if ($user->hasRole('mobile.user')) {
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
