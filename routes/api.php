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
Route::namespace("Api")->group(function (){
    //region首页
    Route::get( "shop/index", "ShopController@index" );
    Route::get( "shop/detail", "ShopController@detail" );
    //endregion

    //region注册
    Route::post( "member/reg", "MemberController@reg" );
    //获得验证码
    Route::get( "member/sms", "MemberController@sms" );
    //登录
    Route::post( "member/login", "MemberController@login" );
    //忘记密码
    Route::post( "member/reset", "MemberController@reset" );
    //修改密码
    Route::post( "member/change", "MemberController@change" );
    //登录用户列表显示
    Route::get( "member/detail", "MemberController@detail" );
    Route::get( "member/money", "MemberController@money" );
    //endregion

    //region收货地址
    #增删改查
    Route::get( "address/index", "AddressController@index" );
    Route::post( "address/add", "AddressController@add" );
    Route::post( "address/edit", "AddressController@edit" );
    Route::get( "address/getOne", "AddressController@getOne" );//回显一条
    //endregion

    //region购物车
    Route::post( "cart/add", "CartController@add" );
    Route::get( "cart/index", "CartController@index" );
    //endregion

    //region订单
    Route::post( "order/add", "OrderController@add" );
    Route::get( "order/detail", "OrderController@detail" );
    Route::any( "order/index", "OrderController@index" );
    Route::any( "order/clear", "OrderController@clear" );
    //endregion

    //region支付
    Route::post("order/pay", "OrderController@pay");
    # 微信支付
    Route::get("order/wxPay", "OrderController@wxPay");
    Route::get("order/ok","OrderController@ok");
    Route::get("order/status","OrderController@status");
    //endregion
});