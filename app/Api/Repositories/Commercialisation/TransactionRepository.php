<?php

namespace Laraspace\Api\Repositories\Commercialisation;

use Hash;
use JWTAuth;
use Illuminate\Support\Facades\Mail;
use Laraspace\Mail\SendMail;
use Laraspace\Models\Competition;
use Laraspace\Models\Transaction;
use Laraspace\Models\Tournament;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Position;
use Laraspace\Models\Referee;
use Laraspace\Models\Venue;
use Laraspace\Models\Pitch;
use Laraspace\Models\TransactionHistory;
use Laraspace\Api\Repositories\TournamentRepository;
use Laraspace\Api\Repositories\MatchRepository;
use Laraspace\Models\TournamentCompetationTemplates;

class TransactionRepository
{

    public function __construct()
    {
        $this->tournamentObj = new TournamentRepository();
        $this->matchRepoObj = new MatchRepository();
    }

    /**
     * Add transaction response in db 
     * @param array $requestData
     * @return object
     */
    public function addDetails($requestData)
    {
        $data = array_change_key_case($requestData['paymentResponse'], CASE_UPPER);
        $authUser = JWTAuth::parseToken()->toUser();
        $userId = $authUser->id;

        if ($data['STATUS'] == 5 && !empty($requestData['tournament'])) {
            $tournamentRes = $this->tournamentObj->addTournamentDetails($requestData['tournament'], 'api');

            $tournamentRes->users()->attach($userId);
        }
        $response = $this->addTransaction($data, $tournamentRes, $userId);

        //If renew license then duplicate age category if team size same
        if (!empty($requestData['tournament']['is_renew'])) {
            $oldTournamentRecord = Tournament::findOrFail($requestData['tournament']['old_tournament_id']);

            if ($requestData['tournament']['tournament_max_teams'] == $oldTournamentRecord['maximum_teams']) {

                $oldTournamentRecord = Tournament::orderBy('id', 'desc')->first();

                $oldTournamentCompetitions = Competition::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();
                $oldTournamentAgeCategories = TournamentCompetationTemplates::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();
                $oldTournamentFixtures = TempFixture::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();
                $oldTournamentVenues = Venue::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();
                $oldTournamentPitches = Pitch::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();
                $oldTournamentReferees = Referee::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();

                $venuesMappingArray = [];
                $pitchesMappingArray = [];
                $refereesMappingArray = [];
                $competitionsMappingArray = [];
                $ageCategoriesMappingArray = [];

                if ($oldTournamentAgeCategories) {
                    foreach ($oldTournamentAgeCategories as $ageCategory) {
                        $oldCopiedAgeCategory = $ageCategory->replicate();
                        $oldCopiedAgeCategory->tournament_id = $oldTournamentRecord->id;
                        $oldCopiedAgeCategory->save();
                        $ageCategoriesMappingArray[$ageCategory->id] = $oldCopiedAgeCategory->id;

                        $positions = Position::where('age_category_id', $ageCategory->id)->get();
                        foreach ($positions as $position) {
                            $oldCopiedPositions = $position->replicate();
                            $oldCopiedPositions->age_category_id = $ageCategoriesMappingArray[$position->age_category_id];
                            $oldCopiedPositions->save();
                        }
                    }
                }

                if ($oldTournamentCompetitions) {
                    foreach ($oldTournamentCompetitions as $competition) {
                        $oldCopiedCompetition = $competition->replicate();
                        $oldCopiedCompetition->tournament_competation_template_id = $ageCategoriesMappingArray[$competition->tournament_competation_template_id];
                        $oldCopiedCompetition->tournament_id = $oldTournamentRecord->id;
                        $oldCopiedCompetition->save();
                        $competitionsMappingArray[$competition->id] = $oldCopiedCompetition->id;
                    }
                }

                if ($oldTournamentFixtures) {
                    foreach ($oldTournamentFixtures as $fixture) {
                        $oldCopiedFixture = $fixture->replicate();
                        $oldCopiedFixture->tournament_id = $oldTournamentRecord->id;
                        $oldCopiedFixture->competition_id = $competitionsMappingArray[$fixture->competition_id];
                        $oldCopiedFixture->venue_id = isset($venuesMappingArray[$fixture->venue_id]) ? $venuesMappingArray[$fixture->venue_id] : null;
                        $oldCopiedFixture->age_group_id = isset($ageCategoriesMappingArray[$fixture->age_group_id]) ? $ageCategoriesMappingArray[$fixture->age_group_id] : null;
                        $oldCopiedFixture->referee_id = isset($refereesMappingArray[$fixture->referee_id]) ? $refereesMappingArray[$fixture->referee_id] : null;
                        $oldCopiedFixture->pitch_id = isset($pitchesMappingArray[$fixture->pitch_id]) ? $pitchesMappingArray[$fixture->pitch_id] : null;
                        $oldCopiedFixture->is_scheduled = $fixture->is_schedule = 0;
                        $oldCopiedFixture->match_datetime = $fixture->match_datetime = null;
                        $oldCopiedFixture->match_endtime = $fixture->match_endtime = null;
                        $oldCopiedFixture->minimum_team_interval_flag = $fixture->minimum_team_interval_flag = 0;
                        $oldCopiedFixture->home_team = $fixture->home_team = 0;
                        $oldCopiedFixture->away_team = $fixture->away_team = 0;
                        $oldCopiedFixture->hometeam_score = $fixture->hometeam_score = null;
                        $oldCopiedFixture->awayteam_score = $fixture->awayteam_score = null;
                        $oldCopiedFixture->hometeam_point = $fixture->hometeam_point = null;
                        $oldCopiedFixture->awayteam_point = $fixture->awayteam_point = null;
                        $oldCopiedFixture->home_yellow_cards = $fixture->home_yellow_cards = null;
                        $oldCopiedFixture->away_yellow_cards = $fixture->away_yellow_cards = null;
                        $oldCopiedFixture->home_red_cards = $fixture->home_red_cards = null;
                        $oldCopiedFixture->away_red_cards = $fixture->away_red_cards = null;
                        $oldCopiedFixture->age_category_color = $fixture->age_category_color = null;
                        $oldCopiedFixture->group_color = $fixture->group_color = null;
                        $oldCopiedFixture->bracket_json = $fixture->bracket_json = null;

                        $oldCopiedFixture->save();
                    }
                }
            }
        }

        if ($data['STATUS'] == 5) {
            //Send conformation mail to customer
            $subject = 'Message from Eurosport';
            $email_templates = 'emails.frontend.payment_confirmed';
            $emailData = ['paymentResponse' => $requestData['paymentResponse'], 'tournament' => $requestData['tournament'], 'user' => $authUser->profile];
            Mail::to($authUser->email)
                    ->send(new SendMail($emailData, $subject, $email_templates, NULL, NULL, NULL));
        }

        return $response;
    }

    /**
     * Add payment details into transaction
     * @param array $data
     * @param array $tournamentRes
     * @param int $userId
     * @return array
     */
    public function addTransaction($data, $tournamentRes, $userId)
    {
        $paymentStatus = config('app.payment_status');
        $transaction = [
            'tournament_id' => !empty($tournamentRes->id) ? $tournamentRes->id : null,
            'user_id' => $tournamentRes->user_id,
        ];
        $response = Transaction::create($transaction);

        //Add in transaction history
        $transactionHistory = [
            'transaction_id' => $response->id,
            'order_id' => $data['ORDERID'],
            'transaction_key' => $data['PAYID'],
            'team_size' => $tournamentRes->maximum_teams,
            'amount' => number_format($data['AMOUNT'], 2, '.', ''),
            'status' => $paymentStatus[$data['STATUS']],
            'currency' => $data['CURRENCY'],
            'card_type' => $data['PM'],
            'card_holder_name' => $data['CN'],
            'card_number' => $data['CARDNO'],
            'card_validity' => $data['ED'],
            'transaction_date' => date('Y-m-d H:i:s', strtotime($data['TRXDATE'])),
            'brand' => $data['BRAND'],
            'payment_response' => json_encode($data)
        ];
        TransactionHistory::create($transactionHistory);       
        $responseData = array_merge($transactionHistory, $transaction);

        return $responseData;
    }

    /**
     * Update transaction if customer update tournament from manage tournament
     * @param array $tournament
     * @param array $data
     */
    public function updateTransaction($requestData)
    {
        $data = array_change_key_case($requestData['paymentResponse'], CASE_UPPER);
        $tournament = $requestData['tournament'];
        $authUser = JWTAuth::parseToken()->toUser();
        $paymentStatus = config('app.payment_status');
        $existsTransaction = Transaction::where('tournament_id', $tournament['old_tournament_id'])->first();        
        $existsTransaction->tournament->preventDateAttrSet = true;

        $mainTransaction = [
            'updated_at' => date('Y-m-d H:i:s')
        ];
    
        if (empty($tournament['tournamentPricingValue'])) {
            $transaction = [
                'transaction_id' => $existsTransaction['id'],
                'amount' => $tournament['tournamentPricingValue'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
        } else {
            $existsHistory = TransactionHistory::where('transaction_id', $existsTransaction['id'])->orderBy('id', 'desc')->first();
            $transaction = [
                'transaction_id' => $existsTransaction['id'],
                'order_id' => $data['ORDERID'],
                'transaction_key' => $data['PAYID'],
                'team_size' => $tournament['tournament_max_teams'],
                'amount' => number_format($data['AMOUNT'] + $existsHistory['amount'], 2, '.', ''),
                'status' => $paymentStatus[$data['STATUS']],
                'currency' => $data['CURRENCY'],
                'card_type' => $data['PM'],
                'card_holder_name' => $data['CN'],
                'card_number' => $data['CARDNO'],
                'card_validity' => $data['ED'],
                'transaction_date' => date('Y-m-d H:i:s', strtotime($data['TRXDATE'])),
                'brand' => $data['BRAND'],
                'payment_response' => json_encode($data),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        Transaction::where('tournament_id', $tournament['id'])
                ->update($mainTransaction);
        $result = '';
        if (!empty($data)) {
            $result = TransactionHistory::create($transaction);
        }
        if ($data['STATUS'] == 5) {
            //Send conformation mail to customer
            $subject = 'Message from Eurosport';
            $email_templates = 'emails.frontend.payment_confirmed';
            $emailData = ['paymentResponse' => $requestData['paymentResponse'], 'tournament' => $requestData['tournament'], 'user' => $authUser->profile];
            Mail::to($authUser->email)
                    ->send(new SendMail($emailData, $subject, $email_templates, NULL, NULL, NULL));
        }
        return $result;
    }

    /**
     * Get transaction list
     * @param int $tournamentId
     * @return object
     */
    public function getList($tournamentId)
    {
        $transaction = Transaction::with('transactionHistories')->with('tournament')
                        ->where('tournament_id', $tournamentId)->first();
        
        $response = [];
        if (!empty($transaction)) {
            foreach ($transaction->transactionHistories as $key => $history) {                
                $response[$key] = [
                    'id' => $history->id,
                    'transaction_id' => $history->transaction_id,
                    'order_id' => $history->order_id,
                    'transaction_key' => $history->transaction_key,
                    'amount' => $history->amount,
                    'team_size' => $history->team_size,
                    'start_date' => $transaction->tournament->start_date,
                    'end_date' => $transaction->tournament->end_date,
                    'currency' => $history->currency,
                    'created_at' => $history->created_at,
                ];
            }
        }
        return $response;
    }

}
