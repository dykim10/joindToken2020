<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    private $token;

    public function __construct()
    {

    }


    #토큰 발행
    public function requestAuthorizationCode($request){
        $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);

    }

    #토큰 정보 로드 
    public function getTokenInfo($request){
        $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);
        return $token;
    }

    #새로고침 -> 토큰 재발급
    public function getRefreshToken() {
        return $this->requestAuthorizationCode(Auth::guard('api')->refresh());
    }

}
