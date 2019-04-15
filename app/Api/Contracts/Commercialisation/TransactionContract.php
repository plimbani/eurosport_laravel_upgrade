<?php

namespace Laraspace\Api\Contracts\Commercialisation;

interface TransactionContract
{
    /*
     * Buy license 
     *
     * @param void
     * @return view
     */

    public function paymentResponse($data);
}

?>