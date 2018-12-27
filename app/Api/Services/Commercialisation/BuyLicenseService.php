<?php

namespace Laraspace\Api\Services\Commercialisation;

use Laraspace\Api\Contracts\Commercialisation\BuyLicenseContract;
use Laraspace\Api\Repositories\Commercialisation\BuyLicenseRepository;

class BuyLicenseService implements BuyLicenseContract {
    
    /**
     * Controller instance
     * @param BuyLicenseRepository $buyLicenseRepoObj
     */
    public function __construct(BuyLicenseRepository $buyLicenseRepoObj)
    {
        $this->buyLicenseRepoObj = $buyLicenseRepoObj;
    }
    
    
    public function buyLicense()
    {
        
    }
}
?>

