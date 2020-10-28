<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;

/**
    # usersLog 기록용 테이블
    CREATE TABLE `userslog` (
        `seq` INT(11) NOT NULL AUTO_INCREMENT COMMENT '기본 키',
        `email` VARCHAR(50) NOT NULL COMMENT '사용자 아이디(이메일)',
        `created_date` DATETIME NOT NULL COMMENT '작성일자',
        `kind_cd` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1:로그인, 2:로그아웃',
        PRIMARY KEY (`seq`),
        INDEX `email` (`email`)
    )
    COMMENT='유저접속로그'
    COLLATE='utf8mb4_general_ci'
    ENGINE=InnoDB
    ;

    # users 테이블
    CREATE TABLE `users` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
        `email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
        `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
        `password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
        `remember_token` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `users_email_unique` (`email`)
    )
    COLLATE='utf8mb4_unicode_ci'
    ENGINE=InnoDB
    AUTO_INCREMENT=3
    ;

*/

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $email;

    // 필수값
    protected $fillable = [
        'email',                  //아이디[이메일]
        'password',                  //비밀번호
    ];

    // 수정보호
    protected $guarded = ['email'];


    public function getAuthPassword() {
        return $this->email;
    }

    public function getUserSelect($email){

        $result = DB::table("users")->where("email", $email)->first();

        return $result;

    }

    public function register($argv){

        $insert = array();
        $membersData = array();
        $insert['name'] = $argv->name;
        $insert['email'] = $argv->email;
        $insert['password'] = bcrypt($argv->password);
        $insert['created_date'] = date("Y-m-d H:i:s");
        $ikey = DB::table("users")->insertGetId($insert);

        #입력 후 키로 select 해서 회원가입 완료 페이지에 정보 보여주기.
        if($ikey > 0){
            $membersData = DB::table("users")->where("id", $ikey)->first();
            $membersData = $membersData->toArray();
        }

        return $membersData;
        //dd($data->password);

    }

    public function insertLog($argv){

        $insertLog = array();

        $insertLog['email'] = $argv->email;
        $insertLog['created_date'] = date("Y-m-d H:i:s");
        $insertLog['kind_cd'] = 1;

        DB::table("usersLog")->insert($insertLog);
        //dd($argv->email);

    }

    public function insertLogData($argv){

        $result = DB::table("usersLog")->where("email", $argv->email)->orderby("created_date", "desc")->first();
        return $result;

    }

}

