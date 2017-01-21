<?php
namespace TorNas\Http\Controllers\Auth;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

use TorNas\Http\Controllers\Controller;
use TorNas\Http\Requests\Auth\LoginRequest;
use TorNas\Modules\User\UserTransformer;

class AuthController extends Controller
{
    /**
     * Handle login
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            if (! $token = JWTAuth::attempt($request->only('login', 'password'))) {
                return $this->response()->errorUnauthorized('invalid_credentials');
            }
        } catch (JWTException $e) {
            return $this->response()->errorInternal('could_not_create_token');
        }

        return $this->response()->make(compact('token'));
    }

    /**
     * Get user details
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function user(Request $request)
    {
        return $this->response()->item($request->user(), new UserTransformer());
    }
}
