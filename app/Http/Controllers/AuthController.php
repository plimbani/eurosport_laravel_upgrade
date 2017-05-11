<?php

namespace Laraspace\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {

        // dd($request->all());
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function check()
    {

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response(['authenticated' => false]);
        }
        // Here Add Functionality if use is Active then allowed to login
        $token=JWTAuth::getToken();
        if($token) {
          $userData = JWTAuth::toUser($token);
          if($userData->is_active == 0) {
            return response(['authenticated' => false,'message'=>'Permission denied']);
          }
            return response(['authenticated' => true]);
        }

    }

    public function logout()
    {

        try {
            $token = JWTAuth::getToken();

            if ($token) {

                JWTAuth::invalidate($token);
            }

        } catch (JWTException $e) {
            return response()->json($e->getMessage(), 401);
        }

        return response()->json(['message' => 'Log out success'], 200);
    }
}
