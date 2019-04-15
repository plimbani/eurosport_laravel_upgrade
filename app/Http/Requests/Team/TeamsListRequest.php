<?php

namespace Laraspace\Http\Requests\Team;

use JWTAuth;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class TeamsListRequest extends FormRequest
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
        $token = JWTAuth::getToken();
        if (isset($data['tournament_id'])) {
          if(!$token || (isset($this->headers->all()['ismobileuser'])) && $this->headers->all()['ismobileuser'] == true) {
              $tournament_id = $data['tournament_id'];
              $isTournamentAccessible = $this->checkForTournamentReadAccess($data['tournament_id']);
              if(!$isTournamentAccessible) {
                return false;
              }
              return true;
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
            'tournament_id' => 'required'
        ];
    }
}
