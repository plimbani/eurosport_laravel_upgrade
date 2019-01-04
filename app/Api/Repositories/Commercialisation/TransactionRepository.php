<?php

namespace Laraspace\Api\Repositories\Commercialisation;

use Hash;
use Laraspace\Models\Transaction;
use Laraspace\Models\TransactionHistory;
use Laraspace\Models\Tournament;

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
        $tournamentRes = $this->tournamentObj->addTournamentDetails($requestData, 'api');
        
        $data = array_change_key_case($requestData, CASE_UPPER);        
        $transaction = [
            'tournament_id' => $tournamentRes->id,
            'transaction_key' => $data['PAYID'],
            'amount' => $data['AMOUNT'],
            'status' => $data['STATUS'],
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
            'transaction_key' => $data['PAYID'],
            'amount' => $data['AMOUNT'],
            'status' => $data['STATUS'],
            'payment_response' => json_encode($data)
        ];
        TransactionHistory::create($transactionHistory);

        return $response;
    }

}
