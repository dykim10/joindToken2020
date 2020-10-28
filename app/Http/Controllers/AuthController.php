<?php

namespace  App\Http\Controllers;

use JWTAuth;
use Session;
use DB;
use Hash;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;   //검증시스템
use Auth;

use App\User;

class  AuthController extends  Controller {

    public  function  getAuthUser(Request  $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        return  response()->json(['user' => $user]);
    }


}