<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

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
    public function __construct(Request $request)
    {
        $this->request = $request;
        // $this->middleware('guest', ['except' => 'logout']);
        // $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $field = filter_var($this->request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $this->request->merge([$field => $this->request->input('login')]);

        return $field;
    }

    // public function login(Request $request)
    // {
    //     $credentials = $request->only('password');
    //     $credentials['email'] = $request->input('login');
    //     try {
    //         // attempt to verify the credentials and create a token for the user
    //         if (! $token = JWTAuth::attempt($credentials)) {
    //             return response()->json(['error' => 'invalid_credentials'], 401);
    //         }
    //     } catch (JWTException $e) {
    //         // something went wrong whilst attempting to encode the token
    //         return response()->json(['error' => 'could_not_create_token'], 500);
    //     }

    //     // all good so return the token
    //     return response()->json(compact('token'));
    // }

    /*
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     *
     * @return mixed
     */
    // protected function authenticated(Request $request, $user)
    // {
    //     /*
    //      * Check to see if the users account is confirmed and active
    //      */
    //     if ($user->is_verified === 0) {
    //         $this->logout($request);

    //         return redirect()->to('login')->with(
    //             'warning',
    //             'Your account is not confirmed. Please click the confirmation link in your e-mail'
    //         );
    //     }

    //     return redirect()->intended($this->redirectPath());
    // }
}
