<?php

namespace App\Http\Controllers;

use External\Bar\Auth\LoginService;
use External\Baz\Auth\Authenticator;
use External\Foo\Auth\AuthWS;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $login      = $request->name;
        $password   = $request->password;

        if(substr($login, 0, 4) === "FOO_") {
            $credentials    = $request->only('name', 'password');
            $authObj        = new AuthWS();
            $auth           = $authObj->authenticate($login, $password);

            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            } else {
                return response()->json([
                    'status' => 'failure',
                ]);
            }
        } elseif (substr($login, 0, 4) === "BAR_") {
            $credentials    = $request->only('name', 'password');
            $authObj        = new LoginService();
            $auth           = $authObj->authenticate($login, $password);

            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            } else {
                return response()->json([
                    'status' => 'failure',
                ]);
            }
        } elseif (substr($login, 0, 4) === "BAZ_") {
            $credentials    = $request->only('name', 'password');
            $authObj        = new Authenticator();
            $auth           = $authObj->authenticate($login, $password);

            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            } else {
                return response()->json([
                    'status' => 'failure',
                ]);
            }
        }

        return response()->json([
            'status' => 'failure',
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'token' => $token,
        ]);
    }
}
