<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    //显示首页
    public function index(  ){
        $pers=Permission::paginate(3);
        return view("admin.per.index",compact("pers"));
    }

    //添加权限
    public function add(Request $request){

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
        $pers =Permission::pluck("name")->toArray();
        //去掉已经存在的路由
        $urls =array_diff($urls,$pers);
        //判断接收方法
        if($request->isMethod("post")){
            //验证
            $this->validate($request,[
                'name'=>"required",
                'intro'=>"required",

            ]);
            $data = $request->post();
            $data['guard_name']="admin";
            //dd($data);
           $per= Permission::create($data);
            return redirect()->route('per.index')->with('success', '创建' . $per->name . "成功");

        }
        //引入视图
        return view("admin.per.add",compact('urls'));
    }

    //修改
    public function edit(Request $request,$id ){
        //找到一条
        $per =Permission::find($id);
        //判断接收方式
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'=>"required",
                'intro'=>"required",

            ]);
            $data = $request->post();
           // $data['guard_name']="admin";
            $per->update($data);
            //跳转
            return redirect()->route("per.index")->with("success","修改成功");

        }
        //引入视图
        return view("admin.per.edit",compact("per"));

    }

    //删除
    public function del( $id ){
        $per =Permission::find($id);
        $per->delete();
        //跳转
        return back()->with("success","删除成功");
    }
}
