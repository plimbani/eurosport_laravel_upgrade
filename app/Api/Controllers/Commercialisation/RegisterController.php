<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laraspace\Http\Controllers\AuthController;
use Laraspace\Http\Requests\Commercialisation\Register\StoreRequest;
// Need to Define Only Contracts
use Laraspace\Api\Services\Commercialisation\RegisterService;

//use Laraspace\Api\Contracts\Commercialisation\RegisterContract;

/**
 * Registration Description.
 *
 * @Resource("register")
 *
 * @Author supasani@aecordigital.com
 */
class RegisterController extends BaseController
{

    /**
     * Create a new controller instance.
     * @param object $registerObj
     * @return void
     */
    // public function __construct(RegisterContract $registerObj)
    public function __construct(RegisterService $registerObj)
    {
        $this->registerObj = $registerObj;
    }

    /*
     * Return Register From
     *  */

    public function index()
    {
        return $this->registerObj->index();
    }

    /*
     * Insert Register Data
     *  */

    public function register(StoreRequest $request)
    {
        $register = $this->registerObj->register($request->all());
        dd($register);
        if (FALSE !== $register) {
            return view('commercialisation.buylicense');
        } else {
            return response()->json(['status_code' => Response::HTTP_UNPROCESSABLE_ENTITY, 'message' => 'Seems that you are already registered with us.']);
        }
    }

}
