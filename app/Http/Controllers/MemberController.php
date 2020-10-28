<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;   //검증시스템
use Illuminate\Validation\Rule;

use App\Http\Services\UserService;
use App\Http\Services\AuthService;

class MemberController extends Controller
{
    //member Controller

    private $userService;
    private $authService;

    public function __construct(UserService $userService, AuthService $authService) {

        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * 회원가입 - 주석은 이런식으로 함수앞에 
     *
     * @param Request $request
     * @return void
     */
    public function join(Request  $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:12|max:255|confirmed',
            'password_confirmation' => 'required|string|min:12|max:255',
        ]);

        // $rules = array(
        //     'name' => ['required', 'string', 'max:100'],
        //     'email' => ['required', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'min:12', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/', 'confirmed'],
        //     'password_confirmation' => ['required', 'min:12', 'max:255']
        // );  

        // $validator = Validator::make($request->all(), $rules);

        //dump($validator->fails());
        //dd("END");
        
        $userData = array();
        if($validator->fails()) {   //데이터 검증실패!

            LOG::info("log ==> " . $validator->messages() );
            $userData['message'] = '필수 파라미터[이름/이메일/패스워드] 정보를 확인할 수 없습니다. ';
            return view('/member/main')->with(['status'=>401, 'argv' => $userData]);

        } else {
            $userData = $this->userService->userRegister($request);
            return view('/member/main')->with(['status'=>200, 'argv' => $userData]);

        }

    }

    /**
     * 로그인
     *
     * @param Request $request
     * @return void
     * {tip} only 메소드는 유입되는 request-요청에서 입력한 키 / 값 쌍을 반환합니다. 그렇지만 현재 request 에서 존재하지 않는 키/값은 반환하지 않습니다.
     */
    public function login(Request  $request){
        $input = $request->only('email', 'password');
        $userData = array();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:12|max:255',
        ]);

        #유효 검증 실패. 필수 파라미터 정보x. 
        if($validator->fails()) {
            LOG::info("log ==> " . $validator->messages() );
            //abort(422,'필수 파라미터 정보를 확인할 수 없습니다. ');
            $userData['message'] = '필수 파라미터 정보를 확인할 수 없습니다. ';
            return view('/member/login')->with(['status'=>401, 'argv' => $userData]);
        }

        #패스워드 정규식 체크.
        $pwdResult = $this->pwdCheckAddValidator($request->password);
        if(!$pwdResult['status']){
            $userData['message'] = $pwdResult['msg'];
            return view('/member/login')->with(['status'=>401, 'argv' => $userData]);
        }

        #가입 정보 확인
        $result = $this->userService->getUserInfo($request);
        if(!$result){
            LOG::info("log ==> " . $validator->messages() );
            //abort(421,' 가입되지 않은 유저 입니다. ');
            $userData['message'] = ' 가입되지 않은 유저 입니다. ';
            return view('/member/login')->with(['status'=>401, 'argv' => $userData]);
        }

        #토큰 정보 확인
        $token = $this->authService->getTokenInfo($request);
        //dump("제공 토큰 정보 : " . $token);
        if($token === null) {
            #토큰정보 없음
            LOG::info("log ==> Unauthorized " );
            
            #리프레시 토큰 조회
            $refreshToken = $this->authService->getRefreshToken($request);

            if($refreshToken === null){
                #토큰 재발급
                LOG::info("log ==> reFresh " );
                return $this->authService->requestAuthorizationCode($request);
            }

            $userData['messages'] = "인증 토큰이 유효하지 않습니다. ";
            return view('member/main')->with(['status'=>401, 'argv' => $userData]);

        } 

        #마지막 로그인시 로드
        $userLastLoginTime = $this->userService->userInsertLogData($request);
        if(!$userLastLoginTime){
            #최초 로그인
            $userLastLoginTime->created_date = date("Y-m-d H:i:s");
        }

        #로그인 기록작성
        $this->userService->userInsertLog($request);

        $userInfo = $this->userService->getUserInfo($request);

        #view Data
        $userData['token'] = $token;
        $userData['name'] = $userInfo->name;
        $userData['last_login_time'] = $userLastLoginTime->created_date;
        return view('member/login')->with(['status'=>200, 'argv' => $userData]);

    }

    #유저정보 조회
    public function info(Request  $request){
        return response()->json(Auth::guard('api')->user());
    }

    #로그아웃
    public function logout() {
        Auth::guard('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'logout'
        ], 200);
    }

    /**
     * 패스워드 정규식 특수문자 체크
     *
     * @param Request $request
     * @return void
     */
    public function pwdCheckAddValidator($pwd){

        $result = array();
        $numCnt = preg_match('/[0-9]/u', $pwd);
        $engCnt = preg_match('/[a-z]/u', $pwd);
        $speCnt = preg_match("/[\!\@\#\$\%\^\&\*]/u",$pwd);


        if($numCnt == 0 || $engCnt == 0 || $speCnt == 0){
            $result['status'] = false;
            $result['msg'] = '패스워드 조합 시, 영문/숫자/특수문자를 혼합해주세요. ';
        } else {
            $result['status'] = true;
        }
        return $result;
    }
}