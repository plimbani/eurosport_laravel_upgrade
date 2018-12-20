<?php

namespace Laraspace\Api\Services\Commercialisation;

use Laraspace\Api\Contracts\Commercialisation\RegisterContract;
use Laraspace\Api\Repositories\Commercialisation\RegisterRepository;

class RegisterService implements RegisterContract {
    
    public function __construct(RegisterRepository $registerRepoObj)
    {
        $this->registerRepoObj = $registerRepoObj;
    }
    
    public function index() {
        return $this->registerRepoObj->index();
    }
    
    public function register($data)
    {
        if( $this->registerRepoObj->isRegisteredCustomer($data['email']) ) {
            return false;
        }
        return $this->registerRepoObj->register($data);
    }
}
?>

