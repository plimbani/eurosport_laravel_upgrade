<?php

namespace Laraspace\Api\Services\Commercialisation;

use Laraspace\Api\Contracts\Commercialisation\TransactionContract;
use Laraspace\Api\Repositories\Commercialisation\TransactionRepository;

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
}
?>

