<?php

namespace Laraspace\Http\Requests\Match;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;

class GetFixturesRequest extends FormRequest
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
        if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
            if (isset($this->all()['tournamentData'])) {
                $data = $this->all()['tournamentData'];
                $tournament_id = $data['tournamentId'];
                $client = new HttpClient();
                $login = $client->login();
                $tournament = json_decode($client->post('/tournaments/tournamentSummary', ['Authorization' => 'Bearer '.json_decode($login)->token], ['tournamentId' => $tournament_id]))->data->tournament_detail;
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
            'tournamentData' => 'required'
        ];
    }
}
