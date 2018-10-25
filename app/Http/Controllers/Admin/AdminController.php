<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends BaseController
{
    //登录后台
    public function login(Request $request){
        if($request->isMethod("post")){
            //验证
            $data= $this->validate( $request, [
                'name'     => 'required',
                "password" => "required",

            ] );
            //dd( Auth::guard("admin")->attempt($data));

            if( Auth::guard("admin")->attempt($data)){
                //登陆成功
                return redirect()->intended(route("admin.index"))->with("success","登录成功");
            }else{
                //登录失败
                return redirect()->back()->withInput()->with("danger","账号或密码错误");
            }
        }
        //显示视图
        return view("admin.admin.login");
    }

    public function index( ){
        return view("admin.admin.index");

    }
    //处理分类
    public function list(  ){
        $shops= Shop::all();
        return view("admin.shop.index",compact("shops"));
    }
    //审核
    public function audit(Request $request,$id){
        $one = Shop::find($id);
       //判断状态
        if($request->isMethod("post")){
          $data =$request->post();
          //dd($data);
            DB::update('update shops set status = '.$data['status'].' where id =:id', [$id]);
            //跳转
            return redirect()->route("shop.list");
        }

        //显示视图
        return view("admin.shop.audit",compact("one"));

    }
}
