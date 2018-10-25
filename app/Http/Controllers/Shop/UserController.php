<?php

namespace App\Http\Controllers\Shop;

use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    //注册一个用户
    public function add(Request $request){
       //判断接收方式
        if($request->isMethod("post")){
            //验证
            $this->validate($request,[
                'name'=>'required|unique:users',
                "password" => "required|min:6",
               'email'=>"required",
            ]);

            //接收数据 入库
            $data = $request->post();

            //数据加密

            $data['password']=Hash::make($data['password']);
            //添加
            User::create($data);
            //跳转
            return redirect()->route('shop.user.login')->with("success","注册成功 请登录");
        }
        //显示视图
        return view("shop.user.add");
    }

    //登录
    public function login(Request $request){
        //判断接收方式
        if($request->isMethod("post")){
            //验证
           $data= $this->validate( $request, [
                'name'     => 'required',
                "password" => "required",

            ] );

            if( Auth::attempt( $data, $request->has( "remember"))){
                //登陆成功
                return redirect()->intended(route("index"))->with("success","登录成功");
            }else{
                //登录失败
                return redirect()->back()->withInput()->with("danger","账号或密码错误");
            }
        }
            //显示视图
        return view("shop.user.login");
    }


}
