<?php

use Illuminate\Http\Request;

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

Route::get("shop/index","Api\ShopController@index");
Route::get("shop/detail","Api\ShopController@detail");

//注册
Route::post("member/reg","Api\MemberController@reg");
//获得验证码
Route::get("member/sms","Api\MemberController@sms");
//登录
Route::post("member/login","Api\MemberController@login");
//忘记密码
Route::post("member/reset","Api\MemberController@reset");
//修改密码
Route::post("member/change","Api\MemberController@change");
