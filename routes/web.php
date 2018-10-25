<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//商家
Route::domain("shop.ele.com")->namespace("Shop")->group(function (){

    //商户首页
    Route::get("index","IndexController@index")->name("index");

    //商户注册 登录
    Route::any("user/add","UserController@add")->name("shop.user.add");
    Route::any("user/login","UserController@login")->name("shop.user.login");
    //  店铺申请
    Route::any("shop/apply","ShopController@apply")->name("shop.apply");




});

//平台
Route::domain("admin.ele.com")->namespace("Admin")->group(function (){
    //商户分类
    Route::any("admin/login","AdminController@login")->name("admin.login");
    Route::get("admin/index","AdminController@index")->name("admin.index");
    Route::get("shop/list","AdminController@list")->name("shop.list");

    //审核
    Route::any("admin/audit/{id}","AdminController@audit")->name("admin.audit");
    //分类管理
    Route::get("cate/index","ShopCategoryController@index")->name("cate.index");
    //添加
    Route::any("cate/add","ShopCategoryController@add")->name("cate.add");
    Route::any("cate/edit/{id}","ShopCategoryController@edit")->name("cate.edit");
});