<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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
    public function audit($id){
        $one = Shop::find($id);
       //判断状态

          //dd($data);
            DB::update('update shops set status = 1 where id =:id', [$id]);
            //跳转
            return redirect()->route("shop.list");


        //显示视图
        //return view("admin.shop.audit",compact("one"));

    }
    //显示所有商户信息
    public function indexUser(  ){
        $users=User::all();
        //显示视图
        return view('admin.user.index',compact("users"));

    }

    //修改管理员资料
    public function edit(Request $request,$id){
        //
        $admin=Admin::find($id);
        if($request->isMethod("post")){

            $this->validate($request,[
                "password"=>"required|confirmed",
                "old_password"=>"required",
            ]);


            $oldPassword = $request->post('old_password');
            //判断老密码是否正确
            //dd(Hash::check($oldPassword, $admin['password']));返回true或者false
            if (Hash::check($oldPassword, $admin['password'])) {
                //如果老密码正确 设置新密码
                $admin['password'] = Hash::make($request->post('password'));
                // 保存修改
                $admin->save();
                //跳转
                return redirect()->route('admin.index')->with("success", "修改密码成功");
            }
            //4.老密码不正确
            return back()->with("danger", "旧密码不正确");

        }
        //显示视图
        return view("admin.admin.edit",compact("admin"));
    }

    //给用户重置密码
    public function editUser($id){
        $user= User::find($id);
        //dd($user);
        $password= Hash::make(111111);
        //dd($password);
        $user['password']=$password;
        $user->save();
        return redirect()->route("user.list")->with("success","重置密码完成111111");

    }

    //删除商户信息
    public function delUser($id){

        //应用事务  同时删除
        DB::transaction(function () use ($id) {

            User::find($id)->delete();
            Shop::where("user_id",$id)->delete();

        });
        return redirect()->route("user.list")->with("success","删除成功");

    }

    //退出登录
    public function logout(  ){
        Auth::guard("admin")->logout();
        //跳转
        return redirect()->route("admin.login")->with("success","退出成功");

    }

}
