<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


/***
    #토큰 발급 기록용
    CREATE TABLE `authtokens` (
        `seq` INT(11) NOT NULL AUTO_INCREMENT COMMENT '기본 PK',
        `email` VARCHAR(50) NOT NULL COMMENT '발행아이디',
        `token_full_text` TEXT NULL DEFAULT NULL COMMENT '전체토큰내용',
        `access_token` VARCHAR(255) NULL DEFAULT NULL COMMENT '엑세스 토큰 내용',
        `expires_at` DATETIME NULL DEFAULT NULL COMMENT '엑세스 토큰 만료일자',
        `refresh_token` VARCHAR(255) NULL DEFAULT NULL COMMENT '리프레스 토큰 내용',
        `refresh_expires_at` VARCHAR(255) NULL DEFAULT NULL COMMENT '리프레스 토큰 만료일자',
        `created_date` DATETIME NULL DEFAULT NULL COMMENT '발급일자',
        `modify_date` DATETIME NULL DEFAULT NULL COMMENT '리프레시일자',
        PRIMARY KEY (`seq`)
    )
    COMMENT='토큰발행 기록용테이블'
    COLLATE='utf8mb4_general_ci'
    ENGINE=InnoDB
    ;

*/

class Auth extends Model
{

    protected $table = 'authtoken';
    protected $primaryKey = 'seq';
    public $timestamps = true;
    public $email;

    // 필수값
    protected $fillable = [
        'email',                  //아이디[이메일]
        'password',                  //비밀번호
    ];

    // 수정보호
    protected $guarded = ['access_token', 'refresh_token'];


    
}
