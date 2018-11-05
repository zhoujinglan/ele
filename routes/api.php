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
//登录用户列表显示
Route::get("member/detail","Api\MemberController@detail");

//收货地址 增删改查
Route::get("address/index","Api\AddressController@index");
Route::post("address/add","Api\AddressController@add");
Route::post("address/edit","Api\AddressController@edit");
Route::get("address/getOne","Api\AddressController@getOne");//回显一条

//购物车
Route::post("cart/add","Api\CartController@add");
Route::get("cart/index","Api\CartController@index");

//订单生成
Route::post("order/add","Api\OrderController@add");
Route::get("order/detail","Api\OrderController@detail");
Route::any("order/index","Api\OrderController@index");

//支付
Route::post("order/pay","Api\OrderController@pay");