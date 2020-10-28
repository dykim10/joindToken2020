<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;

#회원가입
Route::post('/member/join', [MemberController::class, 'join']);
#Route::post('/member/join', [AuthController::class, 'register']);


#로그인
Route::post('/member/login', [MemberController::class, 'login']);


#비인증 알림
Route::get('unauthorized', function() {
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized'
    ], 401);
})->name('api.jwt.unauthorized');


Route::group(['middleware' => 'auth:api'], function(){
    Route::any('/member/info', [MemberController::class, 'info'])->name('api.jwt.user');
    Route::any('/member/logout', [MemberController::class, 'logout'])->name('api.jwt.logout');
    Route::any('/member/refresh', [AuthController::class, 'refresh'])->name('api.jwt.refresh');
});


