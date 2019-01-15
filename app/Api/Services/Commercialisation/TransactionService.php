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

    /**
     * Generate PDF
     * @param array $data
     * @return string
     */
    public function generatePaymentReceipt($data)
    {
        $transaction = Transaction::where('tournament_id', '=', $data['tournament_id'])->first();

        $fdate = str_replace('/', '-', $transaction->tournament->start_date);
        $tdate = str_replace('/', '-', $transaction->tournament->end_date);
        $datetime1 = new \DateTime($fdate);
        $datetime2 = new \DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');

        $pdfData = [
            'days' => $days,
            'maximumTeams' => $transaction->tournament->maximum_teams,
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
        return $pdf->save(public_path('images') . 'payment-receipt.pdf');
//        return $pdf->download('payment-receipt.pdf');
    }
}
?>

