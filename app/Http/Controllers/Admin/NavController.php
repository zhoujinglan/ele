<?php

namespace App\Http\Controllers\Admin;

use App\Models\Nav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class NavController extends Controller
{
    //添加
public function add(Request $request  ){

    if($request->isMethod('post')){
        //验证
        $this->validate($request,[
            'name'=>"required |unique:navs",

        ]);
        //接收
        $data =$request->post();
        //添加入库
        Nav::create($data);
        return redirect()->refresh()->with("success","添加成功");
    }
    //获取所有路由
    //先声明一个空数组来保存路由名字
    $urls=[];
    //得到所有路由

    $routes=Route::getRoutes();
    //循环遍历所有路由
    foreach($routes as $route){
        if(isset($route->action["namespace"]) && $route->action["namespace"]=="App\Http\Controllers\Admin"){
            //读取路由名 并保存
            $urls[]=$route->action['as'];
        }
    }
    //从数据读取已经存在的
    $pers =Nav::pluck("url")->toArray();
    //去掉已经存在的路由
    $urls =array_diff($urls,$pers);
    //查询父级的菜单
    $navs =Nav::where('pid',"0")->get();
    //dd($navs);
    //显示视图
    return view("admin.nav.add",compact("urls","navs"));
}
}
