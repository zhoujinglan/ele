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
    return view('index');
});

//商家
Route::domain("shop.ele.com")->namespace("Shop")->group(function (){

    //region商户模块
    Route::get("index","IndexController@index")->name("index");


    #商户注册
    Route::any("user/reg","UserController@reg")->name("user.reg");
    #商户登录
    Route::any("user/login","UserController@login")->name("user.login");
    #密码修改
    Route::any("user/edit/{id}","UserController@edit")->name("user.edit");
    #商户注销
    Route::get("user/logout","UserController@logout")->name("user.logout");
    # 商户店铺申请
    Route::any("shop/apply","ShopController@apply")->name("shop.apply");

    //endregion

    //region菜品分类
    Route::get("cate/index","MenuCategoryController@index")->name("menu_cate.index");
    Route::any("cate/add","MenuCategoryController@add")->name("menu_cate.add");
    Route::any("cate/edit/{id}","MenuCategoryController@edit")->name("menu_cate.edit");
    Route::get("cate/del/{id}","MenuCategoryController@del")->name("menu_cate.del");
    //endregion

    //region菜品
    Route::get("menu/index","MenuController@index")->name("menu.index");
    Route::any("menu/add","MenuController@add")->name("menu.add");
    Route::any("menu/edit/{id}","MenuController@edit")->name("menu.edit");
    Route::any("menu/del/{id}","MenuController@del")->name("menu.del");
    //endregion

    //region webuploder添加图片
    Route::any("menu/upload","MenuController@upload")->name("menu.upload");
    //endregion

    //region显示活动
    Route::get("activity/index","ActivityController@index")->name("user.activity.index");
    //endregion

    //region 订单管理
    Route::get("order/index","OrderController@index")->name("shop.order.index");
    Route::get("order/del/{id}","OrderController@del")->name("shop.order.del");
    //endregion

    //region 统计
    #销量每日统计
    Route::get("order/day","OrderController@day")->name("shop.order.day");
    #销量每月
    Route::get("order/month","OrderController@month")->name("shop.order.month");
    #商品
    Route::get("order/menuday","OrderController@menuDay")->name("shop.order.menu_day");
    Route::get("order/menumonth","OrderController@menuMonth")->name("shop.order.menu_month");
    Route::get("order/menu","OrderController@menu")->name("shop.order.menu");
    #处理订单状态
    Route::get('order/status/{id}/{status}', "OrderController@status")->name('order.status');
    #查看
    Route::get('order/look/{id}', "OrderController@look")->name('shop.order.look');
    //endregion

    //region抽奖活动
    Route::get("event/index","EventController@index")->name("shop.event.index");
    Route::any("event/join/{id}","EventController@join")->name("shop.event.join");
    Route::get("event/look/{id}","EventController@look")->name("shop.event.look");
    //endregion


});

//平台
Route::domain("admin.ele.com")->namespace("Admin")->group(function (){
    //region 商户分类
    Route::any("admin/login","AdminController@login")->name("admin.login");
    Route::any("admin/add","AdminController@add")->name("admin.add");
    Route::get("admin/index","AdminController@index")->name("admin.index");
    Route::get("shop/list","AdminController@list")->name("shop.list");
    //endregion

    //region审核
    Route::any("admin/audit/{id}","AdminController@audit")->name("admin.audit");
    //endregion
    //禁用
    //region 分类管理
    // 显示
    Route::get("cate/index","ShopCategoryController@index")->name("cate.index");
    //添加 修改 删除
    Route::any("cate/add","ShopCategoryController@add")->name("cate.add");
    Route::any("cate/edit/{id}","ShopCategoryController@edit")->name("cate.edit");
    Route::get("cate/del/{id}","ShopCategoryController@del")->name("cate.del");
    //endregion

    //region店户管理
    // 显示
    Route::get("user/list","AdminController@indexUser")->name("user.list");
    #重置密码
    Route::get("user/edit/{id}","AdminController@editUser")->name("admin.user.edit");
    Route::get("user/del/{id}","AdminController@delUser")->name("admin.user.del");

    //endregion

    //region修改管理员信息
    Route::any("admin/edit/{id}","AdminController@edit")->name("admin.edit");
    Route::any("admin/update/{id}","AdminController@update")->name("admin.update");
    Route::get("admin/del/{id}","AdminController@del")->name("admin.del");
    //退出
    Route::get("admin/logout","AdminController@logout")->name("admin.logout");
    //endregion

    //region平台添加商户
    Route::any("shop/add/{id}","ShopController@add")->name("admin.shop.add");
    //endregion

    //region添加活动
    Route::get("activity/index","ActivityController@index")->name("activity.index");
    Route::any("activity/add","ActivityController@add")->name("activity.add");
    Route::any("activity/edit/{id}","ActivityController@edit")->name("activity.edit");
    Route::get("activity/del/{id}","ActivityController@del")->name("activity.del");
    //endregion

    //region 订单管理
    Route::get("admin/order/index","OrderController@index")->name("admin.order.index");
    Route::get("order/del/{id}","OrderController@del")->name("admin.order.del");

    //endregion

    //region 统计
    #销量每日统计
    Route::get("order/day","OrderController@day")->name("admin.order.day");
    #销量每月
    Route::get("order/month","OrderController@month")->name("admin.order.month");
    #商品
    Route::get("order/menuday","OrderController@menuDay")->name("admin.order.menu_day");
    Route::get("order/menu","OrderController@menu")->name("admin.order.menu");

    #查看
    Route::get('order/look/{id}', "OrderController@look")->name('order.look');
    //endregion

    //region  会员
    Route::get("admin/member/index","MemberController@index")->name("admin.member.index");
    //endregion

    //region 权限添加
    Route::get("per/index","PermissionController@index")->name("per.index");
    Route::any("per/add","PermissionController@add")->name("per.add");
    Route::any("per/edit/{id}","PermissionController@edit")->name("per.edit");
    Route::get("per/del/{id}","PermissionController@del")->name("per.del");
    //endregion

    //region 角色添加
    Route::get("role/index","RoleController@index")->name("role.index");
    Route::any("role/add","RoleController@add")->name("role.add");
    Route::any("role/edit/{id}","RoleController@edit")->name("role.edit");
    Route::get("role/del/{id}","RoleController@del")->name("role.del");
    //endregion

    //region导航栏菜单添加
    Route::any("nav/add","NavController@add")->name("nav.add");
    //endregion

    //region抽奖
    Route::get("event/index","EventController@index")->name("admin.event.index");
    Route::any("event/add","EventController@add")->name("admin.event.add");
    Route::any("event/edit/{id}","EventController@edit")->name("admin.event.edit");
    Route::get("event/del/{id}","EventController@del")->name("admin.event.del");
    Route::get("event/open/{id}","EventController@open")->name("admin.event.open");
    //endregion
    //region 抽奖
    Route::get("event/prize/index","EventPrizeController@index")->name("admin.event_prize.index");
    Route::any("event/prize/add","EventPrizeController@add")->name("admin.event_prize.add");
    Route::any("event/prize/edit/{id}","EventPrizeController@edit")->name("admin.event_prize.edit");
    Route::get("event/prize/del/{id}","EventPrizeController@del")->name("admin.event_prize.del");
    //endregion
});