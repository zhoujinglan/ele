<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    //注册一个用户
    public function add(Request $request){
       //判断接收方式
        if($request->isMethod("post")){
            //验证

            $data= $this->validate($request,[
                'name'=>'required|unique:users',
                "password" => "required|min:6",
                'email'=>"required",
                "img"=>"required",
            ]);

            //接收数据 入库
            //$data = $request->post();
            $file=$request->file('img');
           // dd($file);
            $data['img']=$file->store("user_img");
            //$data['img']=$file->store('user_img','image');

            //数据加密

            $data['password']=Hash::make($data['password']);
            //添加
            User::create($data);
            //跳转
            return redirect()->route('user.login')->with("success","注册成功 请登录");
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
           //dd($data);

            if( Auth::attempt( $data, $request->has( "remember"))){
                return redirect()->intended(route("index"))->with("success","登录成功");


                //判断商铺的状态
               $id=Auth::user()->id;


               $shop= Shop::where("user_id",$id)->first();
               dd($shop->status);
                if(($shop->status) ==-1){
                    //登陆成功
                    return back()->with("danger","你的店铺已禁用  请重新注册账号");
                    //return 1;

                }elseif( empty($shop->status)){

                }
                else{
                    return redirect()->intended(route("index"))->with("success","登录成功");

                   // return redirect()->intended(route("shop.user.add"))->with();
                }


            }else{
                //登录失败
                return redirect()->back()->withInput()->with("danger","账号或密码错误");
            }
        }
            //显示视图
        return view("shop.user.login");
    }
  //修改个人密码
    public function edit(Request $request,$id){
        //
        $user= User::find($id);

        if($request->isMethod("post")){

            $this->validate( $request, [
                "password"     => "required|confirmed",
                "old_password" => "required",
            ] );

            $oldPassword = $request->post( 'old_password' );
            //判断老密码是否正确
            //dd(Hash::check($oldPassword, $admin['password']));返回true或者false
            if( Hash::check( $oldPassword, $user['password'] ) ){
                //如果老密码正确 设置新密码
                $user['password'] = Hash::make( $request->post( 'password' ) );
                // 保存修改
                $user->save();
                //跳转
                return redirect()->route( 'index' )->with( "success", "修改密码成功" );
            }

            //4.老密码不正确
            return back()->with( "danger", "旧密码不正确" );
        }
        //显示视图
        return view("shop.user.edit",compact("user"));

    }


}
