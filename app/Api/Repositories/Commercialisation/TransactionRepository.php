<?php

namespace Laraspace\Api\Repositories\Commercialisation;

use Hash;
use JWTAuth;
use Illuminate\Support\Facades\Mail;
use Laraspace\Mail\SendMail;
use Laraspace\Models\Transaction;
use Laraspace\Models\TransactionHistory;
use Laraspace\Api\Repositories\TournamentRepository;

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
        if (!empty($requestData['is_renew'])) {
            
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
            'order_id' => $data['ORDERID'],
            'transaction_key' => $data['PAYID'],
            'team_size' => $tournamentRes->maximum_teams,
            'amount' => $data['AMOUNT'],
            'status' => $paymentStatus[$data['STATUS']],
            'currency' => $data['CURRENCY'],
            'card_type' => $data['PM'],
            'card_holder_name' => $data['CN'],
            'card_number' => $data['CARDNO'],
            'card_validity' => $data['ED'],
            'transaction_date' => $data['TRXDATE'],
            'brand' => $data['BRAND'],
            'payment_response' => json_encode($data)
        ];
        $response = Transaction::create($transaction);

        //Add in transaction history
        $transactionHistory = [
            'transaction_id' => $response->id,
            'tournament_id' => !empty($tournamentRes->id) ? $tournamentRes->id : null,
            'order_id' => $data['ORDERID'],
            'transaction_key' => $data['PAYID'],
            'team_size' => $tournamentRes->maximum_teams,
            'amount' => $data['AMOUNT'],
            'status' => $paymentStatus[$data['STATUS']],
            'currency' => $data['CURRENCY'],
            'card_type' => $data['PM'],
            'card_holder_name' => $data['CN'],
            'card_number' => $data['CARDNO'],
            'card_validity' => $data['ED'],
            'transaction_date' => $data['TRXDATE'],
            'brand' => $data['BRAND'],
            'payment_response' => json_encode($data)
        ];
        TransactionHistory::create($transactionHistory);

        return $response;
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
        $userId = $authUser->id;
        $paymentStatus = config('app.payment_status');
        $existsTransaction = Transaction::where('tournament_id', $tournament['id'])->first();

        if (empty($tournament['total_amount'])) {
            $transaction = [
                'user_id' => $userId,
                'amount' => $tournament['total_amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
        } elseif ($tournament['total_amount'] == 0) {
            
        } else {
            $transaction = [
                'order_id' => $data['ORDERID'],
                'transaction_key' => $data['PAYID'],
                'team_size' => $tournament['tournament_max_teams'],
                'amount' => $data['AMOUNT'] + $existsTransaction['amount'],
                'status' => $paymentStatus[$data['STATUS']],
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

        $result = Transaction::where('tournament_id', $tournament['id'])->update($transaction);

        if (!empty($data)) {
            $transaction['transaction_id'] = $existsTransaction['id'];
            $transaction['tournament_id'] = $tournament['id'];
            TransactionHistory::create($transaction);
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
        return TransactionHistory::select('id', 'transaction_id', 'tournament_id', 'order_id', 'transaction_key', 'team_size', 'currency', 'created_at')
                        ->where('tournament_id', $tournamentId)->get();
    }
}
