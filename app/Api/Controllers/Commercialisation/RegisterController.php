<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laraspace\Http\Controllers\AuthController;
use Laraspace\Http\Requests\Commercialisation\Register\StoreRequest;
// Need to Define Only Contracts
use Laraspace\Api\Services\Commercialisation\RegisterService;
use Laraspace\Api\Contracts\Commercialisation\RegisterContract;

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
        if (FALSE !== $register) {
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $register,
                        'error' => [],
                        'message' => 'Registration has been done successfully.'
            ]);
        } else {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => 'Seems that you are already registered with us.']);
        }
    }

}
