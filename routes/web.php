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
    Route::any("user/reg","UserController@reg")->name("user.reg");//注册
    Route::any("user/login","UserController@login")->name("user.login");//登录
    Route::any("user/edit/{id}","UserController@edit")->name("user.edit");//修改密码
    Route::get("user/logout","UserController@logout")->name("user.logout");//退出登录
    //  店铺申请
    Route::any("shop/apply","ShopController@apply")->name("shop.apply");//商户店铺申请



    //菜品分类
    Route::get("cate/index","MenuCategoryController@index")->name("menu_cate.index");
    Route::any("cate/add","MenuCategoryController@add")->name("menu_cate.add");
    Route::any("cate/edit/{id}","MenuCategoryController@edit")->name("menu_cate.edit");
    Route::get("cate/del/{id}","MenuCategoryController@del")->name("menu_cate.del");


    //菜品
    Route::get("menu/index","MenuController@index")->name("menu.index");
    Route::any("menu/add","MenuController@add")->name("menu.add");
    Route::any("menu/edit/{id}","MenuController@edit")->name("menu.edit");
    Route::any("menu/del/{id}","MenuController@del")->name("menu.del");

    //webuploder添加图片
    Route::any("menu/upload","MenuController@upload")->name("menu.upload");

    //显示活动
    Route::get("activity/index","ActivityController@index")->name("user.activity.index");



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
    Route::any("shop/add/{id}","ShopController@add")->name("admin.shop.add");


    //添加活动
    Route::get("activity/index","ActivityController@index")->name("activity.index");
    Route::any("activity/add","ActivityController@add")->name("activity.add");
    Route::any("activity/edit/{id}","ActivityController@edit")->name("activity.edit");
    Route::get("activity/del/{id}","ActivityController@del")->name("activity.del");

});