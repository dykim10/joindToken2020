#라라벨 프로젝트 2020/10/24 ~ 10/30
==

>   작성자 : dykim10iufc@gmail.com

>   개발환경   
-  라라벨 8.11.2
-  PHP 7.4.0
-  mariadb 10.4
-  테스터 프로그램 Postman
* * *

>   API URI       
-   회원가입 [ http://127.0.0.1:3321/v1/member/join ] /POST      
-   로그인 [ http://127.0.0.1:3321/v1/member/login ]              
-   정보조회 [ http://127.0.0.1:3321/v1/member/info ] /GET              
-   route 설정 [www\routes\api.php]           
* * *

>   데이터베이스 설계       
1. 라라벨 migrate를 사용하여 users 테이블이 생성 되었습니다.       
2. 로그인 기록로그를 위하여, usersLog 테이블을 별도로 생성하였습니다.       
3. 토큰 발행여부를 기록하고자  authtokens 생성하였지만, 본 프로젝트에서는 사용되지 않았습니다.       
4. users 테이블의 email 컬럼에는, unique index를 설정하여, 중복키 값 방지를 하였습니다.       
5. usersLog 테이블 email 컬럼을 index로 설정하였습니다.       
* * *




>   참고 페이지       
1. https://laravel.kr/docs/8.x/ -- 라라벨 8.x 가이드 문서       
2. https://dev-yakuza.github.io/ko/laravel/jwt/    -- JWT의 이해       
* * *


