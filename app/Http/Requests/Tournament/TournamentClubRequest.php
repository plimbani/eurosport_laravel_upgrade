<?php

namespace Laraspace\Http\Requests\Tournament;

use JWTAuth;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class TournamentClubRequest extends FormRequest
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
        if(isset($data['tournament_id'])) {
          if(isset($this->headers->all()['ismobileuser'])) && $this->headers->all()['ismobileuser'] == true) {
              if(!$token || (isset($this->headers->all()['ismobileuser'])) && $this->headers->all()['ismobileuser'] == true) {
                  
                  $currentLayout = config('config-variables.current_layout');
                  if($currentLayout == 'commercialisation'){
                      $tournament_id = $data['tournament_id'];

                      $user = $this->getCurrentLoggedInUserDetail();
                      $checkForTournamentAccess = $this->isTournamentAccessible($user, $tournament_id);
                      \Log::info($checkForTournamentAccess);
                      if(!$checkForTournamentAccess) {
                          return false;
                      } 
                  }
                  
                  $isTournamentAccessible = $this->checkForTournamentReadAccess($data['tournament_id']);
                  if(!$isTournamentAccessible) {
                    return false;
                  }
                  return true;
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
            'tournament_id' => 'required'
        ];
    }
}
