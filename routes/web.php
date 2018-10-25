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
    Route::any("user/add","UserController@add")->name("user.add");
    Route::any("user/login","UserController@login")->name("user.login");
    Route::any("user/edit/{id}","UserController@edit")->name("user.edit");
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
    //禁用

    //分类管理 显示
    Route::get("cate/index","ShopCategoryController@index")->name("cate.index");
    //添加 修改 删除
    Route::any("cate/add","ShopCategoryController@add")->name("cate.add");
    Route::any("cate/edit/{id}","ShopCategoryController@edit")->name("cate.edit");
    Route::get("cate/del/{id}","ShopCategoryController@del")->name("cate.del");


    //店户管理 显示  重置密码  删除
    Route::get("user/list","AdminController@indexUser")->name("user.list");
    Route::get("user/edit/{id}","AdminController@editUser")->name("admin.user.edit");
    Route::get("user/del/{id}","AdminController@delUser")->name("admin.user.del");



    //修改管理员信息
    Route::any("admin/edit/{id}","AdminController@edit")->name("admin.edit");
    //退出
    Route::get("admin/logout","AdminController@logout")->name("admin.logout");

    //平台添加商户
    Route::any("user/add","UserController@add")->name("user.add");
});