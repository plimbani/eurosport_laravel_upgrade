<?php

namespace Laraspace\Http\Requests\AgeGroup;

use JWTAuth;
use Laraspace\Models\Tournament;
use Laraspace\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;

class GetCompetationFormatRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        \Log::info('GetCompetationFormatRequest:' .json_encode($this->all()));
        $token = JWTAuth::getToken();
        if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
            $tournament_id = null;
            if(app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true") {
                $tournament_id = $this->all()['tournament_id'];
            } else {
                $data = $this->all()['tournamentData'];
                $tournament_id = $data['tournament_id'];
            }

            $client = new HttpClient();
            $login = $client->login();
            $tournament = json_decode($client->post('/tournaments/tournamentSummary', ['Authorization' => 'Bearer '.json_decode($login)->token], ['tournamentId' => $tournament_id]))->data->tournament_detail;
            $isTournamentPublished = $this->isTournamentPublished($tournament);
            if(!$isTournamentPublished) {
                return false;
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
            //
        ];
    }
}
