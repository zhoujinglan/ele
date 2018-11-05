<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AddressController extends BaseController
{
    //显示所有
    public function index( Request $request ){
        //应该显示本用户的地址
        $userId=$request->get("user_id");
        $addresses=Address::where("user_id",$userId)->get();

        return $addresses;
    }
    
    //添加新地址
    public function add(Request $request){

        $data= $request->all();
        //dd($data);
        $validate = Validator::make($data,[
            'name'=>"required | unique:addresses",
            'detail_address'=>"required",
            'provence'=>"required",
            'city'=>'required',
            'area'=>'required',
            'user_id'=>"required",

            "tel"=>[
                'required',
                'regex:/^0?(13|14|15|16|17|18|19)[0-9]{9}$/',//电话号码的正则表达
                'unique:members'

            ],//电话号码用正则验证
        ]);
        //如果验证有误 返回错误
        if($validate->fails()){
            //返回错误
            return [
                'status' => "false",
                //获取错误信息
                "message" => $validate->errors()->first()
            ];
        }
        //验证码无误 数据入库
        Address::create($data);
        //数据返回
        return [
            'status' => "true",
            "message" => "添加成功"
        ];

    }

   //回显
    public function getOne( ){
        //回显
        $id= request()->get("id");
        $address = Address::find($id);
        return $address;

    }
    //修改
    public function edit(Request $request){
        //验证
        $id= request()->get("id");
        $address = Address::find($id);
        //接收收据
        $data = $request->all();
        if($address-> update($data)){
           //修改成功 返回信息
            return [
                'status' => "true",
                "message" => "修改成功成功"
            ];
        }else{
            return [
                'status' => "false",
                "message" => "修改失败"
            ];
        }

    }

}
