<?php

namespace App\Http\Services;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\models\User;

class UserService
{

    private $user;
    public function __construct()
    {
        $this->user = new \App\models\User();
    }

    public function getUserInfo($request){

        //$user = new \App\models\User();
        $user = $this->user->getUserSelect($request->email);

        return $user;

    }

    public function userRegister($argv){


        $result = $this->user->register($argv);
        return $result;
        //dd($argv->email);
    }

    public function userInsertLog($argv){
        $this->user->insertLog($argv);
    }


    public function userInsertLogData($argv){
        return $this->user->insertLogData($argv);
    }
}
