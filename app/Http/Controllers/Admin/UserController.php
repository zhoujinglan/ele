<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function add(Request $request){

        //判断接收放式
        if($request->isMethod("post")){

            $data= $this->validate( $request, [
                'shop_name'     => 'required | unq',
                "s_c_id" => "required",
                "shop_img" => "required|img",
                "shop_rating" => "required",
                "brand" => "required",

            ] );

            $data = $request->post();
            //dd($data);
            $file=$request->file('img');
            $data['shop_img']=$file->store('good_img','image');
            //dd($user_id=Auth::user()->id);

            $data['user_id']=Auth::user()->id;
            $data['status']=0;
            //dd($user_id);
            Shop::create($data);
            //页面跳转
            return redirect()->intended(route("index"))->with("success","申请成功");

        }
        $categories=ShopCategory::whwee("status",1)->get();
        return view("admin.user.add",compact("categories"));

    }
}
