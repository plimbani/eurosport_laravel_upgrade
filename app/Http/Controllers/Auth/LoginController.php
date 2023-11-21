<?php

namespace Laraspace\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laraspace\Http\Controllers\Controller;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Obtain the user information from provider. Check if the user already exists in our
     * database by looking up their provider_id in the database.
     *
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {

    }
}
