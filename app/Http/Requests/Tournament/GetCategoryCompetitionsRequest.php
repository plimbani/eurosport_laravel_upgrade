<?php
namespace Laraspace\Http\Requests\Tournament;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetCategoryCompetitionsRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $token = JWTAuth::getToken();
        if(!$token || (isset($this->headers->all()['ismobileuser'])) && $this->headers->all()['ismobileuser'] == true) {
            if (isset($this->all()['ageGroupId'])) {
                $ageGroupId = $this->all()['ageGroupId'];
                $ageCategory = TournamentCompetationTemplates::findOrFail($ageGroupId);
                $tournament_id = $ageCategory->tournament_id;
                $tournament = Tournament::where('id',$tournament_id)->first();
                $isTournamentPublished = $this->isTournamentPublished($tournament);
                if(!$isTournamentPublished) {
                    return false;
                }
            }
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
            'ageGroupId' => 'required'
        ];
    }
}
