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
    public function reg(Request $request){
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
            //验证信息
            $data = $this->validate($request, [
                'name' => "required",
                'password' => "required"
            ]);
            //验证密码是否正确
            if(Auth::attempt($data)){
                //密码正确
                //判断商铺状态
                $shop = Auth::user()->shop;//shop是user模型里的一种方法
                if($shop){//如果店铺有 看状态
                    //状态就判断-1 0
                    switch($shop->status){
                        case -1:
                            //禁用 退登录
                            Auth::logout();
                            return back()->withInput()->with("danger","你的店铺已禁用");
                            break;
                        case 0:
                            //未审核 退登录
                            Auth::logout();
                            return back()->withInput()->with("danger","你的店铺还未通过审核");
                            break;
                    }
                }else{


                   // 没有店铺 申请
                    return redirect()->route("shop.apply")->with("success","你还没有店铺 请先申请");
                }
                //上面的条件都满足 登录首页
                return redirect()->route("index")->with("success","登录成功");

            }else{
                //密码不正确
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

    //退出登录
    public function logout(  ){
        Auth::logout();
        return redirect()->route("user.login")->with("success","退出成功");
    }


}
