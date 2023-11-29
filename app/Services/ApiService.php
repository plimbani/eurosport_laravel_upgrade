<?php

namespace App\Services;

use Dingo\Api\Routing\Helpers;
use App\Contracts\ApiContract;

class ApiService implements ApiContract
{
    use Helpers;
    /*
     *  Api Object And Dispacther Object
     */

    protected $apiObj;

    protected $dispatcherObj;

    public function __construct()
    {
        $this->apiObj = $this->api;
    }

    public function getDispacther()
    {
        return $this->dispatcherObj = app('Dingo\Api\Dispatcher');
    }
}
