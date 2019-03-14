<?php

namespace Laraspace\Api\Services\Commercialisation;

use JWTAuth;
use Laraspace\Api\Contracts\Commercialisation\TransactionContract;
use Laraspace\Api\Repositories\Commercialisation\TransactionRepository;
use Laraspace\Models\Transaction;
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
    public function generatePaymentReceipt($data)
    {
        $transaction = \DB::table('transactions')
                ->select('transaction_histories.amount', 'transaction_histories.order_id', 'tournaments.start_date', 'tournaments.end_date', 'tournaments.maximum_teams')
                ->join('transaction_histories', 'transaction_histories.transaction_id', '=', 'transactions.id')
                ->join('tournaments', 'tournaments.id', '=', 'transactions.tournament_id')
                ->where(['transactions.tournament_id' => $data['tournament_id']])
                ->orderBy('transaction_histories.id', 'desc')
                ->first();
        
        $fdate = str_replace('/', '-', $transaction->start_date);
        $tdate = str_replace('/', '-', $transaction->end_date);
        $datetime1 = new \DateTime($fdate);
        $datetime2 = new \DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');

        $pdfData = [
            'days' => $days,
            'maximumTeams' => $transaction->maximum_teams,
            'amount' => $transaction->amount,
            'orderNumber' => $transaction->order_id
        ];
        $date = new \DateTime(date('H:i d M Y'));
        $pdf = PDF::loadView('commercialisation.payment_receipt', ['data' => $pdfData])
                ->setPaper('a4')
                ->setOption('header-spacing', '5')
                ->setOption('header-font-size', 7)
                ->setOption('header-font-name', 'Open Sans')
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

