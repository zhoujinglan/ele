<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{

    //显示
    public function index(  ){
       $roles =Role::all();
       return view("admin.role.index",compact("roles"));
    }
    //添加角色
    public function add(Request $request ){
        //判断接收方式
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'=>"required"
            ]);
            //接收数据  并处理
            $pers =$request->post('pers');//获得权限
            //dd($pers);
            //添加角色
            $role = Role::create([
                'name'=>$request->post('name'),
                'guard_name'=>'admin',
                                 ]);
            //同步角色
            if($pers){
                $role->syncPermissions($pers);
            }

        }
        //显示视图
        //得到所有权限
        $pers =Permission::all();
        return view("admin.role.add",compact('pers'));
    }

    //修改
    public function edit( Request $request,$id ){
        //
        $role =Role::find($id);
        //dd($role);
        $rol =  $role->permissions()->pluck('id')->toArray();
        //dd($rol);
        //判断接收


            if($request->isMethod('post')){
                $this->validate( $request, [
                    'name' => "required"
                ] );
                //接收数据  并处理
                $pers = $request->post( 'pers' );//获得权限
                //dd($pers);
                //添加角色
                $role->update( [
                                   'name'       => $request->post( 'name' ),
                                   'guard_name' => 'admin',
                               ] );
                //同步角色
                if( $pers ){
                    $role->syncPermissions( $pers );
                }

            return redirect()->route("role.index")->with("success","修改成功");
        }

        //获得
        $pers =Permission::all();
        return view("admin.role.edit",compact("pers","role","rol"));

    }
    //删除
    public function del($id){
        $role=Role::find($id);
        $role->delete();
        return redirect()->route("role.index")->with("success","修改成功");
    }
}
