<?php

namespace Laraspace\Services;

use Laraspace\Contracts\ApiContract;
use Dingo\Api\Routing\Helpers;

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
