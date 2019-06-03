<?php

namespace Laraspace\Api\Services\Commercialisation;

use JWTAuth;
use Laraspace\Api\Contracts\Commercialisation\TransactionContract;
use Laraspace\Api\Repositories\Commercialisation\TransactionRepository;
use Laraspace\Models\Transaction;
use Laraspace\Models\Tournament;
use PDF;

class TransactionService implements TransactionContract {

    /**
     * Controller instance
     * @param BuyLicenseRepository $buyLicenseRepoObj
     */
    public function __construct(TransactionRepository $transactionRepoObj)
    {
        $this->transactionRepo = $transactionRepoObj;
    }

    public function paymentResponse($data)
    {
        return $this->transactionRepo->addDetails($data);
    }

    public function customerTransactions($data)
    {
        return $this->transactionRepo->getList($data);
    }

    /**
     * Generate PDF
     * @param array $data
     * @return string
     */
    public function generatePaymentReceipt($data, $tournament_id)
    {
        $transaction = \DB::table('transaction_histories')
                ->select('transaction_histories.id', 'transaction_histories.currency', 'transaction_histories.amount', 'transaction_histories.order_id', 'transaction_histories.team_size', 'tournaments.start_date', 'tournaments.end_date', 'transaction_histories.no_of_days', 'tournaments.maximum_teams')
                ->join('transactions', 'transaction_histories.transaction_id', '=', 'transactions.id')
                ->join('tournaments', 'tournaments.id', '=', 'transactions.tournament_id')
                ->where(['transactions.tournament_id' => $tournament_id])
                ->orderBy('transaction_histories.id', 'desc')
                ->limit(2)
                ->get();

        $fdate = str_replace('/', '-', $transaction[0]->start_date);
        $tdate = str_replace('/', '-', $transaction[0]->end_date);
        $datetime1 = new \DateTime($fdate);
        $datetime2 = new \DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a') + 1;

        if (count($transaction) > 1) {
            $amount  = $transaction[0]->amount;
            $maxTeam = $transaction[0]->maximum_teams." (+".$transaction[0]->team_size.")";
            $days = $days." (+".$transaction[0]->no_of_days.")";
        } else {
            $amount = $transaction[0]->amount;
            $maxTeam = $transaction[0]->team_size;
        }

        $tournamentCreatedAt = Tournament::orderBy('id', 'desc')->first();
        $tournamentCreatedAtDateFormat = $tournamentCreatedAt['created_at']->format('h:i:s d-m-y');

        $pdfData = [
            'days' => $days,
            'maximumTeams' => $maxTeam,
            'amount' => number_format($amount,2),
            'orderNumber' => $transaction[0]->order_id,
			'currency' => $transaction[0]->currency,
            'tournamentCreatedAtDateFormat' => $tournamentCreatedAtDateFormat,
            'userName' => $_GET['user_name'],
        ];
        
        $date = new \DateTime(date('H:i d M Y'));
        $pdf = PDF::loadView('commercialisation.payment_receipt', ['data' => $pdfData])
                ->setPaper('a4')
                ->setOption('header-spacing', '5')
                ->setOption('header-font-size', 7)
                ->setOption('header-font-name', 'Open Sans')
                ->setOption('footer-spacing', '5')
                ->setOption('footer-font-size', 7)
                ->setOption('footer-font-name', 'Open Sans')
                ->setOrientation('portrait')
                ->setOption('footer-right', 'Page [page] of [toPage]')
                ->setOption('header-right', $date->format('H:i d M Y'))
                ->setOption('margin-top', 20)
                ->setOption('margin-bottom', 20);
        $pdfFile = 'payment-receipt-' . $date->format('Y-m-d H:i:s') . '.pdf';
        return $pdf->download($pdfFile);
    }
}
?>

