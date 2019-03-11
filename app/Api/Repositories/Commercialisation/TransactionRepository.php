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
use Laraspace\Models\TournamentCompetationTemplates;

class TransactionRepository
{

    public function __construct()
    {
        $this->tournamentObj = new TournamentRepository();
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
    
            $oldTournamentCompetitions = Competition::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();

            $oldTournamentAgeCategories = TournamentCompetationTemplates::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();
        
            $oldTournamentFixtures = TempFixture::where('tournament_id',  $requestData['tournament']['old_tournament_id'])->get();
        
            $oldTournamentVenues = Venue::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();
            
            $oldTournamentPitches = Pitch::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();

            $oldTournamentReferees = Referee::where('tournament_id', $requestData['tournament']['old_tournament_id'])->get();

            $venuesMappingArray = [];
            $pitchesMappingArray = [];
            $refereesMappingArray = [];
            $competitionsMappingArray = [];
            $ageCategoriesMappingArray = [];

            $newCopiedTournament = $oldTournamentRecord->replicate();
            $newCopiedTournament->save();


            if($oldTournamentAgeCategories) {
                foreach ($oldTournamentAgeCategories as $ageCategory) {
                    $oldCopiedAgeCategory = $ageCategory->replicate();
                    $oldCopiedAgeCategory->tournament_id = $newCopiedTournament->id;
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

            if($oldTournamentCompetitions) {
                foreach ($oldTournamentCompetitions as $competition) {
                    $oldCopiedCompetition = $competition->replicate();
                    $oldCopiedCompetition->tournament_competation_template_id = $ageCategoriesMappingArray[$competition->tournament_competation_template_id];
                    $oldCopiedCompetition->tournament_id = $newCopiedTournament->id;
                    $oldCopiedCompetition->save();
                    $competitionsMappingArray[$competition->id] = $oldCopiedCompetition->id;
                }
            }


            if($oldTournamentFixtures) {
                foreach ($oldTournamentFixtures as $fixture) {
                    $oldCopiedFixture = $fixture->replicate();
                    $oldCopiedFixture->tournament_id = $newCopiedTournament->id;
                    $oldCopiedFixture->competition_id = $competitionsMappingArray[$fixture->competition_id];
                    $oldCopiedFixture->venue_id = isset($venuesMappingArray[$fixture->venue_id]) ? $venuesMappingArray[$fixture->venue_id] : null;
                    $oldCopiedFixture->age_group_id = isset($ageCategoriesMappingArray[$fixture->age_group_id]) ? $ageCategoriesMappingArray[$fixture->age_group_id] : null;
                    $oldCopiedFixture->referee_id = isset($refereesMappingArray[$fixture->referee_id]) ? $refereesMappingArray[$fixture->referee_id] : null;
                    $oldCopiedFixture->pitch_id = isset($pitchesMappingArray[$fixture->pitch_id]) ? $pitchesMappingArray[$fixture->pitch_id] : null;
                    $oldCopiedFixture->is_scheduled = isset($fixture->is_schedule) ? $fixture->is_schedule : 0;
                    $oldCopiedFixture->save();
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
        $days = $this->getTournamentDays($tournamentRes->start_date, $tournamentRes->end_date);
        $transaction = [
            'tournament_id' => !empty($tournamentRes->id) ? $tournamentRes->id : null,
        ];
        $response = Transaction::create($transaction);

        //Add in transaction history
        $transactionHistory = [
            'transaction_id' => $response->id,
            'order_id' => $data['ORDERID'],
            'transaction_key' => $data['PAYID'],
            'team_size' => $tournamentRes->maximum_teams,
            'amount' => $data['AMOUNT'],
            'status' => $paymentStatus[$data['STATUS']],
            // 'days' => $days,
            'currency' => $data['CURRENCY'],
            'card_type' => $data['PM'],
            'card_holder_name' => $data['CN'],
            'card_number' => $data['CARDNO'],
            'card_validity' => $data['ED'],
            'transaction_date' => $data['TRXDATE'],
            'brand' => $data['BRAND'],
            'payment_response' => json_encode($data)
        ];
        return TransactionHistory::create($transactionHistory);
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
        $existsTransaction = Transaction::where('tournament_id', $tournament['id'])->first();
        $existsTransaction->tournament->preventDateAttrSet = true;

        $mainTransaction = [
            'updated_at' => date('Y-m-d H:i:s')
        ];
        if (empty($tournament['total_amount'])) {
            $transaction = [
                'transaction_id' => $existsTransaction['id'],
                'amount' => $tournament['total_amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
        } else {
            $days = $this->getTournamentDays($tournament['tournament_start_date'], $tournament['tournament_end_date'], $existsTransaction);
            $existsHistory = TransactionHistory::where('transaction_id', $existsTransaction['id'])->orderBy('id', 'desc')->first();

            $transaction = [
                'transaction_id' => $existsTransaction['id'],
                'order_id' => $data['ORDERID'],
                'transaction_key' => $data['PAYID'],
                'team_size' => $tournament['tournament_max_teams'],
                'amount' => $data['AMOUNT'] + $existsHistory['amount'],
                'status' => $paymentStatus[$data['STATUS']],
                'days' => $days,
                'currency' => $data['CURRENCY'],
                'card_type' => $data['PM'],
                'card_holder_name' => $data['CN'],
                'card_number' => $data['CARDNO'],
                'card_validity' => $data['ED'],
                'transaction_date' => $data['TRXDATE'],
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
        $transaction = Transaction::with('transactionHistories')
                        ->where('tournament_id', $tournamentId)->get();
        $response = [];
        if (!empty($transaction)) {
            foreach ($transaction[0]->transactionHistories as $key => $history) {
                $response[$key] = [
                    'id' => $history->id,
                    'transaction_id' => $history->transaction_id,
                    'order_id' => $history->order_id,
                    'transaction_key' => $history->transaction_key,
                    'amount' => $history->amount,
                    'team_size' => $history->team_size,
                    'days' => $history->days,
                    'currency' => $history->currency,
                    'created_at' => $history->created_at,
                ];
            }
        }
        return $response;
    }

    /**
     * Get tournament days
     * @param array $tournament
     * @param object $existsTransaction     
     * @return string
     */
    private function getTournamentDays($tNewSDate, $tNewEDate, $existsTransaction = null)
    {
//        $earlier = new \DateTime(date('Y-m-d', strtotime($existsTransaction->tournament->start_date)));
//        $later = new \DateTime(date('Y-m-d', strtotime($existsTransaction->tournament->end_date)));
//        $preDiff = $later->diff($earlier)->days;

        $sDate = new \DateTime(date('Y-m-d', strtotime(str_replace('/', '-', $tNewSDate))));
        $eDate = new \DateTime(date('Y/m/d', strtotime(str_replace('/', '-', $tNewEDate))));
        $newDiff = $eDate->diff($sDate)->days;

//        return $newDiff - $preDiff;
        return $newDiff;
    }
}
